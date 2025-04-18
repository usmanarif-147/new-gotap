<?php

namespace App\Http\Livewire\Admin\Platform;

use App\Models\Category;
use App\Models\Platform;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Platforms extends Component
{

    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $platform_id, $methodType, $modalTitle, $modalBody, $modalActionBtnColor, $modalActionBtnText;

    // filter valriables
    public $searchQuery = '', $filterByStatus, $filterByCategory, $sortBy;

    public $total, $statuses = [], $categories;

    public function mount()
    {
        $this->statuses = [
            '1' => 'Active',
            '2' => 'Inactive',
        ];

        $this->categories = Category::all()->pluck('name', 'id')->toArray();
    }

    public function updatedFilterByStatus()
    {
        $this->resetPage();
    }
    public function updatedFilterByCategory()
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

        // dd(Platform::all()->toArray());

        $filteredData = Platform::select(
            'platforms.id',
            'platforms.category_id as cat_id',
            'platforms.title',
            'platforms.pro',
            'platforms.icon',
            'platforms.status',
            'platforms.baseURL',
            'categories.id as category_id',
            'categories.name as category',
        )
            ->join('categories', 'categories.id', 'platforms.category_id')
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 2) {
                    $query->where('platforms.status', 0);
                }
                if ($this->filterByStatus == 1) {
                    $query->where('platforms.status', 1);
                }
            })
            ->when($this->filterByCategory, function ($query) {
                $query->where('platforms.category_id', $this->filterByCategory);
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('platforms.created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('platforms.created_at', 'desc');
                }
            })
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($query) {
                    $query->where('platforms.title', 'like', "%$this->searchQuery%");
                });
            })
            ->orderBy('platforms.created_at', 'desc');

        return $filteredData;
    }

    public function confirmModal($id)
    {
        $this->platform_id = $id;
        $this->methodType = 'delete';
        $this->modalTitle = 'Are you sure';
        $this->modalBody = 'You want to Delete this platform!';
        $this->modalActionBtnColor = 'btn-danger';
        $this->modalActionBtnText = 'Delete';
        $this->dispatchBrowserEvent('confirmModal');
    }

    public function closeModal()
    {
        $this->platform_id = null;
        $this->methodType = null;
        $this->modalTitle = null;
        $this->modalBody = null;
        $this->modalActionBtnColor = null;
        $this->modalActionBtnText = null;
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete()
    {
        $platform = Platform::find($this->platform_id);
        if ($platform) {
            // Delete the icon from storage if it exists
            if ($platform->icon && Storage::disk('public')->exists($platform->icon)) {
                Storage::disk('public')->delete($platform->icon);
            }
            $platform->delete();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Platform Deleted successfully!',
            ]);
            $this->platform_id = null;
            $this->methodType = null;
            $this->modalTitle = null;
            $this->modalBody = null;
            $this->modalActionBtnColor = null;
            $this->modalActionBtnText = null;
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Platform not found!',
            ]);
        }
    }

    public function render()
    {
        $data = $this->getData();
        $platforms = $data->paginate(10);

        $this->total = $platforms->total();

        return view('livewire.admin.platform.platforms', [
            'platforms' => $platforms
        ]);
    }
}
