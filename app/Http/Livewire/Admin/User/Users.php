<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Users extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    // filter valriables
    public $search = '', $filterByStatus = '', $filterByRole = '',  $sortBy = '';

    public $total, $heading, $statuses = [];

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

    public function render()
    {
        $data = $this->getFilteredData();

        $this->heading = "Users";
        $users = $data->paginate(10);

        $this->total = $users->total();

        return view('livewire.admin.user.users', [
            'users' => $users
        ]);
    }
}
