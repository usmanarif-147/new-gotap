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

    public $card_id, $methodType, $modalTitle, $modalBody, $modalActionBtnColor, $modalActionBtnText;

    public $total;

    public $searchQuery = '', $filterByType, $filterByStatus;

    public $types = [], $statuses = [];

    public function mount()
    {
        $this->statuses = [
            '1' => 'Active',
            '2' => 'Inactive',
        ];
    }

    // public function exportCsv()

    // {
    //     $fileName = 'cards.csv';
    //     $cards = $this->getData()->get();

    //     $headers = [
    //         'Content-Type' => 'application/csv',
    //         'Content-Disposition' => "attachment; filename=\"$fileName\"",
    //         'Cache-Control' => 'no-store, no-cache',
    //         'Pragma' => 'no-cache',
    //     ];

    //     $output = fopen('php://output', 'w');
    //     ob_start();

    //     fputcsv($output, ['ID', 'UUID', 'Description', 'Status', 'Type', 'Username']);

    //     foreach ($cards as $card) {
    //         fputcsv($output, [
    //             $card->id,
    //             $card->uuid,
    //             $card->description,
    //             $card->status == 1 ? 'Active' : 'Inactive',
    //             $card->type,
    //             $card->username,
    //         ]);
    //     }

    //     fclose($output);
    //     $csvContent = ob_get_clean();

    //     return response($csvContent, 200, $headers);
    // }

    public function exportCsv()
    {
        $queryParams = http_build_query([
            'searchQuery' => $this->searchQuery,
            'filterByStatus' => $this->filterByStatus,
        ]);

        $this->dispatchBrowserEvent('download-csv', [
            'url' => route('cards.export') . '?' . $queryParams,
        ]);
    }

    // public function exportCsv()
    // {
    //     return redirect()->route('export')->with(
    //         [
    //             'file_name' => 'cards.csv',
    //             'data' => $this->getData()->get()
    //         ]
    //     );
    // }

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
            'cards.type',
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

    public function confirmModal($id)
    {
        $this->card_id = $id;
        $this->methodType = 'delete';
        $this->modalTitle = 'Are you sure';
        $this->modalBody = 'You want to Delete this platform!';
        $this->modalActionBtnColor = 'btn-danger';
        $this->modalActionBtnText = 'Delete';
        $this->dispatchBrowserEvent('confirmModal');
    }

    public function delete()
    {
        $card = Card::find($this->card_id);
        if ($card) {
            $card->delete();
            $this->card_id = null;
            $this->methodType = null;
            $this->modalTitle = null;
            $this->modalBody = null;
            $this->modalActionBtnColor = null;
            $this->modalActionBtnText = null;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Card deleted successfully!',
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Card not found!',
            ]);
        }
    }

    public function closeModal()
    {
        $this->card_id = null;
        $this->methodType = null;
        $this->modalTitle = null;
        $this->modalBody = null;
        $this->modalActionBtnColor = null;
        $this->modalActionBtnText = null;
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $data = $this->getData();
        $cards = $data->paginate(10);
        $this->total = $cards->total();

        return view('livewire.admin.card.cards', [
            'cards' => $cards
        ]);
    }
}
