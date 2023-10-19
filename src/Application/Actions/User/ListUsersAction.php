<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_USER', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try{
            $body = $this->request->getParsedBody();
            $users = $this->userRepository->findAll($body);
        }catch(Exception $e){
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
        return $this->respondWithData(data: $users);
    }
}
