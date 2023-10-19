<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Perencanaan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListPerencanaanAction extends PerencanaanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_RENCANA_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $body = $this->request->getParsedBody();
            $perencanaan = $this->perencanaanRepository->findAll($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan);
    }
}
