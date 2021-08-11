<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Socialite;
use Exception;
use Auth;
use Jumbojett\OpenIDConnectClient;
class ProviderAuthController extends Controller
{
    /**
     * Redirect to keycloak server.
     * @provider
     * @return
     */
    private     $provider = 'keycloak';
    public function redirectToProvider()
    {
        /* where $provider = 'keycloak' */
        return Socialite::driver($this->provider)
            ->scopes(['openid','email']) // Array ex : name
            ->redirect();
    }

    /**
     * retrieve user information which is located at keycloak serve.
     * @provider
     * @return
     */
    public function callbackFunction(Request $request)
    {
        /* where $provider = 'keycloak' */
        $oauthUser = Socialite::driver($this->provider)
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->user();
        $finduser = User::where('email', $oauthUser->email)->first();

        if($finduser){

            Auth::login($finduser);

            return redirect()->intended('dashboard');

        }else{
            $newUser = User::create([
                'name' => $oauthUser->name,
                'email' => $oauthUser->email,
                //'google_id'=> $oauthUser->id,
                'password' => encrypt('admin@123')
            ]);

            Auth::login($newUser);
            return redirect('/dashboard');
            return redirect()->intended('affiliate');
        }

        /* Note : */
        /* 1) Callback url is same for login and logout request. so this function executed twice. */
        /* 2) Must add below code, Because user data not retrieved while logout calls is requested. */
        if(!isset($userData->email)){
            return redirect()->back();
        }

        /* your logic for add or get user detail */

    }
    /**
     * Log the user out of the application.
     * @provider
     * @return void
     */
    public function providerLogout()
    {
        /* where $provider = 'keycloak' */
        /* logout from laravel auth */
        Auth::logout();
        /* redirect to keycloak logout url */
        $kc_logout_url = env('KEYCLOAK_AUTHSERVERURL')."/realms/".env('KEYCLOAK_REALM')."/protocol/openid-connect/logout?redirect_uri=".env('APP_URL');
//        return redirect(
//            Socialite::driver($this->provider)
//                ->getLogoutUrl()
//        );
        return redirect($kc_logout_url);
    }
}
