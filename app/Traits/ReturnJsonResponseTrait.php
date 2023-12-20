<?php

namespace App\Traits;

use Exception;
use App\Models\Student;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait ReturnJsonResponseTrait
{

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function returnFlexibleJsonResponse($statusMessage, $data, $EloquentQuery = null, $anotherQuery = null)
    {
        try {
            $statusCode = 200;

            return response()->json([
                'statusCode' => $statusCode,
                'statusMessage' => $statusMessage,
                'data' => $data,
            ], $statusCode);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }


    /**
     * @param Request $request->validated()
     * @param EloquentModel 'give_the_model_name' 'Exemple: User'
     * @return Response JsonResponse
     * @throws \Exception 
     * @throws \ModelNotFoundException
     */
    public function returUpdateJsonResponse($id, $requestValidated, $EloquentModel)
    {
        try {
           
            $modelInstance = 'App\Models\\' . $EloquentModel;
            $finderModel = $modelInstance::findOrFail($id);
            $finderModel->update($requestValidated);

            return response()->json([
                'statusCode' => 200,
                'statusMessage' => 'Mise à jour réussi',
                'data' => $finderModel,
            ], 200);

        } catch (ModelNotFoundException $error) {
            return response()->json([
                'message' => $error->getMessage(),
                'code' => $error->getCode(), // Include the exception code
                'line' => $error->getLine(), // Include the line number where the exception occurred
                // 'file' => $error->getFile(), // Include the file where the exception occurred
                // 'trace' => $error->getTrace(), // Include the stack trace
            ], 404);
        } catch (Exception $error) {
            return response()->json([
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'line' => $error->getLine(),
                // 'file' => $error->getFile(),
                // 'trace' => $error->getTrace(),
            ], 403);
        }
    }
    
    public function returShowJsonResponse($modelName, $id)
    {
        try {
            $model = str_replace('model:', '', $modelName);
            $modelInstance = 'App\Models\\' . $model;
            $finderModel = $modelInstance::findOrFail($id);

            return response()->json([
                'statusCode' => 200,
                'statusMessage' => 'Enregistrement trouvé',
                'data' => $finderModel,
            ], 200);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }

    public function returDeleteJsonResponse($model, $id)
    {
        try {
            $modelInstance = 'App\Models\\' . $model;
            $finderModel = $modelInstance::findOrFail($id);
            $finderModel->delete($finderModel);

            return response()->json([
                'statusCode' => 200,
                'statusMessage' => 'Enregistrement supprimé',
                'data' => $finderModel,
            ], 200);

            $finderModel->delete();
        } catch (ModelNotFoundException $error) {
            return $this->returnNotFoundJsonResponse();
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }


    /**
     * @param Request $request->validated()
     * @param guard 'your_guard_name'
     * @return Response JsonResponse
     * @throws \Exception 
     * @throws \ModelNotFoundException
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function returLoginJsonResponse($requestValidated, $guard)
    {
        try {
            $guard = str_replace('guard:', '', $guard);
            $token = $token = auth()->guard($guard)->attempt($requestValidated);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Information de connexin incorrect',
                ], 401);
            }
            return $this->respondWithToken($token);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request->validated()
     * @param EloquentModel 'give_the_model_name' 'Exemple: User'
     * @return Response JsonResponse
     * @throws \Exception 
     * @throws \ModelNotFoundException
     */
    public function returnSuccesJsonResponse($requestValidated, $EloquentModel)
    {
        try {

            $modelInstance = 'App\Models\\' . $EloquentModel;

            return response()->json([
                'statusCode' => 200,
                'statusMessage' => 'Enregistrement réussi',
                'data' =>  $modelInstance::create($requestValidated),
            ], 200);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }


    /**
     * @param requestValidated 'The request was validated'
     * @param ModelName 'give_the_model_name' 'Exemple: User'
     * @return Response JsonResponse
     * @throws \Exception 
     * @throws \ModelNotFoundException
     */
    public function returnJsonResponse($action, $requestValidated, $ModelName,   $id = null)
    {
        try {
            // preg_match('/^model:(\w+)/i', $modelName, $matches);
            // $model = isset($matches[1]) ? $matches[1] : null;

            $action = str_replace('action:', '', $action);
            $Model = str_replace('model:', '', $ModelName);
            $statusCode = 200;

            if ($action == 'store') {

                $modelInstance = 'App\Models\\' . $Model;
                $data = $modelInstance::create($requestValidated);
                $statusMessage = 'Enregistrement réussi';
            } else if ($action == 'update') {

                return $this->returUpdateJsonResponse($id, $requestValidated, $Model);
                // $statusMessage = 'Enregistrement mise à jour avec succes';

            } else if ($action == 'delete') {

                return $this->returDeleteJsonResponse($Model, $id);
                // $statusMessage = 'Enregistrement supprimer';

            } else if ($action == 'index') {
                return $this->returIndexJsonResponse($Model, $data = null);
            } else if ($action == 'show') {

                return $this->returShowJsonResponse($id, $Model);
            } else if ($action == 'pivaut') {
                //return $this->returnStoreWithPivautJsonResponse($model, $id, $relation, $sync);
            } else {
                return $this->returnNotFoundJsonResponse();
            }

            return response()->json([
                'statusCode' => $statusCode,
                'statusMessage' => $statusMessage,
                'data' => $data,
            ], $statusCode);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }


    public function returIndexJsonResponse($model, $paginate)
    {
        try {
            $model = str_replace('model:', '', $model);
            $paginate = str_replace('paginate:', '', $paginate);

            $modelInstance = 'App\Models\\' . $model;
            $data = $modelInstance::Paginate($paginate);
            $statusCode = 200;

            return response()->json([
                'statusCode' => $statusCode,
                'statusMessage' => 'succes',
                'data' => $data,
            ], $statusCode);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }



    public function returnNotFoundJsonResponse($message = null, $statusCode = 404)
    {
        if ($message == null) {
            $message = 'Enregistrement';
        }
        return response()->json([
            'message' => ucfirst($message) . ' introuvable',
            'statusCode' => $statusCode,
        ], $statusCode);
    }

    public function returnAuthorizationJsonResponse($statusCode = 403)
    {
        return response()->json([
            'message' => 'VOUS NAVEZ PAS LAUTORISATION REQUISE POUR EFFECTUER CETTE ACTION',
            'statusCode' => $statusCode,
        ], $statusCode);
    }



    public function returnStoreWithPivautJsonResponse($model, $id,  $relation)
    {
        try {
            $model = str_replace('model:', '', $model);
            $relation = str_replace('relation:', '', $relation);
            $relation .= '()';

            if (Auth::guard('api')->user() === null) {
                return $this->notLogin();
            }


            $modelInstance = 'App\Models\\' . $model;
            $data = $modelInstance::FindOrFail($id);
            // $data .= '->'.$relation;
            dd($data);
            if (method_exists($data, $relation) && is_a($data, 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
                //  $data->sync($data);
            } else {
                // Handle the case where the relationship is not defined or not a many-to-many relationship
                return response()->json(['error' => 'Type de relation invalide entre les models.'], 400);
            }
            // $data->$relation->sync(Auth::guard('api')->user()->id);
            $statusCode = 200;

            return response()->json([
                'statusCode' => $statusCode,
                'statusMessage' => 'succes',
                'data' => $data,
            ], $statusCode);
        } catch (ModelNotFoundException $error) {
            return response()->json(['message' => 'La formation selectionné n\'existe pas'], 404);
        } catch (Exception $error) {
            return response()->json(['message' => $error->getMessage()], 403);
        }
    }

    public function notLogin()
    {
        if (!auth()->user()) {
            return $this->returnAuthorizationJsonResponse();
        }
        return  Auth::guard('api')->user()->id;
    }
}
