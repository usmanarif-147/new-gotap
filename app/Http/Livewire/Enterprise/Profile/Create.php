<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $heading;

    public
        $name,
        $email,
        $username,
        $work_position,
        $job_title,
        $company,
        $address,
        $bio,
        $phone,
        $photo,
        $cover_photo,
        $active,
        $user_direct,
        $tiks,
        $private;

    protected function rules()
    {
        return [
            'name'              => ['nullable', 'min:5', 'max:15'],
            'email'             => ['nullable', 'email'],
            'phone'             => ['nullable', 'min:5', 'max:15'],
            'username'          => ['required', 'min:3', 'max:20', 'regex:/^[A-Za-z][A-Za-z0-9_.]{5,25}$/', Rule::unique(Profile::class)],
            'work_position'     => ['nullable', 'min:3', 'max:20'],
            'job_title'         => ['nullable', 'string'],
            'company'           => ['nullable', 'string'],
            'address'           => ['nullable'],
            'bio'               => ['nullable'],
            'cover_photo'       => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photo'             => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function messages()
    {
        return [
            'name.min' => 'The name must be at least 5 characters long.',
            'name.max' => 'The name must not exceed 15 characters.',

            'email.email' => 'Please provide a valid email address.',

            'phone.min' => 'The phone number must be at least 5 characters long.',
            'phone.max' => 'The phone number must not exceed 15 characters.',

            'username.required' => 'The username is required.',
            'username.min' => 'The username must be at least 3 characters long.',
            'username.max' => 'The username must not exceed 20 characters.',
            'username.regex' => 'The username must start with a letter and can contain letters, numbers, underscores, and periods.',
            'username.unique' => 'This username is already taken. Please choose another one.',

            'work_position.min' => 'The work position must be at least 3 characters long.',
            'work_position.max' => 'The work position must not exceed 20 characters.',

            'job_title.string' => 'The job title must be a valid string.',

            'company.string' => 'The company name must be a valid string.',

            'cover_photo.mimes' => 'The cover photo must be a file of type: jpg, jpeg, png, or webp.',
            'cover_photo.max' => 'The cover photo must not exceed 4MB.',

            'photo.mimes' => 'The profile photo must be a file of type: jpg, jpeg, png, or webp.',
            'photo.max' => 'The profile photo must not exceed 4MB.',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function saveProfile()
    {
        $data = $this->validate();

        $data['enterprise_id'] = auth()->id();
        $data['type'] = 'enterprise';

        if ($this->photo) {
            $data['photo'] = Storage::disk('public')->put('/uploads/photos', $this->photo);
        }
        if ($this->cover_photo) {
            $data['cover_photo'] = Storage::disk('public')->put('/uploads/coverPhotos', $this->cover_photo);
        }

        Profile::create($data);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Profile created successfully!',
        ]);
    }

    public function render()
    {
        $this->heading = 'Create';
        return view('livewire.enterprise.profile.create');
    }
}
