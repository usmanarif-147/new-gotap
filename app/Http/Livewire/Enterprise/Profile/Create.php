<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Mail\EnterpriserUserWelcomeMail;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $heading, $userId;

    public $maxProfiles;

    public $showSubscriptionModal = false;

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
    $private,
    $password;

    public function mount()
    {
        $this->userId = auth()->id();
        $user = User::with('userSubscription')->find($this->userId);
        $this->maxProfiles = $this->getProfileLimitBasedOnSubscription($user);
        $currentProfileCount = Profile::where('enterprise_id', $user->id)->count();

        if ($currentProfileCount >= $this->maxProfiles) {
            $this->showSubscriptionModal = true;
            return;
        }

        // if ($this->user->userSubscription && Carbon::parse($this->user->userSubscription->end_date)->lt(now())) {
        //     $this->showSubscriptionModal = true;
        //     return;
        // }

        // $this->maxProfiles = $this->getProfileLimitBasedOnSubscription($this->user);
        // $currentProfileCount = Profile::where('enterprise_id', $this->user->id)->count();
        // // dd($currentProfileCount);
        // if ($currentProfileCount >= $this->maxProfiles) {
        //     $this->showSubscriptionModal = true;
        //     return;
        // }
    }

    // private function getProfileLimitBasedOnSubscription($user)
    // {
    //     switch ($user->userSubscription->enterprise_type) {
    //         case '1':
    //             return 6;
    //         case '2':
    //             return 20;
    //         case '3':
    //             return PHP_INT_MAX;
    //         default:
    //             return 0;
    //     }
    // }

    private function getProfileLimitBasedOnSubscription($user)
    {
        $subscription = $user->userSubscription;

        if (!$subscription)
            return 0;

        switch ($subscription->enterprise_type) {
            case '1':
                return 6;
            case '2':
                return 20;
            case '3':
                return PHP_INT_MAX;
            default:
                return 0;
        }
    }


    protected function rules()
    {
        return [
            'name' => ['nullable', 'min:5', 'max:15'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'min:5', 'max:15'],
            'username' => ['required', 'min:3', 'max:20', 'regex:/^[A-Za-z][A-Za-z0-9_.]{5,25}$/', Rule::unique(Profile::class)],
            'work_position' => ['nullable', 'min:3', 'max:20'],
            'job_title' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'address' => ['nullable'],
            'bio' => ['nullable'],
            'cover_photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function messages()
    {
        return [
            'name.min' => 'The name must be at least 5 characters long.',
            'name.max' => 'The name must not exceed 15 characters.',
            'email.required' => 'The Email Address is required.',
            'email.email' => 'Please provide a valid email address.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters.',

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

        $currentProfileCount = Profile::where('enterprise_id', $this->user->id)->count();
        if ($currentProfileCount >= $this->maxProfiles) {
            $this->showSubscriptionModal = true;
            return;
        }

        $data['enterprise_id'] = auth()->id();
        $data['type'] = 'enterprise';

        if ($this->photo) {
            $data['photo'] = Storage::disk('public')->put('/uploads/photos', $this->photo);
        }
        if ($this->cover_photo) {
            $data['cover_photo'] = Storage::disk('public')->put('/uploads/coverPhotos', $this->cover_photo);
        }
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $data['user_id'] = $user->id;

            Profile::create($data);
            DB::commit();
            $this->reset([
                'name',
                'email',
                'phone',
                'username',
                'work_position',
                'job_title',
                'company',
                'address',
                'bio',
                'photo',
                'cover_photo',
                'password',
            ]);
            Mail::to($user->email)->send(new EnterpriserUserWelcomeMail($user, $data['password']));
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'User and Related Profile created successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'There was an error creating the user and profile. Please try again.',
            ]);
        }
    }

    public function render()
    {
        $this->heading = 'Create';
        return view('livewire.enterprise.profile.create');
    }
}
