<?php

declare(strict_types=1);

namespace App\Application\Actions\Tahun;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteTahunAction extends TahunAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_TAHUN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->tahunRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus tahun", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
