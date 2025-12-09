<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendWelcome;
use App\Models\UserRelation;
use App\Rules\ValidRecaptcha;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $sitekey = env('GOOGLE_RECAPTCHA_KEY');

        return view('auth.register', compact('sitekey'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $rules = [
            'firstname' => 'required|string|max:32',
            'lastname' => 'required|string|max:32',
            'email' => 'required|string|email|max:85|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => ['required', new ValidRecaptcha()],
            'addchild' => 'nullable'
        ];

        if (!empty($data['addchild'])) {
            $rules['childfirstname'] = 'required|string|max:32';
            $rules['childlastname'] = 'required|string|max:32';
            $rules['childemail'] = 'nullable|string|email|max:85|unique:users,email';
        }

        return Validator::make($data, $rules);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $banned = [
            'http',
            'weight',
            'www'
        ];

        foreach ($banned as $word) {
            if (stripos($data['firstname'], $word) !== false) {
                abort(404);
            }
            else if (stripos($data['lastname'], $word) !== false) {
                abort(404);
            }
        }



        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'lastipaddress' => \Request::ip(),
            'roleid' => 4,
            'username' => strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $data['firstname'].$data['lastname'])) . rand(1,1440)
        ]);

        if (!empty($data['addchild'])) {

            $child = [
                'firstname' => ($data['childfirstname'] ?? ''),
                'lastname' => ($data['childlastname'] ?? ''),
                'email' => (($data['childemail'] ?? '') != $data['email'] ? $data['childemail'] : ''),
            ];

            $userid = $this->createBasicUser($child, $user->userid);

            if (empty($userid)) {
                return back()->with('failure', 'Please try again later');
            }

            $userrelation = new UserRelation();
            $userrelation->userid = $user->userid;
            $userrelation->relationid = $userid;
            $userrelation->authorised = 1;
            $userrelation->hash = $this->createHash();

            $userrelation->save();

        }

        return $user;
    }


    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        SendWelcome::dispatch($user->email, $user->firstname);
    }


}
