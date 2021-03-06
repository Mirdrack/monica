<?php
namespace Monica\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbilitiesRequest extends FormRequest
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
            'name' => 'required|alpha_dash',
            'title' => 'required|regex:/^[ a-zA-Z0-9]+$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'A title is required.',
            'title.regex'  => 'The title may only contain letters, numbers, and spaces.',
        ];
    }
}
