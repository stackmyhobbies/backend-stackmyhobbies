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

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //

    }

    public function index()
    {
        $users = $this->userRepository->index();
        return $users;
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        return $user;
    }

    public function store(array $data)
    {

        return TryCatch::handle(function () use ($data) {
            $user = $this->userRepository->store($data);
            return $user;
        });
    }

    public function update(array $data, $id)
    {

        return TryCatch::handle(function () use ($data, $id) {
            $user =  $this->userRepository->update($data, $id);
            return $user;
        });
    }

    public function destroy($id)
    {
        return TryCatch::handle(
            function () use ($id) {
                $result = $this->userRepository->destroy($id);
                return $result;
            }
        );
    }
}
