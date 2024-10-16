<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\subteams as EnterpriseSubTeams;

class SubTeams extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $subteamData;

    public $total;
    public $logo;
    public $name;
    public $description;

    public $subTeamId;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

    // Rules for validation
    protected $rules = [
        'name' => ['required', 'min:3', 'max:20'],
        'description' => ['nullable', 'min:5', 'max:255'],
        'logo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
    ];

    protected function messages()
    {
        return [
            'name.min' => 'The name must be at least 3 characters long.',
            'name.max' => 'The name must not exceed 20 characters.',
            'name.required' => 'The name is required',

            'description.min' => 'The description must be at least 5 characters long.',
            'description.max' => 'The name must not exceed 255 characters.',

            'logo.mimes' => 'The logo must be a file of type: jpg, jpeg, png, or webp.',
            'logo.max' => 'The logo must not exceed 4MB.',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }
    public function save()
    {
        $data = $this->validate();
        if ($this->logo) {
            $data['logo'] = Storage::disk('public')->put('/uploads/photos', $this->logo);
        }

        EnterpriseSubTeams::create($data);
        $this->reset(['logo', 'name', 'description']);
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'SubTeam created successfully!',
        ]);
    }

    public function editModal($id)
    {
        $this->subTeamId = $id;
        $this->loadSubteam();
        $this->dispatchBrowserEvent('showEditModal');
    }

    public function updateSubteam()
    {
        $data = $this->validate();
        if ($this->logo) {
            $data['logo'] = Storage::disk('public')->put('/uploads/photos', $this->logo);
        }
        $subteam = EnterpriseSubTeams::find($this->subTeamId);
        $subteam->name = $data['name'];
        $subteam->description = $data['description'];
        $subteam->logo = $data['logo'];
        $subteam->save();
        $this->reset(['logo', 'name', 'description']);
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'SubTeam updated successfully!',
        ]);
    }
    public function loadSubteam()
    {
        $this->subteamData = EnterpriseSubTeams::find($this->subTeamId);
        $this->name = $this->subteamData->name;
        $this->description = $this->subteamData->description;
        $this->logo = $this->subteamData->logo;
    }

    public function confirmModal($id)
    {
        $this->subTeamId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to delete this Sub Team!';
        $this->c_modal_btn_text = 'Delete';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'delete';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function closeModal()
    {
        $this->c_modal_heading = '';
        $this->c_modal_body = '';
        $this->c_modal_btn_text = '';
        $this->c_modal_btn_color = '';
        $this->c_modal_method = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete()
    {
        $subteam = EnterpriseSubTeams::find($this->subTeamId);
        if ($subteam->logo) {
            Storage::disk('public')->delete($subteam->logo);
        }
        $subteam->delete();
        $this->closeModal();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'SubTeam deleted successfully!',
        ]);
    }

    public function getData()
    {
        $filteredData = DB::table('subteams')->select(
            'subteams.id',
            'subteams.name',
            'subteams.description',
            'subteams.logo',
        )
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('subteams.name', 'like', "%$this->search%");
                });
            })
            ->orderBy('subteams.created_at', 'desc');
        return $filteredData;
    }
    public function render()
    {
        $data = $this->getData();
        $subteams = $data->paginate(10);
        $this->total = $subteams->total();
        return view('livewire.enterprise.profile.sub-teams', [
            'subteams' => $subteams,
        ]);
    }
}
