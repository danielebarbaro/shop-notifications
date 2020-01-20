<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function homeRoute()
    {
        return route('home');
    }

    protected function registerRoute()
    {
        return route('register');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    protected function successfulRegisterRoute()
    {
        return route('home');
    }

    public function testUserCanViewARegistrationForm()
    {
        $response = $this->get($this->registerRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function testUserCannotViewARegistrationFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->registerRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanRegister()
    {
        $response = $this->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.local',
            'password' => 'i-love-php!',
            'password_confirmation' => 'i-love-php!',
        ]);
        $users = User::all();
        $response->assertRedirect($this->successfulRegisterRoute());
        $this->assertCount(1, $users);
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john.doe@example.local', $user->email);
        $this->assertTrue(Hash::check('i-love-php!', $user->password));
    }

    public function testUserCannotRegisterWithoutName()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => '',
            'email' => 'john.doe@example.local',
            'password' => 'i-love-php!',
            'password_confirmation' => 'i-love-php!',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutEmail()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'i-love-php!',
            'password_confirmation' => 'i-love-php!',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithInvalidEmail()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'no-mail',
            'password' => 'i-love-php!',
            'password_confirmation' => 'i-love-php!',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPassword()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.local',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPasswordConfirmation()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.local',
            'password' => 'i-love-php!',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithPasswordsNotMatching()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.local',
            'password' => 'i-love-php!',
            'password_confirmation' => 'i-hate-node',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithShortPasswords()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.local',
            'password' => 'php',
            'password_confirmation' => 'php',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
