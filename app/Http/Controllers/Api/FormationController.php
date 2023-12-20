<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Http\Resources\FormationResource;
use App\Traits\ReturnJsonResponseTrait;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    use ReturnJsonResponseTrait;

    public function index()
    {
        return $this->returIndexJsonResponse('model:Formation', 'paginate:5');
    }

    public function show(Request $request)
    {
        return $this-> returShowJsonResponse('model:Formation', $request->user);
        
    }

    public function store(StoreFormationRequest $request) 
    {
       
        return $this-> returnJsonResponse('action:store', $request->validated(), 'model:Formation');
    }

    public function update(Request $request, UpdateFormationRequest $myRequest)
    {
        //  dd( $request->formation);
        return $this-> returnJsonResponse('action:update', $myRequest->validated(), 'model:Formation', $request->formation);
    }

    public function destroy(Request $request)
    {
        return $this-> returnJsonResponse('action:delete', null, 'model:Formation', $request->formation);
    }
}

