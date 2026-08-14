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
                    'stock_status' => 'instock',
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

                if (is_array($products)) {
                    // Filter out out-of-stock items
                    $products = array_filter($products, function ($p) {
                        $status = $p['stock_status'] ?? 'instock';
                        $qty = $p['stock_quantity'] ?? 1;
                        return $status !== 'outofstock' && ($qty === null || $qty > 0);
                    });

                    // Sort by most-stocked products first (highest stock quantity first)
                    usort($products, function ($a, $b) {
                        $qtyA = (int) ($a['stock_quantity'] ?? 10);
                        $qtyB = (int) ($b['stock_quantity'] ?? 10);
                        return $qtyB <=> $qtyA;
                    });
                } else {
                    $products = [];
                }

                return [
                    'products' => array_values($products),
                    'totalPages' => $totalPages,
                    'totalProducts' => $totalProducts,
                    'isLive' => true,
                ];
            } catch (Throwable $exception) {
                $localProducts = [];
                try {
                    $localProducts = WooProduct::where('status', 'publish')
                        ->where(function ($q) {
                            $q->where('stock_status', 'instock')->orWhereNull('stock_status');
                        })
                        ->where(function ($q) {
                            $q->where('stock_quantity', '>', 0)->orWhereNull('stock_quantity');
                        })
                        ->orderByDesc('stock_quantity')
                        ->take(12)
                        ->get()
                        ->map(fn ($item) => [
                            'id' => $item->woo_id,
                            'name' => $item->name,
                            'slug' => $item->slug,
                            'price' => $item->price,
                            'regular_price' => $item->regular_price,
                            'stock_quantity' => $item->stock_quantity,
                            'stock_status' => $item->stock_status ?? 'instock',
                            'images' => $item->featured_image ? [['src' => $item->featured_image]] : [],
                        ])->toArray();
                } catch (Throwable $dbEx) {
                    $localProducts = [];
                }

                return [
                    'products' => $localProducts,
                    'totalPages' => 1,
                    'totalProducts' => count($localProducts),
                    'isLive' => false,
                    'error' => $exception->getMessage(),
                ];
            }
        });

        // Fetch ALL Categories (per_page => 100) with fallback list
        $categories = Cache::remember('deen_store_all_categories_list', 1800, function () use ($wooService) {
            try {
                $res = $wooService->get('products/categories', ['per_page' => 100, 'hide_empty' => false]);
                $apiCategories = is_array($res->json()) ? $res->json() : [];
                if (! empty($apiCategories)) {
                    return $apiCategories;
                }
            } catch (Throwable $e) {
                // Fallthrough to fallback categories
            }

            return [
                ['id' => 1, 'name' => 'Denim & Jeans', 'slug' => 'denim-jeans', 'count' => 142, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg']],
                ['id' => 2, 'name' => 'Casual Shirts', 'slug' => 'casual-shirts', 'count' => 98, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Category-1.webp']],
                ['id' => 3, 'name' => 'Polos & T-Shirts', 'slug' => 'polos-tshirts', 'count' => 84, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/11/Basic-polo.webp']],
                ['id' => 4, 'name' => 'Outerwear & Jackets', 'slug' => 'outerwear-jackets', 'count' => 45, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Active-Wear-Category.webp']],
                ['id' => 5, 'name' => 'Trousers & Chinos', 'slug' => 'trousers-chinos', 'count' => 62, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/11/Belt-.webp']],
                ['id' => 6, 'name' => 'Accessories', 'slug' => 'accessories', 'count' => 35, 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/08/Accessories.webp']],
            ];
        });

        // Fetch Live Hero Slideshow Products (featured or top in-stock products)
        $heroSlides = Cache::remember('deen_hero_slideshow_products', 300, function () use ($wooService) {
            try {
                $response = $wooService->get('products', [
                    'featured' => true,
                    'per_page' => 5,
                    'status' => 'publish',
                    'stock_status' => 'instock',
                ]);
                $slides = $response->json();
                if (is_array($slides) && count($slides) > 0) {
                    return $slides;
                }
                // Fallback to top in-stock products if featured array is empty
                $response = $wooService->get('products', [
                    'per_page' => 5,
                    'status' => 'publish',
                    'stock_status' => 'instock',
                ]);
                $slides = $response->json();
                if (is_array($slides) && count($slides) > 0) {
                    return $slides;
                }
            } catch (Throwable $e) {
                // Fallthrough to local fallback slides
            }

            return [
                [
                    'id' => 202567,
                    'name' => 'High-End Raw Washed Slim Fit Jeans',
                    'price' => '2490',
                    'regular_price' => '2990',
                    'description' => 'Crafted from 13.5oz premium stretch denim, hand-washed in Dhaka.',
                    'images' => [['src' => 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg']],
                    'tagline' => 'NEW SEASON DENIM COLLECTION 2026',
                    'badge' => '20% OFF DEAL',
                ],
                [
                    'id' => 202568,
                    'name' => '100% Oxford Cotton Tailored Casual Shirt',
                    'price' => '1890',
                    'regular_price' => '2290',
                    'description' => 'Breathable Oxford cotton with button-down collar and tailored fit.',
                    'images' => [['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Category-1.webp']],
                    'tagline' => 'URBAN SHIRT COLLECTION',
                    'badge' => 'BEST SELLER',
                ],
                [
                    'id' => 202569,
                    'name' => 'Heavyweight Biker Leather & Active Jacket',
                    'price' => '4890',
                    'regular_price' => '5990',
                    'description' => 'Weatherproof outer shell with quilted thermal lining and matte finish.',
                    'images' => [['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Active-Wear-Category.webp']],
                    'tagline' => 'EXCLUSIVE ACTIVE & OUTERWEAR',
                    'badge' => 'PREMIUM FASHION',
                ],
            ];
        });

        return view('welcome', [
            'products' => $data['products'],
            'totalPages' => $data['totalPages'],
            'totalProducts' => $data['totalProducts'],
            'isLive' => $data['isLive'] ?? false,
            'categories' => $categories,
            'heroSlides' => $heroSlides,
            'currentPage' => $page,
            'searchQuery' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }


    public function categories(WooCommerceService $wooService): View
    {
        $categories = Cache::remember('deen_all_categories_page', 1800, function () use ($wooService) {
            try {
                $res = $wooService->get('products/categories', ['per_page' => 100, 'hide_empty' => false]);
                $apiCats = is_array($res->json()) ? $res->json() : [];
                if (! empty($apiCats)) {
                    return $apiCats;
                }
            } catch (Throwable $e) {
                // Fallback
            }

            return [
                ['id' => 1, 'name' => 'Denim & Jeans', 'slug' => 'denim-jeans', 'count' => 142, 'description' => 'Premium slim fit and relaxed raw washed denim jeans.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2026/07/101-0100-149-Front.jpg']],
                ['id' => 2, 'name' => 'Casual Shirts', 'slug' => 'casual-shirts', 'count' => 98, 'description' => '100% Oxford cotton long sleeve and short sleeve shirts.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Category-1.webp']],
                ['id' => 3, 'name' => 'Polos & T-Shirts', 'slug' => 'polos-tshirts', 'count' => 84, 'description' => 'Pique cotton solid and printed polo shirts.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/11/Basic-polo.webp']],
                ['id' => 4, 'name' => 'Outerwear & Jackets', 'slug' => 'outerwear-jackets', 'count' => 45, 'description' => 'Urban leather jackets, denim jackets, and winter coats.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/10/Active-Wear-Category.webp']],
                ['id' => 5, 'name' => 'Trousers & Chinos', 'slug' => 'trousers-chinos', 'count' => 62, 'description' => 'Stretch cotton chinos and formal trousers.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/11/Belt-.webp']],
                ['id' => 6, 'name' => 'Accessories & Leather', 'slug' => 'accessories', 'count' => 35, 'description' => 'Genuine leather belts, wallets, and caps.', 'image' => ['src' => 'https://deencommerce.com/wp-content/uploads/2025/08/Accessories.webp']],
            ];
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
            'stock_status' => 'instock',
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

            if (is_array($products)) {
                // Filter out out-of-stock products
                $products = array_filter($products, function ($p) {
                    $status = $p['stock_status'] ?? 'instock';
                    $qty = $p['stock_quantity'] ?? 1;
                    return $status !== 'outofstock' && ($qty === null || $qty > 0);
                });

                if ($sort === 'newest') {
                    // Sort most stocked products first by default
                    usort($products, function ($a, $b) {
                        $qtyA = (int) ($a['stock_quantity'] ?? 10);
                        $qtyB = (int) ($b['stock_quantity'] ?? 10);
                        return $qtyB <=> $qtyA;
                    });
                }
                $products = array_values($products);
            }

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
        } catch (Throwable $e) {
            $localProd = null;
            try {
                $localProd = WooProduct::where('woo_id', $id)->first();
            } catch (Throwable $dbEx) {
                $localProd = null;
            }

            if ($localProd) {
                $product = [
                    'id' => $localProd->woo_id,
                    'name' => $localProd->name,
                    'price' => $localProd->price,
                    'regular_price' => $localProd->regular_price,
                    'stock_quantity' => $localProd->stock_quantity,
                    'sku' => $localProd->sku ?? ('SKU-' . $id),
                    'description' => 'Premium Bangladesh denim and urban apparel line.',
                    'short_description' => 'High quality fashion item from Deen Commerce.',
                    'images' => $localProd->featured_image ? [['src' => $localProd->featured_image]] : [],
                ];
            } else {
                $product = [
                    'id' => $id,
                    'name' => 'High-End Raw Washed Jeans - Slim Fit',
                    'price' => 2490.00,
                    'regular_price' => 2990.00,
                    'stock_quantity' => 15,
                    'sku' => 'DN-JNS-001',
                    'description' => 'Crafted from premium 13.5oz stretch denim with custom whiskering and hand-washed finish. Slim fit through hip and thigh.',
                    'short_description' => 'Slim fit raw washed denim jeans with 2% elastane stretch.',
                    'images' => [
                        ['src' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop&q=80']
                    ],
                ];
            }
        }

        try {
            $relatedQuery = ['per_page' => 4, 'status' => 'publish', 'stock_status' => 'instock'];
            if (! empty($product['categories'][0]['id'])) {
                $relatedQuery['category'] = $product['categories'][0]['id'];
            }
            $relatedResponse = $wooService->get('products', $relatedQuery);
            $relatedProducts = is_array($relatedResponse->json()) ? $relatedResponse->json() : [];
        } catch (Throwable $e) {
            $relatedProducts = [];
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
