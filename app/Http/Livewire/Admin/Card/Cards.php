<?php

namespace App\Http\Livewire\Admin\Card;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Card;

use Illuminate\Support\Str;
use App\Traits\ExportData;

class Cards extends Component
{

    use WithPagination, ExportData;

    protected $paginationTheme = 'bootstrap';

    public $total, $heading;

    public $searchQuery = '', $filterByType, $filterByStatus;

    public $types = [], $statuses = [];

    public function mount()
    {
        $this->statuses = [
            '1' => 'Active',
            '2' => 'Inactive',
        ];
    }

    public function exportCsv()
    {
        return redirect()->route('export')->with(
            [
                'file_name' => 'cards.csv',
                'data' => $this->getData()->get()
            ]
        );
    }

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $filteredData = Card::select(
            'cards.id',
            'cards.uuid',
            'cards.description',
            'cards.status',
            'profiles.username',
        )
            ->leftJoin('profile_cards', 'profile_cards.card_id', 'cards.id')
            ->leftJoin('profiles', 'profiles.id', 'profile_cards.profile_id')
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($query) {
                    $query->where('cards.uuid', 'like', "%$this->searchQuery%")
                        ->orWhere('profiles.username', 'like', "%$this->searchQuery%");
                });
            })
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 1) {
                    $query->where('cards.status', 1);
                }
                if ($this->filterByStatus == 2) {
                    $query->where('cards.status', 0);
                }
            })
            ->orderBy('cards.created_at', 'desc');
        return $filteredData;
    }

    public function render()
    {
        $data = $this->getData();
        $this->heading = "Cards";
        $cards = $data->paginate(10);
        $this->total = $cards->total();

        return view('livewire.admin.card.cards', [
            'cards' => $cards
        ]);
    }
}
