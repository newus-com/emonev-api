<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteDetailKetSubDpaAction extends DetailKetSubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_RINCIANG_BELANJA_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $this->detailKetSubDpaRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus rincian belanja", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
