<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileConnects extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $profile_id;
    public $total;
    public $tab_change;

    public $search = '';

    public function mount($id, $tab)
    {
        $this->profile_id = $id;
        $this->tab_change = $tab;
    }

    public function getProfileConnects()
    {
        $users = DB::table('connects')->select(
            'users.name',
            'users.username',
            'users.email',
            'users.phone',
            'users.photo',
            'users.created_at'
        )
            ->where('connected_id', $this->profile_id)
            ->leftjoin('users', 'connects.connecting_id', '=', 'users.id')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('users.name', 'like', "%$this->search%")
                        ->orWhere('users.email', 'like', "%$this->search%")
                        ->orWhere('users.phone', 'like', "%$this->search%");
                });
            })
            ->orderBy('connects.created_at', 'desc');
        return $users;
    }
    public function render()
    {
        $data = $this->getProfileConnects();
        $users = $data->paginate(10);
        $this->total = $users->total();
        return view('livewire.enterprise.profile.profile-connects', [
            'users' => $users,
        ]);
    }
}
