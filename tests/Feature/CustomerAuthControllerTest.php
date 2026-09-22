<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_with_mobile_and_password(): void
    {
        $response = $this->post(route('customer.register.store'), [
            'name' => 'کاربر تست',
            'phone' => '۰۹۱۲۱۲۳۴۵۶۷',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $user = User::where('phone', '09121234567')->firstOrFail();

        $response->assertRedirect(route('account.orders'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('Secret123', $user->password));
    }

    public function test_registered_customer_can_login_with_mobile_and_password(): void
    {
        $user = User::factory()->create([
            'phone' => '09121111111',
            'password' => Hash::make('Secret123'),
        ]);

        $response = $this->post(route('customer.login.password'), [
            'phone' => '09121111111',
            'password' => 'Secret123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('account.orders'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_login_rejects_wrong_password(): void
    {
        User::factory()->create([
            'phone' => '09122222222',
            'password' => Hash::make('Secret123'),
        ]);

        $this->post(route('customer.login.password'), [
            'phone' => '09122222222',
            'password' => 'WrongPassword',
        ])->assertSessionHasErrors(['phone']);

        $this->assertGuest();
    }

    public function test_registration_rejects_duplicate_phone(): void
    {
        User::factory()->create(['phone' => '09123333333']);

        $this->post(route('customer.register.store'), [
            'name' => 'کاربر دوم',
            'phone' => '09123333333',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ])->assertSessionHasErrors(['phone']);

        $this->assertSame(1, User::where('phone', '09123333333')->count());
        $this->assertGuest();
    }

    public function test_customer_auth_pages_render_password_and_registration_options(): void
    {
        $this->get(route('customer.login'))
            ->assertOk()
            ->assertSee('شماره موبایل')
            ->assertSee('رمز عبور')
            ->assertSee('ورود با کد یکبارمصرف');

        $this->get(route('customer.register'))
            ->assertOk()
            ->assertSee('ساخت حساب کاربری')
            ->assertSee('تکرار رمز عبور');
    }
}
