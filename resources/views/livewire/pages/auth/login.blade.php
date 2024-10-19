<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input label="Email" wire:model="form.email" placeholder="Masukkan email" id="email" name="email"
                type="email" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-password label="Password" wire:model="form.password" placeholder="Masukkan password" name="password"
                required autocomplete="current-password" right />
            <div class="flex justify-between mt-4">
                <!-- Remember Me -->
                <div class="block">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm ">{{ __('Ingat saya') }}</span>
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="underline text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Lupa password?') }}
                    </a>
                @endif

            </div>
        </div>

        <div class="flex gap-3 justify-end mt-4">

            <x-button label="Login" class="btn-primary w-32" type="submit" wire:click="login" spinner />
        </div>
    </x-form>
</div>
