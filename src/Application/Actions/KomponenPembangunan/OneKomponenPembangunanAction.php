<?php

declare(strict_types=1);

namespace App\Application\Actions\KomponenPembangunan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneKomponenPembangunanAction extends KomponenPembangunanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_KOMPONEN_PEMBANGUNAN', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }
        try {
            $komponenPembangunan = $this->komponenPembangunanRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $komponenPembangunan);
    }
}
