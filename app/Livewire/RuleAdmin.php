<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rule;

class RuleAdmin extends Component
{
    public $rules = [];
    public $showModal = false;
    public $editingId = null;

    public $title;
    public $content;
    public $sort_order = 0;
    public $active = true;

    protected $listeners = ['refreshRules' => 'loadRules'];

    protected function rulesValidation()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadRules();
    }

    public function loadRules()
    {
        $this->rules = Rule::ordered()->get()->toArray();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $rule = Rule::findOrFail($id);
        $this->editingId = $rule->id;
        $this->title = $rule->title;
        $this->content = $rule->content;
        $this->sort_order = $rule->sort_order;
        $this->active = (bool)$rule->active;
        $this->showModal = true;
    }

public function save()
{
    $this->validate($this->rulesValidation());

    $data = [
        'title' => $this->title,
        'content' => $this->content,
        'sort_order' => $this->sort_order ?? 0,
        'active' => $this->active ?? false,
    ];

    if ($this->editingId) {
        $rule = Rule::findOrFail($this->editingId);
        $rule->update($data);
    } else {
        Rule::create($data);
    }

    $this->resetForm();
    $this->loadRules();

    // Livewire 3: reemplazamos emit
    $this->dispatch('rulesUpdated');

    session()->flash('success', 'Regla guardada correctamente.');
}

public function delete($id)
{
    Rule::findOrFail($id)->delete();
    $this->loadRules();

    // Livewire 3
    $this->dispatch('rulesUpdated');

    session()->flash('success', 'Regla eliminada.');
}



    public function resetForm()
    {
        $this->editingId = null;
        $this->title = $this->content = null;
        $this->sort_order = 0;
        $this->active = true;
        $this->showModal = false;
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.rule-admin');
    }
}
