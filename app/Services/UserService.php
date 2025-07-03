<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new class instance.
     */
    protected UserRepositoryInterface $_userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        //
        $this->_userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->_userRepository->index();
        return  ApiResponseClass::sendResponse(UserResource::collection($users), 'Usuarios cargados exitosamente', Response::HTTP_OK);
    }

    public function show($id)
    {
        $user = $this->_userRepository->show($id);
        return ApiResponseClass::sendResponse(new UserResource($user), 'Usuario cargado exitosamente', Response::HTTP_OK);
    }

    public function store(array $data)
    {

        try {
            $user =  $this->_userRepository->store($data);
            DB::commit();
            return ApiResponseClass::sendResponse(new UserResource($user), 'usuario creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'No se pudo crear el usuario');
        }
    }

    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $user =  $this->_userRepository->update($data, $id);
            DB::commit();
            return ApiResponseClass::sendResponse($user, 'usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'No se pudo actualizar el usuario');
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->_userRepository->destroy($id);
            return ApiResponseClass::sendResponse($user, 'usuario eliminado exitosamente', 204);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return ApiResponseClass::sendError('user not found or could not be deleted', [], 404);
            }
            return ApiResponseClass::throw($e, 'No se pudo eliminar el usuario');
        }
    }
}
