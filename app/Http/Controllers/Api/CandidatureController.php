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


    /**
     * @OA\Post(
     *     path="/api/user/candidater/formation-{formationId}",
     *     summary="Submit a candidature for a formation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="formationId", type="integer", description="ID of the formation to apply for"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Candidature submitted successfully"),
     *     @OA\Response(response="403", description="You have already applied to this formation"),
     *     @OA\Response(response="404", description="Formation not found"),
     *     @OA\Response(response="422", description="Validation failed"),
     *     
     * )
     */
    public function candidater(StoreCandidatreRequest $request)
    {

        try {
            $formation = Formation::FindOrFail($request->validated('formationId'));

            $formationId = $request->validated('formationId');
            $userFormationExists = $formation->users()->where('formation_id', $formationId)->exists();

            if ($userFormationExists) {
                return response()->json(['message' => 'Vous avez déjà posutlé à cette formation'], 403);
            }

            $formation->users()->sync($request->validated());
            return response()->json(['message' => 'Candidature soumise'], 200);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    /**
     * @OA\Patch(
     *     path="/api/admin/candidater/{formationId}",
     *     summary="Accept or reject a user's candidature for a formation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="formationId", type="integer", description="ID of the formation"),
     *             @OA\Property(property="status", type="string", description="Status of the candidature (accept or reject)"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Candidature status updated successfully"),
     *     @OA\Response(response="404", description="Formation not found"),
     *     @OA\Response(response="422", description="Validation failed"),
     * )
     */
    public function accepteUser(UpdateCandidatureRequest $request)
    {
        return $this->returnJsonResponse('action:update', $request->validated(), 'model:User',   $this->notLogin());
    }
}
