<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Card;
use App\Models\CompaignEmail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Users extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    // filter valriables
    public $search = '', $filterByStatus = '', $filterByRole = '', $sortBy = '';

    public $user_id, $methodType, $modalTitle, $modalBody, $modalActionBtnColor, $modalActionBtnText;

    public $selectedUser;
    public $showCardModal = false;

    public $total, $statuses = [];

    public function mount()
    {
        $this->statuses = [
            '1' => 'Active',
            '2' => 'Inactive',
        ];
    }

    public function updatedFilterByStatus()
    {
        $this->resetPage();
    }
    public function updatedFilterByRole()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function showCards($userId)
    {
        $this->selectedUser = User::with('profiles.cards')->find($userId);
        $this->showCardModal = true;
    }

    public function getFilteredData()
    {
        $filteredData = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.role',
            'users.status',
            'users.created_at',
            // DB::raw('(SELECT COUNT(*) FROM profile_cards WHERE profile_cards.user_id = users.id) AS total_products')
        )
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 2) {
                    $query->where('users.status', 0);
                }
                if ($this->filterByStatus == 1) {
                    $query->where('users.status', 1);
                }
            })
            ->when($this->filterByRole, function ($query) {
                $query->where('users.role', $this->filterByRole);
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('created_at', 'desc');
                }
                if ($this->sortBy == 'products_asc') {
                    $query->orderBy('total_products', 'asc');
                }
                if ($this->sortBy == 'products_desc') {
                    $query->orderBy('total_products', 'desc');
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('users.name', 'like', "%$this->search%")
                        ->orWhere('users.username', 'like', "%$this->search%")
                        ->orWhere('users.email', 'like', "%$this->search%");
                });
            })
            ->where('role', '!=', 'admin')
            ->orderBy('users.created_at', 'desc');

        return $filteredData;
    }

    public function confirmModal($id)
    {
        $this->user_id = $id;
        $this->methodType = 'delete';
        $this->modalTitle = 'Are you sure';
        $this->modalBody = 'You want to Delete this User!';
        $this->modalActionBtnColor = 'btn-danger';
        $this->modalActionBtnText = 'Delete';
        $this->dispatchBrowserEvent('confirmModal');
    }

    public function closeModal()
    {
        $this->user_id = null;
        $this->methodType = null;
        $this->modalTitle = null;
        $this->modalBody = null;
        $this->modalActionBtnColor = null;
        $this->modalActionBtnText = null;
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete()
    {
        $user = User::findOrFail($this->user_id);
        if (!$user) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'User not found!',
            ]);
            return;
        }
        $profileIds = Profile::where('user_id', $user->id)
            ->orWhere('enterprise_id', $user->id)
            ->pluck('id');
        foreach ($profileIds as $profileId) {
            $profile = Profile::find($profileId);
            $profile->subteams()->detach();
            if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }

            if ($profile->cover_photo && Storage::disk('public')->exists($profile->cover_photo)) {
                Storage::disk('public')->delete($profile->cover_photo);
            }
            $cardIds = $profile->cards()->pluck('cards.id')->toArray();
            Card::whereIn('id', $cardIds)->delete();
            DB::table('leads')->where('viewing_id', $profileId)->delete();
            $profile->delete();
        }
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        if ($user->role === "user") {
            DB::table('leads')->where('employee_id', $user->id)->delete();
        } else {
            DB::table('leads')->where('enterprise_id', $user->id)->delete();
            CompaignEmail::where('enterprise_id', $user->id)->delete();
        }

        DB::table('subteams')
            ->where('enterprise_id', $user->id)
            ->delete();
        $user->delete();
        $this->user_id = null;
        $this->methodType = null;
        $this->modalTitle = null;
        $this->modalBody = null;
        $this->modalActionBtnColor = null;
        $this->modalActionBtnText = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'User Deleted successfully!',
        ]);
    }

    public function render()
    {
        $data = $this->getFilteredData();

        $users = $data->paginate(10);

        $this->total = $users->total();

        return view('livewire.admin.user.users', [
            'users' => $users
        ]);
    }
}
