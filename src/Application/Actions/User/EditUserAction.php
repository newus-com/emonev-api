<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class EditUserAction extends UserAction
{
    private $rule = [
        'name' => 'required',
        'email' => 'required|email',
    ];

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_USER', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401); 
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if($validation->fails()){
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }
        try{
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $user = new User();
            $putDataUser = $user->fromArray($body);
            $userEdit = $this->userRepository->updateUser((int)$this->args['id'], $putDataUser);
            return $this->respondWithData(data: $userEdit, message:"Success", statusCode: 200);
        }catch(Exception $e){
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
