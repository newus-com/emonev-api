<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteSubDpaAction extends SubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_SUB_KEGIATAN_DPA_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->subDpaRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus Sub Kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
