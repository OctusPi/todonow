<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Users extends Controller
{
    public function __construct(){
        parent::__construct(new User());
    }
}
