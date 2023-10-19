<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{

    private $rule = [
        'name'                  => 'required',
        'email'                 => 'required|email',
        'password'              => 'required|min:6',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if(!in_array('C_USER', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }

        try{
            $body['createAt'] = (string)date('Y-m-d H:i:s');
            $user = new User();
            $putDataUser = $user->fromArray($body);
            $insertService = $this->userRepository->createUser($putDataUser);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah user", statusCode: 201);
        }catch(Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
