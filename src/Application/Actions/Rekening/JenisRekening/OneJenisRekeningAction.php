<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\JenisRekening;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneJenisRekeningAction extends JenisRekeningAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_JENIS_REKENING', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try {
            $jenisRekening = $this->jenisRekeningRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $jenisRekening);
    }
}
