<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneDetailKetSubDpaAction extends DetailKetSubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_RINCIANG_BELANJA_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $detailKetSubDpa = $this->detailKetSubDpaRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $detailKetSubDpa);
    }
}
