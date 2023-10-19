<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteRoleUserAction extends UserAction
{
    private $rule = [
        'role.id' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if(!in_array('D_USER_ROLE', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $body = $this->request->getParsedBody();
            if (!isset($body['role.id'])) {
                return $this->respondWithData(message: "Role ID is missing in the request body.", statusCode: 400);
            }
            $this->valid->validator()->make($body, $this->rule);
            $userRole = $this->userRepository->deleteRoleUser((int)$this->args['id'], (int)$body['role.id']);
            return $this->respondWithData(data: $userRole, message: "Success", statusCode: 200);
            if (!$userRole) {
                return $this->respondWithData(message: "User not found", statusCode: 404);
            }
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
