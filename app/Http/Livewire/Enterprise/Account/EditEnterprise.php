<?php

namespace App\Http\Livewire\Enterprise\Account;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EditEnterprise extends Component
{
    use WithFileUploads;
    public $old_enterprise_logo;

    public
    $name,
    $email,
    $phone,
    $company_name,
    $enterprise_logo;

    public function mount()
    {
        $enterpriser = auth()->user();
        $this->name = $enterpriser->name;
        $this->email = $enterpriser->email;
        $this->phone = $enterpriser->phone;
        $this->company_name = $enterpriser->company_name;

        if ($enterpriser->enterprise_logo) {
            $this->old_enterprise_logo = $enterpriser->enterprise_logo;
        }
    }
    protected function rules()
    {
        return [
            'name' => ['required', 'min:5', 'max:15'],
            'email' => ['required'],
            'phone' => ['required', 'min:5', 'max:15'],
            'company_name' => ['required', 'min:3', 'max:20'],
            'enterprise_logo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'The name must be at least 5 characters long.',
            'name.max' => 'The name must not exceed 15 characters.',

            'email.required' => 'Email is required',

            'phone.min' => 'The phone number must be at least 5 characters long.',
            'phone.max' => 'The phone number must not exceed 15 characters.',

            'company_name.min' => 'The work position must be at least 3 characters long.',
            'company_name.max' => 'The work position must not exceed 20 characters.',
            'company_name.required' => 'Enterprise Type is required',

            'enterprise_logo.mimes' => 'The profile photo must be a file of type: jpg, jpeg, png, or webp.',
            'enterprise_logo.max' => 'The profile photo must not exceed 4MB.',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateEnterpriser()
    {
        $data = $this->validate();
        $data['enterprise_logo'] = $this->old_enterprise_logo;

        if ($this->enterprise_logo) {
            if ($this->old_enterprise_logo) {
                Storage::disk('public')->delete($this->old_enterprise_logo);
            }
            $data['enterprise_logo'] = Storage::disk('public')->put('uploads/photos', $this->enterprise_logo);
        }

        User::where('id', auth()->id())->update($data);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Enterpriser updated successfully!',
        ]);
        // $this->emit('refresh-profile', $this->profile_id);
    }

    public function render()
    {
        return view('livewire.enterprise.account.edit-enterprise');
    }
}
