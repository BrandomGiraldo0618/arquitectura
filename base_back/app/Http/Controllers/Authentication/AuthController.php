<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $token = '';

        $credentials = ['email' => $email, 'password' => $password];

        if (! $token = $this->guard()->attempt($credentials))
        {
            if(null == $credentials['email'])
            {
                return response()->json(['success' => false, 'message' => trans('auth.empty_email')]);
            }

            if(null == $credentials['password'])
            {
                return response()->json(['success' => false, 'message' => trans('auth.empty_password')]);
            }

            return response()->json(['success' => false, 'message' => 'Usuario y/o contraseña incorrectos' /*trans('auth.unauthorized')*/], self::STATUS_OK);
        }

        $user = User::where('email', $email)
                    ->with('roles')
                    ->first();

        if(!$user->active)
        {
            return response()->json(['success' => false, 'message' => trans('auth.inactive_user')], self::STATUS_OK);
        }

        date_default_timezone_set('America/Bogota');
        $now = time(); //Actual Time

        $user->last_date_connection = date("Y-m-d H:i:s", $now);
        $user->save();

        $user->url_image = $user->getImage();

        \App::setLocale($user->language);

        if(4 == $user->role_id)
        {
            $companyUser  = CompanyUser::where('user_id' , $user->id)->first();

            if(null != $companyUser )
            {
                $user->company = Company::find($companyUser->company_id);
            }
        }
        return response()->json(['success' => true, 'user' => $user, 'token' => $token]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => trans('auth.logged_out')]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
