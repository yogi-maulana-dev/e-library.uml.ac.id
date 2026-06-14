<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_access_super_admin_modules_by_direct_url(): void
    {
        $this->seed();

        $staff = User::where('email', 'staff@elibrary.local')->firstOrFail();

        $this->actingAs($staff)->get('/admin/settings')->assertForbidden();
        $this->actingAs($staff)->get('/admin/roles')->assertForbidden();
        $this->actingAs($staff)->get('/admin/permissions')->assertForbidden();
        $this->actingAs($staff)->get('/admin/books')->assertOk();
    }

    public function test_member_cannot_access_admin_area(): void
    {
        $this->seed();

        $member = User::where('email', 'test@example.com')->firstOrFail();

        $this->actingAs($member)->get('/admin')->assertForbidden();
    }

    public function test_admin_crud_rejects_path_traversal_payloads(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@elibrary.local')->firstOrFail();

        $this->actingAs($admin)
            ->from('/admin/ebooks/create')
            ->post('/admin/ebooks', [
                'title' => 'Traversal Test',
                'slug' => 'traversal-test',
                'description' => 'Testing path traversal payload.',
                'file_path' => '../.env',
                'file_type' => 'pdf',
                'file_size' => '1',
                'is_active' => '1',
                'download_count' => '0',
                'view_count' => '0',
                'rating' => '0',
            ])
            ->assertSessionHasErrors('file_path')
            ->assertRedirect('/admin/ebooks/create');

        $this->assertDatabaseMissing('ebooks', ['slug' => 'traversal-test']);
    }

    public function test_weak_registration_password_is_rejected(): void
    {
        $this->post('/register', [
            'name' => 'Weak User',
            'email' => 'weak@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');
    }
}
