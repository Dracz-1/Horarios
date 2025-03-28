<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Record;
use Carbon\Carbon;

class HorarioCrud extends Component
{
    public $users;
    public $dailyRecords = [];
    public $selectedUserId = null;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->loadUsers(); // Carregar os registos do dia atual
    }

    // Carregar utilizadores com os registos do dia atual
    public function loadUsers()
    {
        $this->users = User::with(['records' => function ($query) {
            $query->whereDate('date_time', $this->startDate)->orderBy('date_time');
        }])->get();

        $this->calculateTimeSpent();
    }



    // Função para calcular o tempo total na sala
    public function calculateTimeSpent()
    {
        foreach ($this->users as $user) {
            $totalTime = 0;
            $records = $user->records->sortBy('date_time');
    
            if ($records->isEmpty()) {
                $user->time_spent = 'Não registado';
                $user->first_entry_time = null;
                $user->last_exit_time = null;
                $user->status = 'Fora da sala';
                continue;
            }
    
            $firstEntry = $records->firstWhere('type', 'entry');
            $lastExit = $records->where('type', 'exit')->last();
            $entries = $records->where('type', 'entry');
            $exits = $records->where('type', 'exit');
    
            foreach ($entries as $entry) {
                $exit = $exits->firstWhere(function ($exit) use ($entry) {
                    return Carbon::parse($exit->date_time)->gt(Carbon::parse($entry->date_time));
                });
    
                if ($exit) {
                    $totalTime += Carbon::parse($entry->date_time)->diffInMinutes(Carbon::parse($exit->date_time));
                    $exits = $exits->forget($exits->search($exit));
                } else {
                    $totalTime += Carbon::parse($entry->date_time)->diffInMinutes(Carbon::now());
                }
            }
    
            $hours = floor($totalTime / 60);
            $minutes = $totalTime % 60;
            $user->time_spent = "{$hours}h{$minutes}min";
            $user->first_entry_time = $firstEntry ? Carbon::parse($firstEntry->date_time)->format('H:i') : null;
            $user->last_exit_time = $lastExit ? Carbon::parse($lastExit->date_time)->format('H:i') : null;
            $lastRecord = $records->last();
            $user->status = $lastRecord->type === 'entry' ? 'Na sala' : 'Fora da sala';
            
            // Adiciona a sala ao utilizador
            $user->room = $lastRecord->location ? $lastRecord->location->name : 'Não registada';
        }
    }
    

    // // Mostrar registos diários de um utilizador
    // public function showDailyRecords($userId)
    // {
    //     $this->selectedUserId = $userId;
    //     $this->dailyRecords = Record::where('user_id', $userId)
    //         ->whereBetween('date_time', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
    //         ->orderBy('date_time')
    //         ->get();
    // }



        // Mostrar registos diários de um utilizador
    public function showDailyRecords($userId)
    {
        $this->selectedUserId = $userId;
        $this->loadDailyRecords();
    }

    // Carregar os registos diários com base no intervalo de datas
    public function loadDailyRecords()
    {
        $this->dailyRecords = Record::where('user_id', $this->selectedUserId)
            ->whereBetween('date_time', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->orderBy('date_time')
            ->get();
    }

    // Função para filtrar por intervalo de datas
    public function filterByDate()
    {
        $this->loadDailyRecords();
    }


    // Fechar registos diários e voltar à tabela principal
    // Fechar registos diários e voltar à tabela principal
    public function closeDailyRecords()
    {
        $this->selectedUserId = null;
        $this->dailyRecords = [];
        $this->startDate = Carbon::now()->startOfDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->loadUsers(); // Recarregar utilizadores com os registos do dia atual
    }



    public function render()
    {
        return view('livewire.horario-crud');
    }
}