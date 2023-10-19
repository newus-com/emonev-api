<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\JenisRekening;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteJenisRekeningAction extends JenisRekeningAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_JENIS_REKENING', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->jenisRekeningRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus jenis rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
