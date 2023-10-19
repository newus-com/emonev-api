<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteRoleAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_ROLE', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }


        try{
            $roleDelete = $this->roleRepository->deleteRole((int)$this->args['id']);
            if(!$roleDelete){
                return $this->respondWithData(message: "role not found", statusCode: 404);
            }
        }catch(Exception $e){
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
        return $this->respondWithData(data: $roleDelete, message:"Success", statusCode: 200);
    }
}
