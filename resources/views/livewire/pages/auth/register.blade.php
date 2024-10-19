<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input label="Name" wire:model="name" placeholder="Masukkan nama" id="name" name="name" type="text"
                required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input label="Email" wire:model="email" placeholder="Masukkan email" id="email" name="email"
                type="email" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-password label="Password" wire:model="password" placeholder="Masukkan password" name="password" required
                autocomplete="new-password" right />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-password label="Confirm Password" wire:model="password_confirmation" placeholder="Masukkan password lagi"
                name="password_confirmation" required autocomplete="new-password" right />
        </div>

        <div class="flex justify-end mt-4">
            <x-button label="Register" class="w-32 btn-primary" type="submit" wire:click="register" spinner />
        </div>
    </x-form>
</div>
