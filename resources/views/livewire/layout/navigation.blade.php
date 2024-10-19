<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<x-nav class="hidden lg:block">
    <x-slot:brand>
        <x-app-brand />
    </x-slot:brand>

    <x-slot:actions>
        @php
            // Generate user initials from the name
            $userName = auth()->user()->name;
            $initials = collect(explode(' ', $userName))->map(fn($part) => strtoupper($part[0]))->take(2)->join('');
        @endphp

        <x-dropdown right>
            <x-slot:trigger>
                <x-button label="{{ $initials }}" class="btn-circle btn-outline" />
            </x-slot:trigger>

            <x-menu-item link="/profile" wire:navigate title="Profile" />
            <x-menu-item title="Keluar" icon="s-arrow-right-start-on-rectangle" wire:click="logout" class="text-error" />
        </x-dropdown>
    </x-slot:actions>
</x-nav>
