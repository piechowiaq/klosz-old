<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'=> 'sometimes|required',
            'surname'=> 'sometimes|required',
            'number'=> ['required','sometimes', Rule::unique('employees', 'number')->ignore($this->employee)],
            'company_id'=> 'exists:companies,id|required|sometimes',
        ];
    }
}
