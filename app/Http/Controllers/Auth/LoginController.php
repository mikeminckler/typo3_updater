<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

	/** 
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
	public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->with(['hd' => 'brentwood.ca'])->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);

        if (!$authUser){
            return '<p>Error, you are not a member of Brentwood College School!!</p>';
        }

        Auth::login($authUser, true);
        return redirect('/');

    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {

        $authUser = User::where('provider_id', $user->id)->first();

        if ($authUser) {
            return $authUser;
        }

        if (strpos($user->email, '@brentwood.ca') !== false){

            return User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'provider' => $provider,
                'provider_id' => $user->id
            ]);

        } else {
            return null;
        }
        
    }

}
