<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllDinasUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $payload = $this->request->getAttribute('payload');
        try{
            $user = $this->userRepository->getAllDinasFromUser((int)$payload['id']);
            if(!$user){
                return $this->respondWithData(message: "User not found", statusCode: 404);
            }
            return $this->respondWithData($user);
        }catch(Exception $e){
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
        return $this->respondWithData(data:$user, statusCode: 200);
    }
}
