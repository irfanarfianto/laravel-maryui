<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    public bool $myModal2 = false;

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium ">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm ">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>


    <x-button label="Delete Account" class="btn-ghost text-error" @click="$wire.myModal2 = true" />
    <x-modal wire:model="myModal2" title="Delete your account?" subtitle="Are you sure you want to delete your account?">
        <div>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your
            password to confirm you would like to permanently delete your account.</div>
        <div class="mt-6">
            <x-password label="Masukkan Password" wire:model="password" id="password" name="password" right />
        </div>

        <x-slot:actions>
            <x-button label="Batal" class="btn-ghost" @click="$wire.myModal2 = false" />
            <x-button label="Delete Account" icon="o-trash" class="btn-error" spinner />
        </x-slot:actions>
    </x-modal>

</section>
