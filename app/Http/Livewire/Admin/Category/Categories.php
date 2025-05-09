<?php

namespace App\Http\Livewire\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class Categories extends Component
{

    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    // filter valriables
    public $searchQuery = '', $filterByStatus, $sortBy;

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

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $filteredData = Category::select(
            'categories.id',
            'categories.name',
            'categories.name_sv',
            'categories.status',
            'categories.created_at',
            DB::raw('(SELECT COUNT(*) FROM platforms WHERE categories.id = platforms.category_id) AS total_platforms')
        )
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 2) {
                    $query->where('categories.status', 0);
                }
                if ($this->filterByStatus == 1) {
                    $query->where('categories.status', 1);
                }
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('created_at', 'desc');
                }
                if ($this->sortBy == 'platforms_asc') {
                    $query->orderBy('total_platforms', 'asc');
                }
                if ($this->sortBy == 'platforms_desc') {
                    $query->orderBy('total_platforms', 'desc');
                }
            })
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($query) {
                    $query->where('categories.name', 'like', "%$this->searchQuery%")
                        ->orWhere('categories.name_sv', 'like', "%$this->searchQuery%");
                });
            })
            ->orderBy('categories.created_at', 'desc');

        return $filteredData;
    }

    public function render()
    {

        $data = $this->getData();
        $categories = $data->paginate(10);

        $this->total = $categories->total();

        return view('livewire.admin.category.categories', [
            'categories' => $categories
        ]);
    }
}
