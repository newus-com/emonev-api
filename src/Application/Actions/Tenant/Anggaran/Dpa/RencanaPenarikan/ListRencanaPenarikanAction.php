<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\RencanaPenarikan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class ListRencanaPenarikanAction extends DpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_RENCANA_PENARIKAN_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $body = $this->request->getParsedBody();
            $dpa = $this->dpaRepository->findAllPengambilan($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $dpa);
    }
}
