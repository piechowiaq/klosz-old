<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array|string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:companies,name',
            'department_ids' => 'sometimes|array',
            'department_ids.+' => 'exists:departments,id',
            'registry_ids' => 'sometimes|array',
            'registry_ids.+' => 'exists:registries,id',
        ];
    }
}
