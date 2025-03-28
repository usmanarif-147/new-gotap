<?php

namespace App\Http\Livewire\Admin\Enterpriser;

use App\Mail\SubscriptionsMail;
use Livewire\Component;
use App\Models\User;
use Exception;
use App\Mail\ApplicationApprovedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    // filter valriables
    public $search = '', $filterByStatus = '', $sortBy = '';

    public $total, $statuses = [];
    public $name, $email, $phone, $enterprise_type, $startDate, $endDate, $file, $companyName;

    public $subStartDate, $subEndDate, $subType, $subDescription, $subFile, $enterpriserId;

    public function mount()
    {
        $this->statuses = [
            '1' => 'Active',
            '2' => 'Expired',
        ];
        // $user = auth()->user();
        // $sub = $user->userSubscription;
        // $this->subStartDate = $sub->start_date;
        // $this->subEndDate = $sub->end_date;
        // $this->subType = $sub->enterprise_type;
        // $this->subDescription = $sub->description;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'companyName' => ['required'],
            'email' => ['required', 'unique:users'],
            'phone' => ['required', 'unique:users'],
            'enterprise_type' => ['required'],
            'startDate' => ['required'],
            'endDate' => ['required'],
            'file' => ['required', 'mimes:pdf', 'max:4096']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'companyName.required' => 'Company Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Your account Already Exists',
            'phone.required' => 'Phone number is required',
            'enterprise_type.required' => 'Enterprise Type is required',
            'startDate.required' => 'Start Date is required',
            'endDate.required' => 'End Date is required',
            'file.required' => 'Pdf File is required',
            'file.mimes' => 'Upload Only Pdf File',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function registerEnterpriser()
    {
        $data = $this->validate();
        if ($this->file) {
            $data['file'] = Storage::disk('public')->put('/uploads/contractfiles', $this->file);
        }
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company_name' => $data['company_name'],
                'role' => 'enterpriser',
                'status' => 1,
                'verified' => 1,
                'token' => Str::random(20) . '_' . Str::random(20)
            ]);

            $sub = UserSubscription::create([
                'enterprise_id' => $user->id,
                'enterprise_type' => $data['enterprise_type'],
                'file' => $data['file'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            DB::commit();

            Mail::to($user->email)
                ->send(new ApplicationApprovedMail($user->name, $user->token));

            $this->closeModal();

            $this->dispatchBrowserEvent('swal:modal', [
                'message' => 'EnterPriser Create Successfully! Email Send.',
                'type' => 'success'
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            $this->dispatchBrowserEvent('swal:modal', [
                'message' => $ex->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function editSubscription($id)
    {
        $this->enterpriserId = $id;
        $this->dispatchBrowserEvent('showEditModal');
    }

    public function updateSubscription()
    {
        $data = $this->validate([
            'subStartDate' => 'required',
            'subEndDate' => 'required',
            'subType' => 'required',
            'subDescription' => 'required',
            'subFile' => 'required|mimes:pdf|max:4096',
        ], [
            'subType.required' => 'Enterprise Type is required',
            'subStartDate.required' => 'Start Date is required',
            'subEndDate.required' => 'End Date is required',
            'subDescription.required' => 'Description is required',
            'subFile.required' => 'Pdf File is required',
            'subFile.mimes' => 'Upload Only Pdf File',
        ]);
        if ($this->subFile) {
            $data['subFile'] = Storage::disk('public')->put('/uploads/contractfiles', $this->subFile);
        }
        $user = User::find($this->enterpriserId);
        $subscription = $user->userSubscription;
        if ($subscription) {
            $existingFilePath = $subscription->file;
            if ($existingFilePath && Storage::disk('public')->exists($existingFilePath)) {
                Storage::disk('public')->delete($existingFilePath);
            }
            $sub = UserSubscription::find($subscription->id);
            $sub->update([
                'start_date' => $data['subStartDate'],
                'end_date' => $data['subEndDate'],
                'enterprise_type' => $data['subType'],
                'description' => $data['subDescription'],
                'file' => $data['subFile'],
            ]);
            Mail::to($user->email)->send(new SubscriptionsMail($user->name, $sub));
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'message' => 'EnterPriser Subscription Plan Updated! Email Send.',
                'type' => 'success'
            ]);
        } else {
            $sub = $user->userSubscription()->create([
                'start_date' => $data['subStartDate'],
                'end_date' => $data['subEndDate'],
                'enterprise_type' => $data['subType'],
                'description' => $data['subDescription'],
                'file' => $data['subFile'],
            ]);
            Mail::to($user->email)->send(new SubscriptionsMail($user->name, $sub));
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'message' => 'EnterPriser Subscription Plan Created! Email Send.',
                'type' => 'success'
            ]);
        }
        $this->subStartDate = '';
        $this->subEndDate = '';
        $this->subType = '';
        $this->subDescription = '';
        $this->subFile = '';
    }

    public function updatedFilterByStatus()
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
        )
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 2) {
                    $query->whereHas('userSubscription', function ($subQuery) {
                        $subQuery->where('end_date', '<', now());
                    });
                }
                if ($this->filterByStatus == 1) {
                    $query->whereHas('userSubscription', function ($subQuery) {
                        $subQuery->where('end_date', '>=', now());
                    });
                }
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('created_at', 'desc');
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('users.name', 'like', "%$this->search%")
                        ->orWhere('users.username', 'like', "%$this->search%")
                        ->orWhere('users.email', 'like', "%$this->search%");
                });
            })
            ->where('role', '=', 'enterpriser')
            ->orderBy('users.created_at', 'desc');

        return $filteredData;
    }
    public function render()
    {
        $data = $this->getFilteredData();

        $users = $data->paginate(6);

        $this->total = $users->total();

        return view('livewire.admin.enterpriser.create', [
            'users' => $users
        ]);
    }
}
