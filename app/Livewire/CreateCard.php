<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Card;

class CreateCard extends Component
{
    public $uid;

    protected $rules = [
        'uid' => 'required|digits:12|unique:cards,uid',
    ];

    public function save()
    {
        $this->validate();

        Card::create(['uid' => $this->uid]);

        session()->flash('success', 'Cartão criado com sucesso!');
        $this->reset('uid');
    }

    public function render()
    {
        return view('livewire.create-card');
    }
}
