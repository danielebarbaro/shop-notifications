<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function passwordRequestRoute()
    {
        return route('password.request');
    }

    protected function passwordEmailRoute()
    {
        return route('password.email');
    }

    public function testUserCanViewAnEmailPasswordForm()
    {
        $response = $this->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    public function testUserCanViewAnEmailPasswordFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    public function testUserReceivesAnEmailWithAPasswordResetLink()
    {
        Notification::fake();
        $user = factory(User::class)->create([
            'email' => 'danny@localhost.sample',
        ]);

        $response = $this->post($this->passwordEmailRoute(), [
            'email' => 'danny@localhost.sample',
        ]);

        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    public function testUserDoesNotReceiveEmailWhenNotRegistered()
    {
        Notification::fake();

        $response = $this->from($this->passwordEmailRoute())->post($this->passwordEmailRoute(), [
            'email' => 'antani@example.com',
        ]);

        $response->assertRedirect($this->passwordEmailRoute());
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(factory(User::class)
            ->make(['email' => 'antani@example.com']), ResetPassword::class);
    }

    public function testEmailIsRequired()
    {
        $response = $this->from($this->passwordEmailRoute())->post($this->passwordEmailRoute(), []);

        $response->assertRedirect($this->passwordEmailRoute());
        $response->assertSessionHasErrors('email');
    }

    public function testEmailIsAValidEmail()
    {
        $response = $this->from($this->passwordEmailRoute())->post($this->passwordEmailRoute(), [
            'email' => 'invalid-email',
        ]);

        $response->assertRedirect($this->passwordEmailRoute());
        $response->assertSessionHasErrors('email');
    }
}
