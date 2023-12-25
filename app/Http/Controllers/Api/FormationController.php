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

    /**
     * @OA\Get(
     * path ="/api/admin/formation",
     *  summary="Retrieve a list of all formatmions",
     *     @OA\Response(response="200", description="Successfully retrieved the list of formatmions"),
     *     security={{ "bearerAuth":{} }}
     * ),
     */
    public function index()
    {
        return $this->returIndexJsonResponse('model:Formation', 'paginate:5');
    }

    /**
     * @OA\Get(
     * path = "/api/admin/formation/{id}",
     *  summary="Retrieve a one formation ",
     *  @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the formation you want show",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Successfully a formation selected"),
     *     security={{ "bearerAuth":{} }}
     * ),
     */
    public function show(Request $request)
    {
        return $this->returShowJsonResponse('model:Formation', $request->user);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/formation",
     *     summary="Create a new formation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="startDate", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="endDate", type="string", format="date", example="2023-12-31"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Formation created successfully"),
     *     @OA\Response(response="422", description="Validation failed"),
     *     security={{ "bearerAuth":{} }}
     *     
     * )
     */
    public function store(StoreFormationRequest $request)
    {
        return $this->returnJsonResponse('action:store', $request->validated(), 'model:Formation');
    }

    /**
     * @OA\Put(
     *     path="/api/admin/formation/{id}",
     *     summary="Update a formation by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the formation to be updated",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="startDate", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="endDate", type="string", format="date", example="2023-12-31"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Formation updated successfully"),
     *     @OA\Response(response="404", description="Formation not found"),
     *     @OA\Response(response="422", description="Validation failed"),
     *   security={{ "bearerAuth":{} }}
     *     
     * )
     */
    public function update(Request $request, UpdateFormationRequest $myRequest)
    {
        return $this->returnJsonResponse('action:update', $myRequest->validated(), 'model:Formation', $request->formation);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/formation/{id}",
     *     summary="Delete a formation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the formation to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="formation deleted successfully"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="404", description="formation not found"),
     *   security={{ "bearerAuth":{} }}
     * )
     */
    public function destroy(Request $request)
    {
        return $this->returnJsonResponse('action:delete', null, 'model:Formation', $request->formation);
    }
}
