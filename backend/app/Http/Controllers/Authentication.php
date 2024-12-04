<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Dates;
use App\Utils\Notify;
use App\Security\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authentication extends Controller
{
    /**
     * Execute authentication user system
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json(Notify::warning('Acesso não autorizado!'), 401);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $user->update(['lastlogin' => $user->nowlogin, 'nowlogin' => Dates::nowPTBR()]);
            $token = $user->createToken('authToken', ['*'], now()->addHours(3));

            return response()->json([
                'notify' => ['type' => Notify::SUCCESS],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'token' => $token->plainTextToken
                ]
            ], 200);
        }

        return response()->json(Notify::warning('Usuário ou Senha Inválidos!'), 403);
    }

    public function logout(Request $request)
    {
        $tokenData = Guardian::getUserByToken($request->bearerToken());
        if ($tokenData) {
            $tokenData->tokens()->delete();
        }

        return response()->json(['message' => 'success']);
    }

    public function check(Request $request)
    {
        $token = $request->bearerToken();
        return Guardian::checkToken($token)
            ? response()->json(['token_valid' => true], 200)
            : response()->json(Notify::info('Login expirado, realize o login novamente...'), 401);
    }

    public function newuser(Request $request)
    {
        $user = User::where("email", $request->email)->first();

        if($user){
            return response()->json(Notify::warning('Já existe um usuário com o e-mail informado...'));
        }

        if ($request->password !== $request->passdoublecheck) {
            return response()->json(Notify::warning('Senhas divergentes informadas!'), 400);
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z]).{8,}$/', $request->password)) {
            return response()->json(Notify::warning('Senha não atende aos critérios de segurança!
            Sua senha deve conter no minimo 08 caracteres com letras, números e símbolos'), 400);
        }

        $user = new User($request->only(['name', 'email', 'password']));
        return $user->save() ? response()->json(Notify::success()) : response()->json(Notify::error('Falha ao cadastro usuário...'), 500);
    }
}
