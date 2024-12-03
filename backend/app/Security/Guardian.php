<?php
namespace App\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Guardian
{
    public static function AuthAccess(?Model $model, Request $request)
    {
        if($model === null){
            return true;
        }

        $user = $request->user();
        if($model->activateSecurity() && (!$user || !$user->tokenCan($model->modelName()))){
            return false;
        }

        return true;
    }

    public static function AuthCheck(?Model $model, Request $request):void
    {
        if(!self::AuthAccess($model, $request)){
            die();
        }
    }
}
