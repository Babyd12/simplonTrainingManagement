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

    /**
     * @OA\Get(
     *     path="/api/admin/users-cadidature",
     *     summary="Retrieve a list of all users",
     *     @OA\Response(response="200", description="Successfully retrieved the list of users"),
     *     security={{ "bearerAuth":{} }},
     * )
     */
    public function index()
    {
        return $this->returIndexJsonResponse('model:User', 'paginate:5');
    }


    /**
     * @OA\Post(
     *     path="/api/admin/users-acepted",
     *     summary="Retrieve a list of all users accepted",
     *     @OA\Response(response="200", description="Successfully retrieved the list of users accepted"),
     *     security={{ "bearerAuth":{} }}
     * )
     */
    public function isAcepted()
    {
        return $this->returnFlexibleJsonResponse('Listes candidat accepté', User::where('status', 'apprenant')->paginate(4));
    }


    /**
     * @OA\Post(
     * path = "/api/admin/users-rejected",
     *  summary="Retrieve a list of all users rejected",
     *     @OA\Response(response="200", description="Successfully retrieved the list of users rejected"),
     *     security={{ "bearerAuth":{} }}
     * ),
     */
    public function isNotAcepted()
    {
        return $this->returnFlexibleJsonResponse('Listes candidat non accepté', User::where('status', 'candidat')->paginate(4));
    }

    /**
     * @OA\Get(
     * path = "/api/admin/users/{id}",
     *  summary="Retrieve a one users ",
     *     @OA\Response(response="200", description="Successfully a users selected"),
     *     security={{ "bearerAuth":{} }}
     * ),
     */
    public function show(Request $request)
    {
        return $this->returShowJsonResponse('model:User', $request->user);
    }


    /**
     * @OA\Post(
     *     path="/api/admin/users/store",
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's name"),
     *             @OA\Property(property="firstName", type="string", description="User's first name"),
     *             @OA\Property(property="residence", type="string", description="User's residence"),
     *             @OA\Property(property="password", type="string", description="User's password (hashed)"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email (unique)"),
     *             @OA\Property(property="profilePicture", type="string", description="URL or base64-encoded image of the user's profile picture"),
     *             @OA\Property(property="levelOfStudy", type="string", description="User's level of study"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="User created successfully"),
     *     @OA\Response(response="422", description="Validation failed"),
     *     security={{ "bearerAuth":{} }}
     * )
     */
    public function store(StoreUserRequest $request)
    {
        return $this->returnJsonResponse('action:store', $request->validated(), 'model:User');
    }


    /**
     * @OA\Patch(
     *     path="/api/admin/users/{id}",
     *     summary="Update an existing user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's name"),
     *             @OA\Property(property="firstName", type="string", description="User's first name"),
     *             @OA\Property(property="residence", type="string", description="User's residence"),
     *             @OA\Property(property="profilePicture", type="string", description="URL or base64-encoded image of the user's profile picture"),
     *             @OA\Property(property="levelOfStudy", type="string", description="User's level of study"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="User updated successfully"),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="422", description="Validation failed"),
     *     security={{ "bearerAuth":{} }}
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        return $this->returnJsonResponse('action:update', $request->validated(), 'model:User', $request->user);
    }


    // /**
    //  * @OA\Delete(
    //  *     path="/api/admin/users/{id}",
    //  *     summary="Delete a user",
    //  *     @OA\Parameter(
    //  *         name="id",
    //  *         in="path",
    //  *         required=true,
    //  *         description="ID of the user to delete",
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Response(response="200", description="User deleted successfully"),
    //  *     @OA\Response(response="401", description="Unauthorized"),
    //  *     @OA\Response(response="404", description="User not found"),
    //  *      security={{ "bearerAuth":{} }}
    //  * )
    //  */
    // public function destroy(Request $request)
    // {
    //     return $this->returnJsonResponse('action:delete', null, 'model:User', $request->user);
    // }

}
