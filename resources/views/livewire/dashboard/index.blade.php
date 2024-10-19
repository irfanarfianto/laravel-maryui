<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public function with(): array
    {
        return [
            'totalUsers' => User::count(),
        ];
    }
};
?>

<div>
    <!-- DASHBOARD SUMMARY -->
    <x-header title="Dashboard" separator progress-indicator />

    <!-- STATISTICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
    </div>
</div>
