<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'city_id' => 'nullable|integer|exists:cities,id',
            'name' => 'required|string|max:60',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'required|integer',
            'image_file' => 'nullable|image'
        ];

        if (in_array($this->method(), ['POST']))
        {
            $rules['email'] = 'required|max:100|email|unique:users';
            $rules['password'] = 'required|min:6|max:20';
            $rules['confirm_password'] = 'same:password';
        }
        else
        {
            $id = $this->route('user')->id;

            $rules['email'] = 'required|max:100|email|unique:users,email,' . $id;
            $rules['password'] = 'nullable|min:6|max:20';
            $rules['confirm_password'] = 'same:password';
        }


        return $rules;
    }
}
