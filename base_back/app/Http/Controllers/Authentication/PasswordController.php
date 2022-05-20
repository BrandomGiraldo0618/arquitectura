<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Library\General;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
	/**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['recoverPassword', 'changePassword']]);
    }

    public function recoverPassword(Request $request)
    {
        try
        {
            $email = $request->input('email');

            $user = \App\Models\User::where('email', $email)->first();

            if(null == $user)
            {
                return response()->json(['success' => false, 'message' => '¡USUARIO NO ENCONTRADO!'], 422);
            }

            $token = General::tokenGenerate();

            $user->remember_token = $token;
            $user->save();


            $subject = 'Comex Sumatec - Recuperar contraseña';
            $message = 'Hemos recibido su solicitud para recuperar su contraseña, puede hacerlo dando clic en el siguiente botón.';
            $urlSite = env('SITE_URL') . 'restablecer-clave/'.$token;

            $data = [
                'name' => $user->name,
                'html_message' => $message,
                'url_site' => $urlSite,
                'button_name' => 'Recuperar contraseña'
            ];

            General::sendMail($user->email, $subject, 'standardEmail', $data);

            $this->_response = ['success' => true, 'message' => 'TE HEMOS ENVIADO UN CORREO DE RECUPERACIÓN DE CONTRASEÑA'];
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    public function changePassword(Request $request){
        try
        {
            $token = $request->input('token');
            $password = $request->input('password');
            $confirmPassword = $request->input('confirm_password');

            $validator = Validator::make($request->all(),[
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:password',
                'token' => 'required'
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                return response()->json(['success' => false, 'message' => $errors->all()]);
            }

            $user = User::where('remember_token', $token)->first();

            if(null === $user)
            {
                return response()->json(['success' => false, 'message' => 'El enlace de recuperación ha expirado, solicita de nuevo la recuperación de tu contraseña.']);
            }

            $user->password = Hash::make($password);
            $user->remember_token = null;
            $user->save();

            $this->_response = ['success' =>true, 'message' => '¡HAS RECUPERADO TU CONTRASEÑA, AHORA PUEDES INICIAR SESIÓN!'];
        }
        catch(Exception $e){
            //Write error in log
            Log::error($e->getMessage() . ' line: '. $e->getLine() . ' file: '. $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }
}
