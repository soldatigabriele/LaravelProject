<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\TempUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Laravel\Socialite\Facades\Socialite as Socialite;
use PropertyStream\Facades\TW;
use Debugbar;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    // redirects to socialite for the login
    public function google()
    {
	// redirects the user to the auth provider (Google)
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {	

	// retrieve Google info using Socialite
        try {
            $google = Socialite::driver('google')->stateless()->user();
	    // dd($google);
        } catch (Exception $e) {
            return redirect('/');
        }
	// gets the user's data from the database
        $user = User::where('email', $google->getEmail())->first();
        $tw_id = NULL;
        if ($user) {
            $email = $user->email;
            $usersList = TW::getUserId();
            foreach ($usersList as $us) {
                if ($us['email'] === $email) {
                    $tw_id = $us['id'];
                }
            }
//            checks if user's info are stored in the db
            $check = User::where('google_id', $google->getId())->first();
            if (!$check) {
		// it means it is the first login => retriving info
                $name = ($google->user['name']['givenName']);
                $surname = ($google->user['name']['familyName']);
                $user->name = $name;
                $user->surname = $surname;
                $user->teamwork_id = $tw_id;
                $user->google_id = $google->getId();
                $user->profile_pic = $google->getAvatar();
                $user->password = bcrypt($google->getId());
                $user->save();
            }
        } else {
            $email = $google->getEmail();
            $usersList = TW::getUserId();
            foreach ($usersList as $us) {
                if ($us['email'] === $email) {
                    $tw_id = $us['id'];
                }
            }
            $name = ($google->user['name']['givenName']);
            $surname = ($google->user['name']['familyName']);
            $user = User::create([
                'email' => $google->getEmail(),
                'name' => $name,
                'surname' => $surname,
                'teamwork_id' => $tw_id,
                'google_id' => $google->getId(),
                'profile_pic' => $google->getAvatar(),
                'password' => bcrypt($google->getId()),
            ]);
        }
        auth()->login($user);
	// dd($google);
        return redirect('/');
        // return redirect()->intended('/');

    }

}
