<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Support\TryCatch;
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

        return TryCatch::handle(function () use ($data) {
            $user = $this->_userRepository->store($data);
            return ApiResponseClass::sendResponse(new UserResource($user), 'usuario creado exitosamente', 201);
        }, message: 'Error al crear usuarios, revise sus parametros');
    }

    public function update(array $data, $id)
    {

        return TryCatch::handle(function () use ($data, $id) {
            $user =  $this->_userRepository->update($data, $id);
            return ApiResponseClass::sendResponse(new UserResource($user), 'usuario actualizado exitosamente');
        }, message: 'Error al actualizar usuario, revise su parametros', transactional: true, name_model: 'user');
    }

    public function destroy($id)
    {
        return TryCatch::handle(
            function () use ($id) {
                $user = $this->_userRepository->destroy($id);
                return ApiResponseClass::sendResponse($user, 'usuario eliminado exitosamente', 204);
            },
            message: "No se pudo eliminar el usuario",
            transactional: false,
            name_model: "usuario",
        );
    }
}
