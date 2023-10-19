<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneKetSubDpaAction extends KetSubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_RINCIAN_BELANJA_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $ketSubDpa = $this->ketSubDpaRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $ketSubDpa);
    }
}
