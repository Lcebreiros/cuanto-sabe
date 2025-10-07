<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class TeamAdmin extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $editingId = null;
    public $members = [];
    
    // Campos del formulario
    public $name = '';
    public $role = '';
    public $description = '';
    public $sort_order = 0;
    public $photo;
    
    // Para el modal mejorado
    public $showModal = false;

    protected $rules = [
        'name' => 'required|min:2',
        'role' => 'required',
        'description' => 'required',
        'sort_order' => 'numeric',
        'photo' => 'nullable|image|max:2048'
    ];

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        // Cargar miembros desde tu fuente de datos
        $this->members = \App\Models\TeamMember::orderBy('sort_order')->get()->toArray();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->showModal = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $member = \App\Models\TeamMember::find($id);
        if ($member) {
            $this->editingId = $id;
            $this->name = $member->name;
            $this->role = $member->role;
            $this->description = $member->description;
            $this->sort_order = $member->sort_order;
            $this->showForm = true;
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'role' => $this->role,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $path = $this->photo->store('team-photos', 'public');
            $data['photo_url'] = Storage::url($path);
        }

        if ($this->editingId) {
            \App\Models\TeamMember::find($this->editingId)->update($data);
            session()->flash('success', 'Miembro actualizado correctamente');
        } else {
            \App\Models\TeamMember::create($data);
            session()->flash('success', 'Miembro agregado correctamente');
        }

        $this->resetForm();
        $this->loadMembers();
        $this->showModal = false;
        
        \Log::debug('RuleAdmin save auth check', [
    'auth_check' => auth()->check(),
    'user' => auth()->user()?->id,
    'can_edit_pages' => auth()->user() ? auth()->user()->can('edit pages') : null,
]);
    }

    public function delete($id)
    {
        \App\Models\TeamMember::find($id)->delete();
        session()->flash('success', 'Miembro eliminado correctamente');
        $this->loadMembers();
    }

    public function resetForm()
    {
        $this->reset(['name', 'role', 'description', 'sort_order', 'photo', 'editingId', 'showForm']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.team-admin');
    }
}