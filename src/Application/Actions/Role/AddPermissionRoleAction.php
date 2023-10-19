<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use App\Domain\Role\Role;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class AddPermissionRoleAction extends RoleAction
{
    private $rule = [
        'permission.id' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_ROLE_PERMISSION', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $body = $this->request->getParsedBody();
            if (!isset($body['permission.id'])) {
                return $this->respondWithData(message: "Permission ID is missing in the request body.", statusCode: 400);
            }

            $this->valid->validator()->make($body, $this->rule);
            $rolePermission = $this->roleRepository->addRolePermission((int)$this->args['id'], (int)$body['permission.id']);
            return $this->respondWithData(data: $rolePermission, message: "Success", statusCode: 200);

            if (!$rolePermission) {
                return $this->respondWithData(message: "User not found", statusCode: 404);
            }
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
