<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeamMember;

class TeamSection extends Component
{
    public $members;

    protected $listeners = ['teamUpdated' => 'loadMembers'];

    public function mount()
    {
        $this->loadMembers();
    }

    public function loadMembers()
    {
        $this->members = TeamMember::ordered()->toArray();
    }

    public function render()
    {
        return view('livewire.team-section');
    }
}
