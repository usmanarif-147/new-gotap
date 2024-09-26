<?php

namespace App\Http\Livewire\Admin\Application;

use App\Mail\ApplicationApprovedMail;
use App\Mail\ApplicationRejectedMail;
use App\Models\Application;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Applications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '', $filterByStatus = '', $sortBy = '';

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

    public $total;

    public $applicationId;

    public $reason;

    protected function rules()
    {
        return [
            'reason' => ['required']
        ];
    }

    protected function messages()
    {
        return [
            'reason.required' => ['Reason is required']
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
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

    /**
     * Accept Application
     */
    public function confirmAcceptApplication($id)
    {
        $this->applicationId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You Want To Accept Application';
        $this->c_modal_btn_text = 'Accept';
        $this->c_modal_btn_color = 'btn-success';
        $this->c_modal_method = 'accept';
        $this->dispatchBrowserEvent('confirm-modal');
    }
    public function accept()
    {
        $application = Application::findOrFail($this->applicationId);
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->email,
                'enterprise_type' => $application->name,
                'role' => 'enterpriser',
                'status' => 1,
                'verified' => 1,
                'token' => Str::random(20) . '_' . Str::random(20)
            ]);

            $application->update([
                'status' => 1
            ]);

            DB::commit();

            Mail::to($user->email)
                ->send(new ApplicationApprovedMail($user->name, $user->token));

            $this->closeModal();

            $this->dispatchBrowserEvent('swal:modal', [
                'message' => 'Request Accepted. Email Sent Successfully',
                'icon' => 'success'
            ]);

            $this->emit('pendingApplications');
        } catch (Exception $ex) {
            DB::rollBack();
            $this->dispatchBrowserEvent('swal:modal', [
                'message' => $ex->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Reject Application
     */
    public function confirmRejectApplication($id)
    {
        $this->applicationId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You Want To Reject Application';
        $this->c_modal_btn_text = 'Reject';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'reasonForRejection';
        $this->dispatchBrowserEvent('confirm-modal');
    }
    public function reasonForRejection()
    {
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reason-modal');
    }
    public function reject()
    {
        $this->validate();

        $application = Application::findOrFail($this->applicationId)->update([
            'status' => 2,
            'reason' => $this->reason
        ]);

        $application = Application::findOrFail($this->applicationId);

        Mail::to($application->email)
            ->send(new ApplicationRejectedMail($application->name, $this->reason));

        $this->closeModal();

        $this->dispatchBrowserEvent('swal:modal', [
            'message' => 'Request Rejected. Email Sent Successfully',
            'icon' => 'success'
        ]);

        $this->emit('pendingApplications');
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

    public function getData()
    {
        $filteredData = Application::when($this->filterByStatus, function ($query) {
            $query->where('applications.status', $this->filterByStatus - 1);
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
                    $query->where('applications.name', 'like', "%$this->search%")
                        ->orWhere('applications.email', 'like', "%$this->search%")
                        ->orWhere('applications.phone', 'like', "%$this->search%");
                });
            })
            ->orderBy('applications.created_at', 'desc');

        return $filteredData;
    }

    public function render()
    {
        $data = $this->getData();
        $applications = $data->paginate(10);

        $this->total = $applications->total();

        return view('livewire.admin.application.applications', [
            'applications' => $applications
        ]);
    }
}
