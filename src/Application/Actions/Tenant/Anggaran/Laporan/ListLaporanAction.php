<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Laporan;

use App\Domain\Tenant\Anggaran\SubDpa\SubDpa;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListLaporanAction extends LaporanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_LAPORAN_ANGGARAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $body = $this->request->getParsedBody();
            $data = $this->subDpaRepository->findAll($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $data);
    }
}
