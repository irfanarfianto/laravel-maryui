<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;

new class extends Component {
    use Toast, WithPagination;

    public string $search = '';
    public bool $editDrawer = false;
    public bool $confirmDeleteModal = false;
    public $deleteId = null;
    public $editingUser = null;
    public $deletingUser = null; // Menyimpan pengguna yang akan dihapus
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Open edit drawer with user data
    public function editUser($id): void
    {
        $this->editingUser = User::find($id);
        $this->editDrawer = true;

        // Debugging
        if (!$this->editingUser) {
            $this->warning('User not found.', position: 'toast-bottom');
        }
    }

    // Open delete confirmation modal
    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        $this->deletingUser = User::find($id);
        $this->confirmDeleteModal = true;
    }

    // Delete action
    public function delete(): void
    {
        if ($this->deleteId) {
            $user = User::find($this->deleteId);
            $user->delete();
            $this->success("Menghapus {$user->name}", 'Berhasil.', position: 'toast-bottom');
        }

        $this->confirmDeleteModal = false;
        $this->deleteId = null;
        $this->deletingUser = null;
    }

    // Save user changes
    public function saveUser(): void
    {
        if ($this->editingUser) {
            $this->editingUser->save();
            $this->success("User #{$this->editingUser->id} updated.", position: 'toast-bottom');
        } else {
            $this->warning('No user is being edited.', position: 'toast-bottom');
        }

        $this->editDrawer = false;
        $this->editingUser = null;
    }

    // Table headers
    public function headers(): array
    {
        return [['key' => 'id', 'label' => '#', 'class' => 'w-1'], ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'], ['key' => 'email', 'label' => 'E-mail', 'sortable' => false], ['key' => 'created_at', 'label' => 'Dibuat pada']];
    }

    // Fetch users from database using Eloquent with pagination
    public function users()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate(20);
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
};
?>

<div>
    <!-- HEADER -->
    <x-header title="Users" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        @if ($users->isEmpty())
            <div class="text-center py-4">
                <p>No users found.</p>
            </div>
        @else
            <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination>
                @scope('actions', $user)
                    <div class="flex space-x-2">
                        <x-button icon="o-pencil" wire:click="editUser({{ $user['id'] }})"
                            class="btn-ghost btn-sm text-blue-500" />
                        <x-button icon="o-trash" wire:click="confirmDelete({{ $user['id'] }})"
                            class="btn-ghost btn-sm text-red-500" />
                    </div>
                @endscope
            </x-table>
        @endif
    </x-card>

    <!-- EDIT DRAWER -->
    <x-drawer wire:model="editDrawer" title="Edit User" right separator with-close-button class="lg:w-1/3">
        <div>
            @if ($editingUser)
                <div class="flex flex-col gap-3">
                    <x-input label="Name" placeholder="Name" wire:model="editingUser.name" />
                    <x-input label="Email" placeholder="Email" wire:model="editingUser.email" type="email" />
                </div>
            @else
                <p>No user selected.</p>
            @endif
        </div>
        <x-slot:actions>
            <x-button label="Save" icon="o-check" wire:click="saveUser" spinner />
            <x-button label="Cancel" icon="o-x-mark" wire:click="$set('editDrawer', false)" />
        </x-slot:actions>
    </x-drawer>


    <!-- DELETE CONFIRMATION MODAL -->
    <x-modal wire:model="confirmDeleteModal" class="backdrop-blur" title="Hapus data users?"
        subtitle="Data yang dihapus tidak dapat dikembalikan.">
        <div class="mb-5">
            @if ($deletingUser)
                <p>Anda yakin ingin menghapus item ini:</p>
                <p class="font-bold">{{ $deletingUser->name }}</p>
            @else
                <p>Tidak ada pengguna yang dipilih untuk dihapus.</p>
            @endif
        </div>
        <div class="flex justify-end gap-2">
            <x-button label="Batal" @click="$wire.confirmDeleteModal = false" />
            <x-button label="Hapus" class="btn-error ml-2" wire:click="delete" spinner />
        </div>
    </x-modal>
</div>
