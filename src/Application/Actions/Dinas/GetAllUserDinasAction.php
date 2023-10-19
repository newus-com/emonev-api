<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllUserDinasAction extends DinasAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_DINAS', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        $user = $this->request->getAttribute('user');
        try {
            $body = $this->request->getParsedBody();
            $dinas = $this->dinasRepository->getAllUserInDinas((int)$user['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $dinas);
    }
}
