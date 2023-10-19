<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteKetSubDpaAction extends KetSubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_RINCIAN_BELANJA_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->ketSubDpaRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus rincian rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
