<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rule;

class RulesSection extends Component
{
    public $rules = [];

    protected $listeners = ['rulesUpdated' => 'loadRules'];

    public function mount()
    {
        $this->loadRules();
    }

    public function loadRules()
    {
        $this->rules = Rule::where('active', true)->orderBy('sort_order')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.rules-section');
    }
}
