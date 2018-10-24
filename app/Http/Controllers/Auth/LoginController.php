<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use App\User;
use Carbon\Carbon;
use App\GoogleAPI;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        $this->provider = env('OAUTH_PROVIDER');
    }

	/** 
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
	public function redirectToProvider()
    {
        return Socialite::driver($this->provider)->with(['hd' => 'brentwood.ca'])->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {

        // https://developers.google.com/admin-sdk/directory/v1/quickstart/php

		try {
			$user = Socialite::driver($this->provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        // only allow people with @company.com to login
        if(explode("@", $user->email)[1] !== 'brentwood.ca'){
            return redirect()->route('home')->with(['error' => 'You must login with your brentwood.ca email']);
        }

        // check if they're an existing user
        $existing_user = User::where('email', $user->email)->first();

        if($existing_user instanceof User){
            // log them in
            auth()->login($existing_user, true);
        } else {
            // create a new user
            $new_user = new User;
            $new_user->name = $user->name;
            $new_user->email = $user->email;
            $new_user->google_id = $user->id;
            $new_user->avatar = $user->avatar;
            $new_user->avatar_original = $user->avatar_original;
            $new_user->email_verified_at = Carbon::now();
            $new_user->save();

            auth()->login($new_user, true);
        }

        $google_api = app()->make(GoogleAPI::class);
        $google_api->setSessionUserGroups();

        return redirect()->route('home');

    }


}
