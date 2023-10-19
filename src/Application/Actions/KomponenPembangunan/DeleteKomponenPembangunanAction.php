<?php

declare(strict_types=1);

namespace App\Application\Actions\KomponenPembangunan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteKomponenPembangunanAction extends KomponenPembangunanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_KOMPONEN_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $this->komponenPembangunanRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus komponen pembangunan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
