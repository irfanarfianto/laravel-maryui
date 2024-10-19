<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;
};

?>

<div>
    <!-- DASHBOARD SUMMARY -->
    <x-header title="Profile" separator />

    <div>
        <x-tabs wire:model="users-tab">
            <x-tab name="users-tab" label="Update User" icon="o-users">
                <livewire:pages.profile.update-profile-information-form />
            </x-tab>
            <x-tab name="tricks-tab" label="Update Password" icon="o-lock-closed">
                <livewire:pages.profile.update-password-form />
            </x-tab>
            <x-tab name="musics-tab" label="Hapus akun" icon="o-trash">
                <livewire:pages.profile.delete-user-form />
            </x-tab>
        </x-tabs>
    </div>
</div>
