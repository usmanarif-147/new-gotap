<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Profile;
use App\Models\User;
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
        $profile = Profile::find($this->profile_id);
        $users = User::select(
            'profiles.id as connection_id',
            'profiles.name as connection_name',
            'profiles.email as connection_email',
            'profiles.username as connection_user_name',
            'profiles.job_title as connection_job_title',
            'profiles.company as connection_company',
            'profiles.photo as connection_photo',
            'profiles.phone as connection_phone',
            'profiles.created_at as connection_created_at',
        )
            ->join('connects', 'connects.connecting_id', 'users.id')
            ->join('profiles', 'profiles.id', 'connects.connected_id')
            ->where('users.id', $profile->user_id)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('profiles.username', 'like', "%$this->search%")
                        ->orWhere('profiles.email', 'like', "%$this->search%")
                        ->orWhere('profiles.phone', 'like', "%$this->search%");
                });
            })
            ->orderBy('connects.created_at', 'desc');
        //     ->get();
        // dd($users);
        // $users = DB::table('connects')->select(
        //     'users.name',
        //     'users.username',
        //     'users.email',
        //     'users.phone',
        //     'users.photo',
        //     'users.created_at'
        // )
        //     ->where('connected_id', $this->profile_id)
        //     ->leftjoin('users', 'connects.connecting_id', '=', 'users.id')
        //     ->when($this->search, function ($query) {
        //         $query->where(function ($query) {
        //             $query->where('users.name', 'like', "%$this->search%")
        //                 ->orWhere('users.email', 'like', "%$this->search%")
        //                 ->orWhere('users.phone', 'like', "%$this->search%");
        //         });
        //     })
        //     ->orderBy('connects.created_at', 'desc');
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
