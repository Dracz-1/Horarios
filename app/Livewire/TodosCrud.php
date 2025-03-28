<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Record;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class TodosCrud extends Component
{
    public $users;

    public function render()
    {
        $this->users = User::select('users.id', 'users.name', 'locations.name as location_name')
            ->join('records', 'users.id', '=', 'records.user_id')
            ->join('locations', 'records.id_location', '=', 'locations.id')
            ->whereRaw('records.id = (SELECT MAX(r2.id) FROM records as r2 WHERE r2.user_id = records.user_id)')
            ->where('records.type', 'entry')
            ->get();

        return view('livewire.todos-crud');
    }
}
