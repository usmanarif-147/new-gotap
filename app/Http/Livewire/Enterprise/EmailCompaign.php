<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\Profile;
use Livewire\Component;

class EmailCompaign extends Component
{


    public $showDropdown = false; // Control dropdown visibility
    public $recipients = []; // Array of selected profile IDs
    public $selectedNames = []; // ID => Name mapping for display
    public $selectAll = false; // Checkbox for 'All Profiles'
    public $profiles = []; // Profiles fetched from the database

    // Mount Function to Load Profiles
    public function mount()
    {
        $this->profiles = Profile::select(
            'profiles.id',
            'profiles.name',
            'profiles.email',
            'profiles.username',
            'profiles.photo',
        )
            ->where('profiles.enterprise_id', auth()->id())
            ->orderBy('profiles.created_at', 'desc')->get();
    }

    // Toggle Dropdown Visibility
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    // Update Selected Names
    public function updateInput()
    {
        $this->selectedNames = collect($this->profiles)
            ->whereIn('id', $this->recipients)
            ->pluck('name', 'id')
            ->toArray();

        // Uncheck 'Select All' if some profiles are deselected
        $this->selectAll = count($this->recipients) === count($this->profiles);
    }

    // Remove Recipient from Selected
    public function removeRecipient($id)
    {
        $this->recipients = array_diff($this->recipients, [$id]);
        $this->updateInput();
    }

    // Toggle 'Select All' Option
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->recipients = collect($this->profiles)->pluck('id')->toArray(); // Select all IDs
        } else {
            $this->recipients = []; // Deselect all
        }

        $this->updateInput();
    }




    public function render()
    {
        return view('livewire.enterprise.email-compaign');
    }
}
