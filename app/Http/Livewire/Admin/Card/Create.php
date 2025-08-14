<?php

namespace App\Http\Livewire\Admin\Card;

use App\Models\Card;
use Livewire\Component;

use Illuminate\Support\Str;


class Create extends Component
{

    public $description, $quantity, $type;

    public $types = [
        'sticker' => 'Sticker',
        'keychain' => 'Keychain',
        'ebadge' => 'E-Badge',
        'card' => 'Card',
        'band' => 'Band',
        'case' => 'Case',
        'ring' => 'Ring',
        'animal' => 'Animal',
        'standard' => 'Standard',
    ];


    protected function rules()
    {
        return [
            'description' => 'sometimes',
            'quantity' => 'numeric|required|min:1',
            'type' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'description.sometimes' => 'sometimes',
            'quantity.required' => 'Quantity is required',
            'quantity.numeric' => 'Quantity should be numeric',
            'quantity.min' => 'Quantity should be at least 1',
            'type.required' => 'Type is required',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function store()
    {
        $this->validate();

        for ($i = 0; $i < $this->quantity; $i++) {

            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            Card::create([
                'uuid' => Str::uuid(),
                'description' => $this->description,
                'type' => $this->type,
                'status' => 0,
            ]);
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Card created successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.card.create');
    }
}
