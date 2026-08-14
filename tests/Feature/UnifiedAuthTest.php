<?php

namespace Tests\Feature;

use Tests\TestCase;

class UnifiedAuthTest extends TestCase
{
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Client Membership Hub');
        $response->assertSee('Google');
        $response->assertSee('Facebook');
        $response->assertSee('50 Free Coins');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Client Membership Hub');
        $response->assertSee('Create Account');
        $response->assertSee('Facebook');
    }

    public function test_google_oauth_redirects(): void
    {
        $response = $this->get(route('auth.google'));
        $response->assertRedirect();
    }

    public function test_facebook_oauth_redirects(): void
    {
        $response = $this->get(route('auth.facebook'));
        $response->assertRedirect();
    }
}
