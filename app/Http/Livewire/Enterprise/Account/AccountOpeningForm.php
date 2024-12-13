<?php

namespace App\Http\Livewire\Enterprise\Account;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountOpeningForm extends Component
{
    use WithFileUploads;

    public $message = '';

    public $name, $email, $phone, $enterprise_type, $startDate, $endDate, $file, $companyName;

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

    public function saveApplication()
    {
        $data = $this->validate();
        if ($this->file) {
            $data['file'] = Storage::disk('public')->put('/uploads/contractfiles', $this->file);
        }

        Application::create($data);

        $this->message = 'Application Submited Succesfully. You will get and Email from Gotaps Administraition Soon.';

        $this->reset(['name', 'email', 'phone', 'enterprise_type', 'companyName', 'startDate', 'endDate', 'file']);
    }

    public function render()
    {
        return view('livewire.enterprise.account.account-opening-form');
    }
}
