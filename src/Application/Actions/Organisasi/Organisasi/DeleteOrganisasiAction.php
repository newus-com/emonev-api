<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Organisasi;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteOrganisasiAction extends OrganisasiAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_ORGANISASI', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->organisasiRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus organisasi", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
