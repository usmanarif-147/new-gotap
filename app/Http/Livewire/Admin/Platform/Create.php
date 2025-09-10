<?php

namespace App\Http\Livewire\Admin\Platform;

use App\Models\Category;
use App\Models\Platform;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $categories;

    public
    $title,
    $icon,
    $pro,
    $category_id,
    $status,
    $placeholder_en,
    $placeholder_sv,
    $description_en,
    $description_sv,
    $baseURL,
    $input;

    protected function rules()
    {
        return [
            'title' => ['required'],
            'icon' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:2000'],
            'pro' => ['required', 'not_in:'],
            'category_id' => ['required', 'not_in:'],
            'status' => ['required', 'not_in:'],
            'placeholder_en' => ['sometimes'],
            'placeholder_sv' => ['sometimes'],
            'description_en' => ['sometimes'],
            'description_sv' => ['sometimes'],
            'baseURL' => ['sometimes'],
            'input' => ['required', 'not_in:'],
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'required',
            'icon.nullable' => 'nullable',
            'icon.mimes' => 'mimes:jpeg,jpg,png,webp',
            'icon.max' => 'max:2000',
            'pro.required' => 'required',
            'pro.not_in' => 'Invalid selection for pro',
            'category_id.required' => 'required',
            'category_id.not_in' => 'Invalid selection for category_id',
            'status.required' => 'required',
            'status.not_in' => 'Invalid selection for status',
            'placeholder_en.sometimes' => 'sometimes',
            'placeholder_sv.sometimes' => 'sometimes',
            'description_en.sometimes' => 'sometimes',
            'description_sv.sometimes' => 'sometimes',
            'baseURL.sometimes' => 'sometimes',
            'input.required' => 'required',
            'input.not_in' => 'Invalid selection for input',
        ];
    }


    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function mount()
    {
        $this->categories = Category::all()->pluck('name', 'id')->toArray();
    }

    public function store()
    {
        $data = $this->validate();

        if ($this->icon) {
            $data['icon'] = Storage::disk('public')->put('/uploads/platforms', $this->icon);
        }

        Platform::create($data);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Platform created successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.platform.create');
    }
}
