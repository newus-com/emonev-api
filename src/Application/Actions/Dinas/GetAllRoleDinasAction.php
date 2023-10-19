<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllRoleDinasAction extends DinasAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // $permission = $this->request->getAttribute('permission',[]);

        // if(!in_array('R_BIDANG', $permission)){
        //     return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        // }
        try {
            $body = $this->request->getParsedBody();
            $user = $this->dinasRepository->getAllRoleInDinas((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $user);
    }
}
