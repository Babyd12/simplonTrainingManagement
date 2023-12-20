<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use App\Traits\ReturnJsonResponseTrait;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    use ReturnJsonResponseTrait;


    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function index()
    {
        return $this->returIndexJsonResponse('model:User', 'paginate:5');
    }

    public function isAcepted()
    {
        return $this -> returnFlexibleJsonResponse('Listes candidat accepté', User::where('status', 'apprenant')->paginate(4));
    }

    public function isNotAcepted()
    {
        return $this -> returnFlexibleJsonResponse('Listes candidat non accepté', User::where('status', 'candidat')->paginate(4));
    }

    public function show(Request $request)
    {
        return $this-> returShowJsonResponse('model:User', $request->user);
    }

    public function store(StoreUserRequest $request) 
    {
        return $this-> returnJsonResponse('action:store', $request->validated(), 'model:User');
    }

    public function update(UpdateUserRequest $request)
    {
        return $this-> returnJsonResponse('action:update', $request->validated(), 'model:User', $request->user);
    }

    public function destroy(Request $request)
    {
        return $this-> returnJsonResponse('action:delete', null, 'model:User', $request->user);
    }
}
