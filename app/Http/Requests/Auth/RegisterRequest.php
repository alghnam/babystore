<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class RegisterRequest.
 */
class RegisterRequest extends FormRequest
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
            // 'first_name'=>['required','max:225'],
            // 'last_name'=>['required','max:225'],
            'phone_no' => ['required','unique:users'],
            'password'=>['required', Rules\Password::defaults()],
            // 'image'=>['nullable'],
            // 'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
        ];
    }
      /**
     * @return array
     */
    public function messages()
    {
        return [
        
        ];
    }

}

