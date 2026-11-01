<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Guest;
use App\Models\User;

class ExampleTest extends TestCase
{
    /**
     * Test root route loads the wedding invitation directly (200 OK).
     */
    public function test_root_renders_wedding_invitation(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Buka Undangan');
        $response->assertSee('undangan');
    }

    /**
     * Test guest is redirected to login when trying to access dashboard unauthenticated.
     */
    public function test_unauthenticated_user_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test login screen can be rendered.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Admin Monitoring Portal');
        $response->assertSee('Kata Sandi');
    }

    /**
     * Test admin can login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@diandodhid.com'],
            ['name' => 'Admin', 'password' => bcrypt('admin123')]
        );

        $response = $this->post('/login', [
            'login' => 'admin@diandodhid.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test authenticated admin can access dashboard screen.
     */
    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@diandodhid.com'],
            ['name' => 'Admin', 'password' => bcrypt('admin123')]
        );

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tabel Monitoring Tamu Undangan');
    }

    /**
     * Test digital invitation via slug renders with personalized guest name.
     */
    public function test_invitation_screen_renders_with_guest_name(): void
    {
        $guest = Guest::first() ?? Guest::createWithUniqueSlug([
            'name' => 'Tamu Percobaan',
            'category' => 'Teman'
        ]);

        $response = $this->get('/to/' . $guest->slug);
        $response->assertStatus(200);
        $response->assertSee($guest->name);
    }
}
