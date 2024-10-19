<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <x-form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <x-password label="Current Password" wire:model="current_password" id="update_password_current_password"
                name="current_password" right />
        </div>

        <div>
            <x-password label="New Password" wire:model="password" id="update_password_password" name="password"
                autocomplete="new-password" right />
        </div>

        <div>
            <x-password label="Confirm Password" wire:model="password_confirmation"
                id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password"
                right />
        </div>

        <div class="flex items-center gap-4">
            <x-button label="Simpan" class="btn-primary" type="submit" wire:click="updatePassword" spinner />
        </div>
    </x-form>
</section>
