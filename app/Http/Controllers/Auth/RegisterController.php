<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

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


     public function showRegistrationForm()
     {
         $countries = Country::all();
         return view('auth.register', compact('countries'));
     }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_code' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validateCaptcha($request);
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'company_name' => $data['company_name'],
            'company_website' => $data['company_website'],
            'location' => $data['location'],
            'email' => $data['email'],
            'phone_code' => $data['phone_code'],
            'phone' => $data['phone'],
            'source' => $data['source'],
            'password' => Hash::make($data['password']),
            'status' => 'active',
            'photo' => 'default.jpg',
        ]);

        $user->assignRole('Customer');

        return $user;

    }

    protected function validateCaptcha(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'required',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $remoteIp = $request->ip();
        $secretKey = config('services.recaptcha.secret');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $remoteIp,
        ]);

        $responseBody = json_decode($response->getBody());

        if (!$responseBody->success) {
            throw ValidationException::withMessages(['g-recaptcha-response' => 'Failed to validate reCAPTCHA.']);
        }
    }

}
