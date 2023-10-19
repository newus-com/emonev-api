<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListSubDpaAction extends SubDpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_SUB_KEGIATAN_DPA_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $body = $this->request->getParsedBody();
            $subDpa = $this->subDpaRepository->findAll($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $subDpa);
    }
}
