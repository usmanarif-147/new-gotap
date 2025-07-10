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

    public $userId;
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
    $password;

    public function mount()
    {
        $this->userId = auth()->id();

        $user = User::with('userSubscription')->find($this->userId);

        if (!$user || !$user->userSubscription) {
            $this->showSubscriptionModal = true;
            $this->maxProfiles = 0;
            return;
        }

        $this->maxProfiles = $this->getProfileLimitBasedOnSubscription($user);

        $currentProfileCount = Profile::where('enterprise_id', $user->id)->count();
        if ($currentProfileCount >= $this->maxProfiles) {
            $this->showSubscriptionModal = true;
        }
    }

    private function getProfileLimitBasedOnSubscription($user)
    {
        $subscription = $user->userSubscription;

        if (!$subscription)
            return 0;

        return match ($subscription->enterprise_type) {
            '1' => 6,
            '2' => 20,
            '3' => PHP_INT_MAX,
            default => 0,
        };
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

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function saveProfile()
    {
        $this->validate();

        $user = User::with('userSubscription')->find($this->userId);

        if (!$user || !$user->userSubscription) {
            $this->showSubscriptionModal = true;
            return;
        }

        $currentProfileCount = Profile::where('enterprise_id', $user->id)->count();
        if ($currentProfileCount >= $this->maxProfiles) {
            $this->showSubscriptionModal = true;
            return;
        }

        $data = [
            'enterprise_id' => $user->id,
            'type' => 'enterprise',
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'work_position' => $this->work_position,
            'job_title' => $this->job_title,
            'company' => $this->company,
            'address' => $this->address,
            'bio' => $this->bio,
            'phone' => $this->phone,
        ];

        if ($this->photo) {
            $data['photo'] = Storage::disk('public')->put('/uploads/photos', $this->photo);
        }

        if ($this->cover_photo) {
            $data['cover_photo'] = Storage::disk('public')->put('/uploads/coverPhotos', $this->cover_photo);
        }

        DB::beginTransaction();

        try {
            $createdUser = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($this->password),
            ]);

            $data['user_id'] = $createdUser->id;

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

            Mail::to($createdUser->email)->send(new EnterpriserUserWelcomeMail($createdUser, $this->password));

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
        return view('livewire.enterprise.profile.create');
    }
}
