<?php

namespace App\Http\Livewire\Enterprise\Account;

use App\Models\Application;
use Livewire\Component;

class AccountOpeningForm extends Component
{

    public $message = '';

    public $name, $email, $phone, $enterprise_type;

    public function rules()
    {
        return [
            'name'              => ['required'],
            'email'             => ['required', 'unique:users'],
            'phone'             => ['required'],
            'enterprise_type'   => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required'              =>  'Name is required',
            'email.required'             =>  'Email is required',
            'email.unique'               =>  'Email already Exists',
            'phone.required'             =>  'Phone number is required',
            'enterprise_type.required'   =>  'Enterprise Type is required',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function saveApplication()
    {
        $data = $this->validate();

        Application::create($data);

        $this->message = 'Application Submited Succesfully. You will get and Email from Gotaps Administraition Soon.';

        $this->reset(['name', 'email', 'phone', 'enterprise_type']);
    }

    public function render()
    {
        return view('livewire.enterprise.account.account-opening-form');
    }
}
