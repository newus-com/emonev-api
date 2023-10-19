<?php

declare(strict_types=1);

namespace App\Application\Actions\SumberDana;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneSumberDanaAction extends SumberDanaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_SUMBER_DANA', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try {
            $sumberDana = $this->sumberDanaRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $sumberDana);
    }
}
