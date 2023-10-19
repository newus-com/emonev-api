<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use App\Domain\Role\Role;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateRoleAction extends RoleAction
{

    private $rule = [
        'dinasId'               => 'required',
        'role'                  => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_ROLE', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            return $this->respondWithData(error: $validation->errors()->firstOfAll(), statusCode: 400);
        }
        try{
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            // $body['dinasId'] = $getDinas;
            $role = new Role();
            $putDataRole = $role->fromArray($body);
            $insertService = $this->roleRepository->updateRole((int)$this->args['id'], $putDataRole);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah role", statusCode: 201);
        }catch(Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
