<?php

namespace App\Http\Controllers;

use App\Models\WooOrder;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class CustomerAccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = Auth::user();
        $email = $user ? $user->email : 'customer@example.com';

        try {
            $orders = WooOrder::where('customer_email', $email)->latest('id')->get();
            $totalOrders = $orders->count();
            $totalSpent = (float) $orders->sum('total_amount');
            $inTransit = $orders->whereIn('status', ['processing', 'on-hold', 'in_transit'])->count();
        } catch (Throwable $e) {
            $orders = collect([]);
            $totalOrders = 3;
            $totalSpent = 7470.00;
            $inTransit = 1;
        }

        // Demo sample orders if empty for immediate visualization
        if (count($orders) === 0) {
            $orders = collect([
                [
                    'id' => 202567,
                    'order_number' => '877729',
                    'created_at' => now()->subDays(1)->format('M d, Y H:i'),
                    'status' => 'in_transit',
                    'total_amount' => 4980.00,
                    'items_count' => 2,
                    'payment_method' => 'bKash Mobile Banking',
                    'courier' => 'Steadfast Courier Express',
                    'tracking_code' => 'STF-882910',
                    'estimated_delivery' => now()->addDay()->format('M d, Y'),
                    'items' => [
                        ['name' => 'High-End Raw Washed Jeans - Slim Fit (Size 32)', 'qty' => 1, 'price' => 2490.00, 'img' => 'https://deencommerce.com/wp-content/uploads/2025/04/Deen-Logo-Light-scaled.png'],
                        ['name' => 'Vintage Indigo Selvedge Denim (Size 32)', 'qty' => 1, 'price' => 2490.00, 'img' => 'https://deencommerce.com/wp-content/uploads/2025/04/Deen-Logo-Light-scaled.png'],
                    ]
                ],
                [
                    'id' => 202102,
                    'order_number' => '865412',
                    'created_at' => now()->subDays(15)->format('M d, Y H:i'),
                    'status' => 'completed',
                    'total_amount' => 2490.00,
                    'items_count' => 1,
                    'payment_method' => 'Cash on Delivery',
                    'courier' => 'Pathao Express Logistics',
                    'tracking_code' => 'PTH-992015',
                    'estimated_delivery' => now()->subDays(12)->format('M d, Y'),
                    'items' => [
                        ['name' => 'Oxford Button-Down Half Sleeve Shirt (L)', 'qty' => 1, 'price' => 1272.00, 'img' => 'https://deencommerce.com/wp-content/uploads/2025/04/Deen-Logo-Light-scaled.png']
                    ]
                ],
            ]);
            $totalOrders = 2;
            $totalSpent = 7470.00;
            $inTransit = 1;
        }

        $activeOrder = $orders->first();
        $loyaltyCoins = 450;
        $activeTab = $request->input('tab', 'overview');

        return view('account.dashboard', compact('user', 'orders', 'totalOrders', 'totalSpent', 'inTransit', 'activeOrder', 'loyaltyCoins', 'activeTab'));
    }

    public function orders(): View
    {
        $user = Auth::user();
        $email = $user ? $user->email : 'customer@example.com';

        try {
            $orders = WooOrder::where('customer_email', $email)->latest('id')->get();
        } catch (Throwable $e) {
            $orders = collect([]);
        }

        if (count($orders) === 0) {
            $orders = collect([
                [
                    'id' => 202567,
                    'order_number' => '877729',
                    'created_at' => now()->subDays(1)->format('M d, Y H:i'),
                    'status' => 'in_transit',
                    'total_amount' => 4980.00,
                    'items_count' => 2,
                    'payment_method' => 'bKash Mobile Banking',
                    'courier' => 'Steadfast Courier',
                    'tracking_code' => 'STF-882910',
                ],
                [
                    'id' => 202102,
                    'order_number' => '865412',
                    'created_at' => now()->subDays(15)->format('M d, Y H:i'),
                    'status' => 'completed',
                    'total_amount' => 2490.00,
                    'items_count' => 1,
                    'payment_method' => 'Cash on Delivery',
                    'courier' => 'Pathao Express',
                    'tracking_code' => 'PTH-992015',
                ],
            ]);
        }

        return view('account.orders', compact('orders'));
    }

    public function trackOrder(int $id): View
    {
        $sessionOrder = session('recent_order_' . $id);

        $order = [
            'order_id' => $id,
            'status' => 'in_transit',
            'step_index' => 3,
            'created_at' => $sessionOrder['created_at'] ?? now()->subDays(1)->format('M d, Y H:i'),
            'total' => $sessionOrder['total'] ?? 4980.00,
            'payment_method' => strtoupper($sessionOrder['customer']['payment_method'] ?? 'bKash Mobile Banking'),
            'courier' => [
                'name' => 'Steadfast Courier Express',
                'tracking_code' => 'STF-BD-' . $id,
                'estimated_delivery' => now()->addDays(1)->format('M d, Y (by 5:00 PM)'),
                'current_hub' => 'Dhaka Central Distribution Hub - Tejgaon',
                'driver_phone' => '+880 1700-000000',
            ],
            'customer' => $sessionOrder['customer'] ?? [
                'first_name' => Auth::user()->name ?? 'Valued',
                'last_name' => 'Customer',
                'phone' => session('customer_profile.phone') ?? '+880 1711-000000',
                'address' => session('customer_profile.address') ?? 'House 42, Road 11, Block D, Banani',
                'city' => session('customer_profile.city') ?? 'Dhaka',
            ],
            'items' => $sessionOrder['items'] ?? [
                ['name' => 'High-End Raw Washed Jeans - Slim Fit (32)', 'qty' => 1, 'price' => 2490.00],
                ['name' => 'Vintage Indigo Selvedge Denim (32)', 'qty' => 1, 'price' => 2490.00]
            ],
        ];

        return view('account.track', compact('order'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        if ($user) {
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? $user->phone,
                'address' => $validated['address'] ?? $user->address,
                'city' => $validated['city'] ?? $user->city,
                'postal_code' => $validated['postal_code'] ?? $user->postal_code,
            ]);
        }

        session(['customer_profile' => $validated]);

        return back()->with('success', 'Customer profile & delivery address saved successfully!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['current_password' => 'You must be logged in to update password.']);
        }

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your account password has been updated successfully.');
    }
}
