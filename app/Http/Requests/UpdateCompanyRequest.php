<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'max:200',
            'company_registration_number' => 'max:20|regex:/^\d{6}-\d{4}$/|unique:companies,company_registration_number,'.$this->company->company_id.',company_id',
            'company_foundation_date' => 'date',
            'country' => 'max:50',
            'zip_code' => 'max:20',
            'city' => 'max:50',
            'street_address' => 'max:100',
            'latitude' => 'numeric|max:100|between:-90,90',
            'longitude' => 'numeric|max:100|between:-180,180',
            'company_owner' => 'max:100',
            'employees' => 'max:100',
            'activity' => 'max:100',
            'active' => 'boolean',
            'email' => 'email|max:100|unique:companies,email,'.$this->company->company_id.',company_id',
            'password' => 'max:100',
        ];
    }

    public function messages()
    {
        return [
            'latitude.between' => 'The latitude must be in range between -90 and 90',
            'longitude.between' => 'The longitude mus be in range between -180 and 180',
        ];
    }
}
