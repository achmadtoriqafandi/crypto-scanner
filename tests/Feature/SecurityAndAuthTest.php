<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityAndAuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_login_page_renders_without_exposed_credentials(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('password123');
        $response->assertDontSee('admin@cryptoscanner.com');
        $response->assertSee('Disclaimer Risiko Trading');
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_registration_can_be_disabled(): void
    {
        config(['auth.allow_registration' => false]);

        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Ditutup');

        $postResponse = $this->post('/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $postResponse->assertSessionHasErrors('email');
    }

    public function test_rate_limiting_on_login(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'wrong@example.com',
                'password' => 'wrongpass',
            ]);
        }

        $throttledResponse = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $throttledResponse->assertStatus(429);
    }

    public function test_login_can_be_disabled(): void
    {
        config(['auth.allow_login' => false]);

        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login Dinonaktifkan');

        $postResponse = $this->post('/login', [
            'email' => 'admin@cryptoscanner.com',
            'password' => 'somepassword',
        ]);

        $postResponse->assertSessionHasErrors('email');
    }

    public function test_public_access_mode_allows_guest_access(): void
    {
        config(['auth.public_access' => true]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Public Access Mode');
    }
}
