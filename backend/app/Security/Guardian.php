<?php
namespace App\Security;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class Guardian
{
    public static function AuthAccess(?Model $model, Request $request)
    {
        if ($model === null) {
            return true;
        }

        $user = $request->user();
        if ($model->activateSecurity() && (!$user || !$user->tokenCan($model->modelName()))) {
            return false;
        }

        return true;
    }

    public static function AuthCheck(?Model $model, Request $request): void
    {
        if (!self::AuthAccess($model, $request)) {
            die();
        }
    }

    public static function checkToken(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        $tokenData = PersonalAccessToken::findToken($token);

        if (!$tokenData) {
            return false;
        }

        if ($tokenData->expires_at && now()->greaterThan($tokenData->expires_at)) {
            return false;
        }

        return true;
    }

    public static function getPersonalToken(string $token): ?PersonalAccessToken
    {
        if (!$token) {
            return null;
        }

        return PersonalAccessToken::findToken($token);
    }

    public static function getUserByToken(string $token): ?User
    {
        $dataToken = self::getPersonalToken($token);

        if (!$dataToken) {
            return null;
        }

        return User::find($dataToken->tokenable_id);
    }
}
