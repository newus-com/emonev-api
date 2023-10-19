<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\PengambilanAction;

class ListRealisasiPengambilanAction extends PengambilanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_REALIASI_ANGGARAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $rencanaPengambilan = $this->rencanaPengambilanRepository->findAllBySubDpa((int)$this->args['id'], (string)$this->args['bulan']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $rencanaPengambilan);
    }
}
