<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Exceptions\MyqlQueryException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    
    public function __construct(
        private User $userModel,
        private MyqlQueryException $mysqlExceptions
    )
    {
        
    }

    public function login( Request $request ) 
    {   
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $userByEmail = $this->userModel->where('email', $request->input('email'))->firstOrFail();

        if ( Hash::check($request->input('password'), $userByEmail->password) ) {        
            Auth::login($userByEmail);
            return response()->json([
                'token' => $request->user()->createToken($request->user()->email)->plainTextToken,
                'message' => 'Sesión iniciada'
            ]);
        }

        return response()->json([
            'message' => 'Error de usuario o contraseña'
        ], 422);
    }

    public function register( Request $request ) 
    {
        /**
         * Valida si los datos enviados cumplen con las reglas
         */

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'name' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar el registro de usuario',
                'errors' => $validator->errors()
            ], 422);
        }

        /**
         * Fin
         */
        
        try {
            $newUser = $this->userModel->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                // 'password' => Hash::make($request->input('password')),
                'password' => Hash::make($request->input('password')),
                // 'email_verified_at' => Carbon::now(),
            ]);
        } catch ( QueryException $th) {
            // throw $th;
            return response()->json([
                'message' => 'Error al procesar el registro de usuario',
                'error' => $this->mysqlExceptions->getByCode($th->errorInfo[1])
            ], 422);
        }

        
        Auth::login($newUser);
        return response()->json([
            'token' => $request->user()->createToken($request->user()->email)->plainTextToken,
            'message' => 'Usuario registrado',
            'user' => $newUser
        ]);

    }
}
