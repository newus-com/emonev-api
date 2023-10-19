<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\ObjekRekening;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListObjekRekeningAction extends ObjekRekeningAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_OBJEK_REKENING', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try {
            $body = $this->request->getParsedBody();
            $objekRekening = $this->objekRekeningRepository->findAll($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $objekRekening);
    }
}
