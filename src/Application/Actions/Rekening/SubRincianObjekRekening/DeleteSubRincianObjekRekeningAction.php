<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\SubRincianObjekRekening;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteSubRincianObjekRekeningAction extends SubRincianObjekRekeningAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_SUB_RINCIAN_OBJEK_REKENING', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->subRincianObjekRekeningRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus sub rincian objek rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
