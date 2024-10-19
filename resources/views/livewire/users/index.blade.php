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
    public bool $addUserModal = false; // Modal for adding a new user
    public $deleteId = null;
    public $editingUser = null; // User being edited
    public $deletingUser = null;
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];
    public string $newUserName = ''; // New user name
    public string $newUserEmail = ''; // New user email
    public string $newUserPassword = ''; // New user password

    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Open edit drawer with user data
    public function editUser($id): void
    {
        $this->editingUser = User::find($id);
        if ($this->editingUser) {
            $this->newUserName = $this->editingUser->name;
            $this->newUserEmail = $this->editingUser->email;
            $this->editDrawer = true;
            $this->resetValidation();
        } else {
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

    // Save user changes or add a new user
    public function saveUser(): void
    {
        // Validation
        $this->validate([
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|unique:users,email,' . ($this->editingUser->id ?? 'NULL'),
            'newUserPassword' => $this->editingUser ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($this->editingUser) {
            // Update existing user
            $this->editingUser->name = $this->newUserName;
            $this->editingUser->email = $this->newUserEmail;
            if ($this->newUserPassword) {
                $this->editingUser->password = bcrypt($this->newUserPassword);
            }
            $this->editingUser->save();
            $this->success("User {$this->newUserName} updated.", position: 'toast-bottom');
        } else {
            // Create a new user
            User::create([
                'name' => $this->newUserName,
                'email' => $this->newUserEmail,
                'password' => bcrypt($this->newUserPassword),
            ]);
            $this->success("User {$this->newUserName} added.", position: 'toast-bottom');
        }

        // Reset input fields and close modal
        $this->resetInputFields();
        $this->addUserModal = false;
        $this->editDrawer = false;
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->newUserName = '';
        $this->newUserEmail = '';
        $this->newUserPassword = '';
        $this->editingUser = null; // Reset editing user
    }

    // Table headers
    public function headers(): array
    {
        return [['key' => 'id', 'label' => '#'], ['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'E-mail', 'sortable' => false], ['key' => 'created_at', 'label' => 'Dibuat pada']];
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

    public function openAddUserModal(): void
    {
        $this->resetInputFields();
        $this->resetValidation();
        $this->addUserModal = true;
    }

    public function getInitials($name)
    {
        $nameParts = explode(' ', $name);
        $initials = '';

        foreach ($nameParts as $part) {
            $initials .= strtoupper($part[0]);
        }

        return $initials;
    }
};
?>


<div>
    <!-- HEADER -->
    <x-header title="Users" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex items-center space-x-2">
                <x-input placeholder="Search..." wire:model.live.debounce="search"  icon="o-magnifying-glass" />
                <x-button label="Tambah User" class="btn-primary" icon="o-user-plus" wire:click="openAddUserModal"
                    spinner />
            </div>
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
                            class="btn-ghost btn-sm text-blue-500" spinner />
                        <x-button icon="o-trash" wire:click="confirmDelete({{ $user['id'] }})"
                            class="btn-ghost btn-sm text-red-500" spinner />
                    </div>
                @endscope
            </x-table>
        @endif
    </x-card>

    <!-- ADD USER MODAL -->
    <x-modal wire:model="addUserModal" title="Add New User" class="backdrop-blur" persistent>
        <div class="space-y-4">
            <x-input label="Name" placeholder="Enter name" wire:model="newUserName" />
            <x-input label="Email" placeholder="Enter email" type="email" wire:model="newUserEmail" />
            <x-input label="Password" placeholder="Enter password" type="password" wire:model="newUserPassword" right />
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <x-button label="Batal" icon="o-x-mark" class="btn-ghost" @click="$wire.addUserModal = false" />
            <x-button label="Tambah" class="btn-primary" wire:click="saveUser" spinner />
        </div>
    </x-modal>

    <!-- EDIT USER DRAWER -->
    <x-drawer wire:model="editDrawer" title="Edit User" right class="W-full lg:w-1/3">
        <div class="space-y-4">
            <x-input label="Name" placeholder="Enter name" wire:model="newUserName" />
            <x-input label="Email" placeholder="Enter email" type="email" wire:model="newUserEmail" />
            <x-input label="Password" placeholder="Leave blank to keep current password" type="password"
                wire:model="newUserPassword" />
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <x-button label="Batal" icon="o-x-mark" class="btn-ghost" wire:click="$set('editDrawer', false)" />
            <x-button label="Simpan" class="btn-primary" wire:click="saveUser" spinner />
        </div>
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
