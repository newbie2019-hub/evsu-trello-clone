<?php

namespace App\Http\Controllers;

use App\Models\MemberRole;
use Illuminate\Http\Request;

class MemberRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        return response()->json(MemberRole::get(['id', 'role']));
    }
}
