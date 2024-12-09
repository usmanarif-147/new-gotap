<?php

namespace App\Http\Livewire\Admin\Enterpriser;

use Livewire\Component;
use App\Models\User;
use Exception;
use App\Mail\ApplicationApprovedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name;
    public $email;
    public $phone;
    public $enterprise_type;
    public $message;

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'phone' => ['required', 'unique:users',],
            'enterprise_type' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Account Already Exists',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'Phone number already exist',
            'enterprise_type.required' => 'Enterprise Type is required',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function registerEnterpriser()
    {
        $data = $this->validate();
        $data['status'] = 1;
        $data['verified'] = 1;
        $data['role'] = 'enterprise';
        $data['token'] = Str::random(20) . '_' . Str::random(20);
        try {
            DB::beginTransaction();
            $user = User::create($data);

            DB::commit();

            Mail::to($user->email)
                ->send(new ApplicationApprovedMail($user->name, $user->token));
            $this->dispatchBrowserEvent('swal:modal', [
                'message' => 'Enterpriser Created. Email Sent Successfully',
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
    public function render()
    {
        return view('livewire.admin.enterpriser.create');
    }
}
