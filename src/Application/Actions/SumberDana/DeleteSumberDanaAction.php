<?php

declare(strict_types=1);

namespace App\Application\Actions\SumberDana;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteSumberDanaAction extends SumberDanaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_SUMBER_DANA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->sumberDanaRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus sumber dana", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
