<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneSubDpaAction extends SubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (in_array('R_SUB_KEGIATAN_DPA', $permission) || in_array('U_DATA_UMUM_PEMBANGUNAN', $permission)) {
            try {
                $subDpa = $this->subDpaRepository->findOneById((int)$this->args['id']);
            } catch (Exception $e) {
                return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
            }

            return $this->respondWithData(data: $subDpa);
        } else {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
    }
}
