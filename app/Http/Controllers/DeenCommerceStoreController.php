<?php

namespace App\Http\Controllers;

use App\Models\WooProduct;
use App\Services\WooCommerceService;
use Illuminate\Http\JsonResponse;
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
        $ttl = 600; // 10 minutes cache

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
                // Fallback to local WooProduct database if remote API has network error
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

        // Fetch Categories
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
