<?php

namespace App\Livewire\Admin;

use App\Models\Organizer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizerManager extends Component
{
    use WithPagination;

    public $showModal = false;

    public $isEditing = false;

    public $editingOrganizerId;

    // Form fields
    public $name;

    public $email;

    public $password;

    public $company_name;

    public $role = 'organizer';

    public $status = 'active';

    public $search = '';

    public $filterStatus = 'all';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'company_name' => 'required|string|max:255',
        'role' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createOrganizer()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function editOrganizer($id)
    {
        $this->resetForm();
        $organizer = Organizer::with('user')->findOrFail($id);
        $this->editingOrganizerId = $organizer->id;
        $this->name = $organizer->user->name;
        $this->email = $organizer->user->email;
        $this->company_name = $organizer->company_name;
        $this->role = $organizer->user->role->value;
        $this->status = $organizer->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveOrganizer()
    {
        $validationRules = $this->rules;
        if (! $this->isEditing) {
            $validationRules['email'] .= '|unique:users,email';
            $validationRules['password'] = 'required|min:8';
        } else {
            $organizer = Organizer::findOrFail($this->editingOrganizerId);
            $validationRules['email'] .= '|unique:users,email,'.$organizer->user_id;
        }

        $this->validate($validationRules);

        if ($this->isEditing) {
            $organizer = Organizer::findOrFail($this->editingOrganizerId);
            $organizer->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            if ($this->password) {
                $organizer->user->update(['password' => Hash::make($this->password)]);
            }

            $organizer->update([
                'company_name' => $this->company_name,
                'status' => $this->status,
            ]);

            session()->flash('success', 'Organizer updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);

            Organizer::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'slug' => Str::slug($this->company_name),
                'status' => 'active',
            ]);

            session()->flash('success', 'Organizer created successfully.');
        }

        $this->showModal = false;
    }

    public function deleteOrganizer($id)
    {
        $organizer = Organizer::findOrFail($id);
        $organizer->delete();
        session()->flash('success', 'Organizer deleted (soft deleted).');
    }

    public function restoreOrganizer($id)
    {
        $organizer = Organizer::onlyTrashed()->findOrFail($id);
        $organizer->restore();
        session()->flash('success', 'Organizer restored successfully.');
    }

    public function changeRole($userId, $newRole)
    {
        $user = User::findOrFail($userId);
        $user->role = $newRole;
        $user->save();
        session()->flash('success', "Role updated to {$newRole}.");
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->company_name = '';
        $this->role = 'organizer';
        $this->status = 'active';
        $this->editingOrganizerId = null;
    }

    public function render()
    {
        $query = Organizer::with('user')->withTrashed();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', function ($uq) {
                        $uq->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if ($this->filterStatus === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($this->filterStatus === 'deleted') {
            $query->onlyTrashed();
        }

        return view('livewire.admin.organizer-manager', [
            'organizers' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
