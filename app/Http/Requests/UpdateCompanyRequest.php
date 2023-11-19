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
            'company_name' => 'required|max:200',
            'company_registration_number' => 'required|max:20|regex:/^\d{6}-\d{4}$/|unique:companies,company_registration_number,'.$this->company->company_id.',company_id',
            'company_foundation_date' => 'required|date',
            'country' => 'required|max:50',
            'zip_code' => 'required|max:20',
            'city' => 'required|max:50',
            'street_address' => 'required|max:100',
            'latitude' => 'required|numeric|max:100|between:-90,90',
            'longitude' => 'required|numeric|max:100|between:-180,180',
            'company_owner' => 'required|max:100',
            'employees' => 'required|max:100',
            'activity' => 'required|max:100',
            'active' => 'required|boolean',
            'email' => 'required|email|max:100|unique:companies,email,'.$this->company->company_id.',company_id',
            'password' => 'required|max:100',
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
