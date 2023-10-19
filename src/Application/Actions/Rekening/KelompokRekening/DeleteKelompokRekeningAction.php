<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\KelompokRekening;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteKelompokRekeningAction extends KelompokRekeningAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_KELOMPOK_REKENING', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->kelompokRekeningRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus kelompok rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
