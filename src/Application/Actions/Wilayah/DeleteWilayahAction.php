<?php

declare(strict_types=1);

namespace App\Application\Actions\Wilayah;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteWilayahAction extends WilayahAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_WILAYAH', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $this->wilayahRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus wilayah", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
