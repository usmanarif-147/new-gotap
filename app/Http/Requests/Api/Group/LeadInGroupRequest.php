<?php

namespace App\Http\Requests\Api\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LeadInGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'lead_id' => ['required'],
            'group_id' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'lead_id.required' => 'Lead Id Is Required',
            'group_id.required' => 'Group Id Is Required',
        ];
    }
}
