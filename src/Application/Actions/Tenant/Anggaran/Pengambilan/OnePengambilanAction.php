<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Pengambilan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OnePengambilanAction extends PengambilanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_RENCANA_PENGAMBILAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $subDpa = $this->subDpaRepository->findOneByIdAndDetail((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $subDpa);
    }
}
