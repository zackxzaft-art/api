<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Buscar el usuario por el correo
        $user = User::where('email', $datos['email'])->first();

        // Verificar que exista y que la contraseña sea correcta
        if (!$user || !Hash::check($datos['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        // Crear el token
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario ha iniciado sesión con éxito',
            'token' => $token,
            'data' => $user,
        ], 200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8']
        ], [
            'email.unique' => 'el correo del usuario que deseas iniciar ya se encuentra registrado en la base de datos'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $datosvalidados = $validator->validated();
        $user = \App\Models\User::create([
            'name' => $datosvalidados['name'],
            'email' => $datosvalidados['email'],
            'password' => bcrypt($datosvalidados['password'])
        ]);
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'usuario registrado con exito',
            'token' => $token,
            'data' => $user
        ], 201);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Cierre de sesion con exito'
        ], 200);
    }
}
