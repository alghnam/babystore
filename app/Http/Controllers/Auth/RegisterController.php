<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Modules\Auth\Entities\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
      return $this->baseRepo->redirectTo();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'image|max:1024|dimensions:min_width=300,min_height=300',
            'country' => 'required|string|max:255',
            'city' => 'string|max:255',
            'town' => 'string|max:255',
            'phone_no1' => 'required|string|max:255|unique:users',
            'phone_no2' => 'string|max:255|unique:users',
            'passport_images'=>['sometimes', 'array'],
            'passport_images.*'=>['mimes:jpeg,bmp,png,gif,svg,pdf'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Modules\Auth\Entities\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'image' => $data['image'],
            'country' => $data['country'],
            'city' => $data['city'],
            'town' => $data['town'],
            'phone_no1' => $data['phone_no1'],
            'phone_no1' => $data['phone_no1']
        ]);
    }
}
