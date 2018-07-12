<?php
namespace Monica\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminsRequest extends FormRequest
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
            'name' => 'required|alpha',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
            'roles' => 'required'
        ];
    }
}
