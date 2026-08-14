<?php

namespace App\Services;

use App\Exceptions\WooCommerceException;
use App\Models\WooApiLog;
use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WooCommerceService
{
    public function paginate(string $endpoint, array $query = []): Generator
    {
        $page = 1;
        $perPage = (int) config('woocommerce.per_page', 100);
        $totalPages = 1;

        do {
            $response = $this->get($endpoint, array_merge($query, [
                'page' => $page,
                'per_page' => $perPage,
            ]));

            $items = $response->json();
            $items = is_array($items) ? $items : [];

            foreach ($items as $item) {
                if (is_array($item)) {
                    yield $item;
                }
            }

            $totalPages = max(1, (int) $response->header('X-WP-TotalPages', (string) $totalPages));
            $page++;
        } while ($page <= $totalPages && $items !== []);
    }

    public function getProducts(array $query = []): Generator
    {
        return $this->paginate('products', $query);
    }

    public function getOrders(array $query = []): Generator
    {
        $statuses = $query['status'] ?? implode(',', config('woocommerce.order_statuses', ['processing', 'completed']));

        return $this->paginate('orders', array_merge($query, [
            'status' => $statuses,
        ]));
    }

    public function getProduct(int $id, bool $useCache = true): array
    {
        $ttl = (int) config('woocommerce.cache_ttl', 60);
        $fetcher = fn () => $this->get("products/{$id}")->json() ?? [];

        if ($useCache && $ttl > 0) {
            return Cache::remember("woo:product:{$id}", $ttl, $fetcher);
        }

        return $fetcher();
    }

    public function get(string $endpoint, array $query = []): Response
    {
        return $this->request('GET', $endpoint, $query);
    }

    public function request(string $method, string $endpoint, array $query = []): Response
    {
        $endpoint = ltrim($endpoint, '/');
        $url = $this->url($endpoint);
        $started = microtime(true);
        $statusCode = null;
        $errorMessage = null;
        $success = false;

        try {
            $response = $this->client()
                ->send($method, $url, ['query' => $query]);

            $statusCode = $response->status();
            $success = $response->successful();

            if (! $success) {
                $errorMessage = $this->errorFromResponse($response);
                throw new WooCommerceException(
                    sprintf('WooCommerce %s %s failed with HTTP %s: %s', strtoupper($method), $endpoint, $statusCode, $errorMessage),
                    $statusCode,
                    [
                        'endpoint' => $endpoint,
                        'method' => $method,
                        'query' => $query,
                        'body' => $response->json(),
                    ]
                );
            }

            return $response;
        } catch (WooCommerceException $exception) {
            $statusCode = $exception->statusCode;
            $errorMessage = $exception->getMessage();
            throw $exception;
        } catch (RequestException $exception) {
            $statusCode = $exception->response?->status();
            $errorMessage = $exception->getMessage();

            throw new WooCommerceException(
                sprintf('WooCommerce HTTP error for %s %s: %s', strtoupper($method), $endpoint, $errorMessage),
                $statusCode,
                [
                    'endpoint' => $endpoint,
                    'method' => $method,
                    'query' => $query,
                ],
                $exception
            );
        } catch (ConnectionException $exception) {
            $errorMessage = $exception->getMessage();

            throw new WooCommerceException(
                sprintf('WooCommerce connection failed for %s %s: %s', strtoupper($method), $endpoint, $errorMessage),
                null,
                [
                    'endpoint' => $endpoint,
                    'method' => $method,
                    'query' => $query,
                ],
                $exception
            );
        } catch (Throwable $exception) {
            $errorMessage = $exception->getMessage();

            throw new WooCommerceException(
                sprintf('WooCommerce request failed for %s %s: %s', strtoupper($method), $endpoint, $errorMessage),
                null,
                [
                    'endpoint' => $endpoint,
                    'method' => $method,
                    'query' => $query,
                ],
                $exception
            );
        } finally {
            $this->logRequest($method, $endpoint, $query, $statusCode, $started, $success, $errorMessage);
        }
    }

    public function backoffMilliseconds(int $attempt, ?Throwable $exception = null): int
    {
        $retryAfterMs = $this->retryAfterMilliseconds($exception);
        if ($retryAfterMs !== null) {
            return min($retryAfterMs, (int) config('woocommerce.retry.max_ms', 30000));
        }

        $base = max(0, (int) config('woocommerce.retry.base_ms', 1000));
        $max = (int) config('woocommerce.retry.max_ms', 30000);
        $delay = $base * (2 ** max(0, $attempt - 1));

        return (int) min($delay, $max);
    }

    public function shouldRetry(Throwable $exception): bool
    {
        if ($exception instanceof ConnectionException) {
            return true;
        }

        $status = $this->statusFrom($exception);

        return in_array($status, [408, 425, 429, 500, 502, 503, 504], true);
    }

    protected function client(): PendingRequest
    {
        $times = max(1, (int) config('woocommerce.retry.times', 4));

        return Http::baseUrl($this->baseUrl())
            ->withBasicAuth(
                (string) config('woocommerce.consumer_key'),
                (string) config('woocommerce.consumer_secret')
            )
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('woocommerce.timeout', 30))
            ->connectTimeout((int) config('woocommerce.connect_timeout', 10))
            ->retry(
                $times,
                fn (int $attempt, Throwable $exception) => $this->backoffMilliseconds($attempt, $exception),
                fn (Throwable $exception) => $this->shouldRetry($exception)
            )
            ->throw();
    }

    protected function url(string $endpoint): string
    {
        return $this->baseUrl() . ltrim($endpoint, '/');
    }

    protected function baseUrl(): string
    {
        $version = trim((string) config('woocommerce.version', 'wc/v3'), '/');

        return rtrim((string) config('woocommerce.url'), '/') . "/wp-json/{$version}/";
    }

    protected function retryAfterMilliseconds(?Throwable $exception): ?int
    {
        if (! $exception instanceof RequestException || ! $exception->response) {
            return null;
        }

        $header = $exception->response->header('Retry-After');
        if ($header === null || $header === '') {
            return null;
        }

        if (is_numeric($header)) {
            return (int) $header * 1000;
        }

        $until = strtotime($header);
        if ($until === false) {
            return null;
        }

        return max(0, ($until - time()) * 1000);
    }

    protected function statusFrom(Throwable $exception): ?int
    {
        if ($exception instanceof RequestException) {
            return $exception->response?->status();
        }

        if ($exception instanceof WooCommerceException) {
            return $exception->statusCode;
        }

        return null;
    }

    protected function errorFromResponse(Response $response): string
    {
        $json = $response->json();

        if (is_array($json)) {
            return (string) ($json['message'] ?? $json['code'] ?? $response->body());
        }

        return $response->reason() ?: 'Unknown error';
    }

    protected function logRequest(
        string $method,
        string $endpoint,
        array $query,
        ?int $statusCode,
        float $started,
        bool $success,
        ?string $errorMessage
    ): void {
        $responseTimeMs = (int) round((microtime(true) - $started) * 1000);
        $context = [
            'method' => strtoupper($method),
            'endpoint' => $endpoint,
            'status_code' => $statusCode,
            'response_time_ms' => $responseTimeMs,
            'success' => $success,
        ];

        Log::channel('woocommerce')->log(
            $success ? 'info' : 'error',
            sprintf(
                'WooCommerce %s %s [%s] %sms',
                strtoupper($method),
                $endpoint,
                $statusCode ?? 'n/a',
                $responseTimeMs
            ),
            $context + ['error' => $errorMessage]
        );

        try {
            WooApiLog::create([
                'method' => strtoupper($method),
                'endpoint' => $endpoint,
                'query' => $query,
                'status_code' => $statusCode,
                'response_time_ms' => $responseTimeMs,
                'success' => $success,
                'error_message' => $errorMessage,
            ]);
        } catch (Throwable $exception) {
            Log::channel('woocommerce')->warning('Failed to persist WooCommerce API log', [
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
