<?php

namespace App\Http\Controllers;

use App\Models\WooProduct;
use App\Services\WooCommerceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Throwable;

class DeenCommerceStoreController extends Controller
{
    public function index(Request $request, WooCommerceService $wooService): View
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $page = (int) $request->input('page', 1);

        $cacheKey = "deen_store_products_" . md5("{$search}_{$categoryId}_{$page}");
        $ttl = 600;

        $data = Cache::remember($cacheKey, $ttl, function () use ($wooService, $search, $categoryId, $page) {
            try {
                $query = [
                    'page' => $page,
                    'per_page' => 12,
                    'status' => 'publish',
                ];

                if ($search) {
                    $query['search'] = $search;
                }

                if ($categoryId) {
                    $query['category'] = $categoryId;
                }

                $response = $wooService->get('products', $query);
                $products = $response->json();
                $totalPages = max(1, (int) $response->header('X-WP-TotalPages', '1'));
                $totalProducts = (int) $response->header('X-WP-Total', '0');

                return [
                    'products' => is_array($products) ? $products : [],
                    'totalPages' => $totalPages,
                    'totalProducts' => $totalProducts,
                    'isLive' => true,
                ];
            } catch (Throwable $exception) {
                $localProducts = WooProduct::where('status', 'publish')
                    ->latest('id')
                    ->take(12)
                    ->get()
                    ->map(fn ($item) => [
                        'id' => $item->woo_id,
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'price' => $item->price,
                        'regular_price' => $item->regular_price,
                        'stock_quantity' => $item->stock_quantity,
                        'stock_status' => $item->stock_status,
                        'images' => $item->featured_image ? [['src' => $item->featured_image]] : [],
                    ])->toArray();

                return [
                    'products' => $localProducts,
                    'totalPages' => 1,
                    'totalProducts' => count($localProducts),
                    'isLive' => false,
                    'error' => $exception->getMessage(),
                ];
            }
        });

        $categories = Cache::remember('deen_store_categories', 1800, function () use ($wooService) {
            try {
                $res = $wooService->get('products/categories', ['per_page' => 10, 'hide_empty' => true]);
                return is_array($res->json()) ? $res->json() : [];
            } catch (Throwable $e) {
                return [];
            }
        });

        return view('welcome', [
            'products' => $data['products'],
            'totalPages' => $data['totalPages'],
            'totalProducts' => $data['totalProducts'],
            'isLive' => $data['isLive'] ?? false,
            'categories' => $categories,
            'currentPage' => $page,
            'searchQuery' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function categories(WooCommerceService $wooService): View
    {
        $categories = Cache::remember('deen_all_categories', 1800, function () use ($wooService) {
            try {
                $res = $wooService->get('products/categories', ['per_page' => 30, 'hide_empty' => false]);
                return is_array($res->json()) ? $res->json() : [];
            } catch (Throwable $e) {
                return [];
            }
        });

        return view('store.categories', compact('categories'));
    }

    public function categoryProducts(int $id, Request $request, WooCommerceService $wooService): View
    {
        $page = (int) $request->input('page', 1);
        $sort = $request->input('sort', 'newest');

        $query = [
            'category' => $id,
            'page' => $page,
            'per_page' => 12,
            'status' => 'publish',
        ];

        if ($sort === 'price_low') {
            $query['orderby'] = 'price';
            $query['order'] = 'asc';
        } elseif ($sort === 'price_high') {
            $query['orderby'] = 'price';
            $query['order'] = 'desc';
        }

        try {
            $response = $wooService->get('products', $query);
            $products = is_array($response->json()) ? $response->json() : [];
            $totalPages = max(1, (int) $response->header('X-WP-TotalPages', '1'));
            $totalProducts = (int) $response->header('X-WP-Total', '0');

            $category = $wooService->get("products/categories/{$id}")->json();
        } catch (Throwable $e) {
            $products = [];
            $totalPages = 1;
            $totalProducts = 0;
            $category = ['id' => $id, 'name' => 'Category #' . $id];
        }

        return view('store.category', compact('products', 'category', 'totalPages', 'totalProducts', 'page', 'sort'));
    }

    public function productDetail(int $id, WooCommerceService $wooService): View
    {
        try {
            $product = $wooService->getProduct($id, true);

            $relatedQuery = ['per_page' => 4, 'status' => 'publish'];
            if (! empty($product['categories'][0]['id'])) {
                $relatedQuery['category'] = $product['categories'][0]['id'];
            }

            $relatedResponse = $wooService->get('products', $relatedQuery);
            $relatedProducts = is_array($relatedResponse->json()) ? $relatedResponse->json() : [];
        } catch (Throwable $e) {
            abort(404, 'WooCommerce product not found.');
        }

        return view('store.product', compact('product', 'relatedProducts'));
    }

    public function checkout(): View
    {
        return view('store.checkout');
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|string|in:bkash,nagad,cod,card',
            'cart_data' => 'required|string',
        ]);

        $cartItems = json_decode($validated['cart_data'], true) ?? [];
        $totalAmount = array_reduce($cartItems, fn ($carry, $item) => $carry + ($item['price'] * $item['qty']), 0.0);

        $orderId = rand(100000, 999999);
        $orderData = [
            'order_id' => $orderId,
            'customer' => $validated,
            'items' => $cartItems,
            'total' => $totalAmount,
            'created_at' => now()->format('M d, Y H:i:s'),
        ];

        session(['recent_order_' . $orderId => $orderData]);

        return redirect()->route('store.order.success', ['id' => $orderId]);
    }

    public function orderSuccess(int $id): View
    {
        $order = session('recent_order_' . $id);

        if (! $order) {
            // Default sample order structure if session expired
            $order = [
                'order_id' => $id,
                'customer' => [
                    'first_name' => 'Valued',
                    'last_name' => 'Customer',
                    'email' => 'customer@example.com',
                    'phone' => '+880 1700-000000',
                    'address' => 'Dhaka, Bangladesh',
                    'city' => 'Dhaka',
                    'payment_method' => 'cod',
                ],
                'items' => [],
                'total' => 0.00,
                'created_at' => now()->format('M d, Y H:i:s'),
            ];
        }

        return view('store.success', compact('order'));
    }

    public function showProduct(int $id, WooCommerceService $wooService): JsonResponse
    {
        try {
            $product = $wooService->getProduct($id, true);
            return response()->json(['success' => true, 'product' => $product]);
        } catch (Throwable $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }
}
