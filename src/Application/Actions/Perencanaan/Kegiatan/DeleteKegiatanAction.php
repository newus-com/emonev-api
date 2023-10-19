<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Kegiatan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteKegiatanAction extends KegiatanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_KEGIATAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->kegiatanRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
