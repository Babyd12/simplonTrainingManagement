<?php

namespace App\Http\Controllers\Api;



use App\Models\User;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ReturnJsonResponseTrait;
use App\Http\Requests\CandidatureRequest;
use App\Http\Requests\StoreCandidatreRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidatureController extends Controller
{
    use ReturnJsonResponseTrait;

    public function __construct()
    {
         $this->middleware('auth:api', ['except' => ['login', 'register']]); 
    }


    public function candidater(StoreCandidatreRequest $request)
    {
      
        try {
            $formation = Formation::FindOrFail($request->validated('formationId') );
         
            $formationId = $request->validated('formationId');
            $userFormationExists = $formation->users()->where('formation_id', $formationId )->exists();

            if($userFormationExists)
            {
                return response()->json(['message' => 'Vous avez déjà posutlé à cette formation'], 403);
            }

            $formation->users()->sync($request->validated());
            return response()->json(['message' => 'Candidature soumise'], 200);

        }catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        }catch (\Exception $e){
            return response()->json($e->getMessage());
        } 

    }

    public function accepteUser(UpdateCandidatureRequest $request)
    {
        // $user  = User::Find($this->notLogin());
        // $user->update($request->validated());
        return $this -> returnJsonResponse('action:update', $request->validated(), 'model:User',   $this->notLogin());
    }
}
