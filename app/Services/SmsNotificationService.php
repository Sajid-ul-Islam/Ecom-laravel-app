<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsNotificationService
{
    /**
     * Send order placement confirmation SMS to customer.
     *
     * @param string $phone
     * @param string|int $orderId
     * @param float $total
     * @return bool
     */
    public function sendOrderConfirmationSms(string $phone, $orderId, float $total): bool
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $message = "Deen Commerce: Assalamu Alaikum! Your Order #{$orderId} (৳" . number_format($total, 2) . ") has been confirmed! Track: https://deencommerce.com/woocommerce/orders";

        // Log SMS event for audit trail
        Log::info("SMS Dispatch to {$cleanPhone}: {$message}");

        // Simulate Greenweb / SSL Wireless SMS API Gateway
        try {
            $apiKey = config('services.sms.api_key', 'DEMO_GREENWEB_KEY');
            if ($apiKey && $apiKey !== 'DEMO_GREENWEB_KEY') {
                Http::timeout(5)->post('https://api.greenweb.com.bd/api.php', [
                    'token' => $apiKey,
                    'to' => $cleanPhone,
                    'message' => $message,
                ]);
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning("SMS Gateway Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order status update SMS.
     *
     * @param string $phone
     * @param string|int $orderId
     * @param string $status
     * @return bool
     */
    public function sendOrderStatusSms(string $phone, $orderId, string $status): bool
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $message = "Deen Commerce: Order #{$orderId} status updated to: " . strtoupper($status) . ". Thank you for shopping with Deen!";

        Log::info("SMS Status Update to {$cleanPhone}: {$message}");
        return true;
    }
}
