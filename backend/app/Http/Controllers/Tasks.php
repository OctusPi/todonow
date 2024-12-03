<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class Tasks extends Controller
{
    public function __construct()
    {
        parent::__construct(new Task());
    }
}
