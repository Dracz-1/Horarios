<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Card;

class UserCrud extends Component
{
    public $users, $cards;
    public $userId, $name, $email, $id_card, $role;
    public $isEdit;
    public $message = '';


    public function mount()
    {
        $this->users = User::with('card')->get();
        $this->cards = Card::all();
    }

    public function create(){

    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->id_card = $user->id_card;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:user,admin,manager',
            'id_card' => 'nullable|exists:cards,id',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'id_card' => $this->id_card,
        ]);

        session()->flash('message', 'Utilizador atualizado com sucesso!');
        $this->resetFields();
        $this->users = User::with('card')->get();
    }
    public function resetFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->id_card = '';
        $this->isEdit = false;
    }


    public function render(){
        return view('livewire.user-crud');
    }
}
