<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\SubKegiatan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteSubKegiatanAction extends SubKegiatanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_SUB_KEGIATAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->subKegiatanRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus sub kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
