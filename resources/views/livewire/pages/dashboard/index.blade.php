<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $recentUsers = [];
    public $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function mount()
    {
        $this->loadRecentUsers();
    }

    public function loadRecentUsers()
    {
        $this->recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();
    }

    public function with(): array
    {
        return [
            'totalUsers' => User::count(),
            'recentUsers' => $this->recentUsers,
            'headers' => $this->headers(),
        ];
    }

    // Menyediakan header untuk tabel
    public function headers(): array
    {
        return [['key' => 'id', 'label' => '#', 'sortable' => false], ['key' => 'name', 'label' => 'Name', 'sortable' => false], ['key' => 'email', 'label' => 'E-mail', 'sortable' => false], ['key' => 'created_at', 'label' => 'Dibuat pada', 'sortable' => false]];
    }
};

?>

<div>
    <!-- DASHBOARD SUMMARY -->
    <x-header title="Dashboard" separator />

    <!-- STATISTICS -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
        <x-stat title="Total Users" value="{{ $totalUsers }}" icon="o-users" tooltip="Total Users" />
    </div>

    <!-- RECENT USERS -->
    <div class="mt-6 space-y-3">
        <h3 class="text-lg font-semibold">Pengguna Terbaru</h3>
        <x-card>
            <x-table :headers="$headers" :rows="$recentUsers" :sort-by="$sortBy">
            </x-table>
        </x-card>
    </div>
</div>
