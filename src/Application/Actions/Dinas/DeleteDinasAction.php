<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteDinasAction extends DinasAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_DINAS', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->dinasRepository->deleteDinas((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil Menghapus User", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
