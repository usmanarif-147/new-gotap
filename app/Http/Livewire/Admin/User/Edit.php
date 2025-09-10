<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{

    use WithFileUploads;

    public $preview_photo, $preview_enterprise_logo;

    public
    $user_id,
    $name,
    $username,
    $email,
    $role,
    $phone,
    $company_name,
    $photo,
    $enterprise_logo,
    $status,
    $address,
    $gender,
    $verified,
    $featured,
    $deactivated_at,
    $user;

    public function mount()
    {
        $this->user_id = request()->id;
        $user = User::with('profiles.cards')->findOrFail($this->user_id);
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone;
        $this->company_name = $user->company_name;
        $this->preview_photo = $user->photo;
        $this->preview_enterprise_logo = $user->enterprise_logo;
        $this->status = $user->status;
        $this->address = $user->address;
        $this->gender = $user->gender;
        $this->verified = $user->verified;
        $this->featured = $user->featured;
        $this->deactivated_at = $user->deactivated_at;
    }

    protected function rules()
    {
        return [
            'name' => ['sometimes'],
            'username' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'phone' => ['sometimes'],
            'company_name' => ['sometimes'],
            'photo' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:2000'],
            'enterprise_logo' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:2000'],
            'status' => ['required'],
            'address' => ['sometimes'],
            'gender' => ['sometimes', 'not_in:4'],
            'verified' => ['sometimes', 'not_in:-1'],
            'featured' => ['sometimes', 'not_in:'],
        ];
    }

    protected function messages()
    {
        return [
            'name.sometimes' => 'The name field is optional.',
            'username.required' => 'The username field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'phone.sometimes' => 'The phone field is optional.',
            'company_name.sometimes' => 'The company field is optional.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, jpg, png.',
            'photo.max' => 'The photo may not be greater than 2000 kilobytes.',
            'enterprise_logo.mimes' => 'The cover photo must be a file of type: jpeg, jpg, png.',
            'enterprise_logo.max' => 'The cover photo may not be greater than 2000 kilobytes.',
            'status.required' => 'The status field is required.',
            'address.sometimes' => 'The address field is optional.',
            'gender.sometimes' => 'The gender field is optional.',
            'gender.not_in' => 'The selected value for gender is invalid.',
            'verified.sometimes' => 'The verified field is optional.',
            'verified.not_in' => 'The selected value for verified is invalid.',
            'featured.sometimes' => 'The featured field is optional.',
            'featured.not_in' => 'The selected value for featured is invalid.',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function deleteImage($type, $image)
    {
        if ($image) {
            if (Storage::exists('public/' . $image)) {
                Storage::delete('public/' . $image);
            }
        }
        if ($type == 'photo') {
            $this->preview_photo = null;
            User::where('id', $this->user_id)->update([
                'photo' => null
            ]);
        }
        if ($type == 'enterprise_logo') {
            $this->preview_enterprise_logo = null;
            User::where('id', $this->user_id)->update([
                'enterprise_logo' => null
            ]);
        }
    }

    public function update()
    {
        $data = $this->validate();
        if ($this->photo) {
            if ($this->preview_photo) {
                Storage::disk('public')->delete($this->preview_photo);
            }
            $data['photo'] = Storage::disk('public')->put('uploads/photos', $this->photo);
        }
        if ($this->enterprise_logo) {
            if ($this->preview_enterprise_logo) {
                Storage::disk('public')->delete($this->preview_enterprise_logo);
            }
            $data['enterprise_logo'] = Storage::disk('public')->put('uploads/photos', $this->enterprise_logo);
        }
        User::where('id', $this->user_id)->update($data);
        $this->photo = null;
        $this->enterprise_logo = null;
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'User updated successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.user.edit');
    }
}
