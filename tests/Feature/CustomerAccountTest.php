<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    public function test_customer_dashboard_renders_successfully(): void
    {
        $response = $this->get('/my-account');
        $response->assertStatus(200);
        $response->assertSee('Customer Profile');
        $response->assertSee('In Transit');
    }

    public function test_customer_orders_page_renders_successfully(): void
    {
        $response = $this->get('/my-account/orders');
        $response->assertStatus(200);
        $response->assertSee('My Orders');
        $response->assertSee('Courier Shipments');
    }

    public function test_order_tracking_page_renders_successfully(): void
    {
        $response = $this->get('/my-account/orders/202567');
        $response->assertStatus(200);
        $response->assertSee('Track Order #202567');
        $response->assertSee('IN TRANSIT WITH COURIER');
    }

    public function test_customer_can_update_profile_details(): void
    {
        $response = $this->post('/my-account/profile', [
            'name' => 'Tanvir Ahmed',
            'phone' => '+880 1711-123456',
            'address' => 'Road 11, Block D, Banani',
            'city' => 'Dhaka',
            'postal_code' => '1213',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('+880 1711-123456', session('customer_profile.phone'));
    }
}
