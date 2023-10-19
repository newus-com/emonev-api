<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllPermissionRoleAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_ROLE_PERMISSION', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try {
            $body = $this->request->getParsedBody();
            $user = $this->roleRepository->getAllPermissionOneRole((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $user);
    }
}
