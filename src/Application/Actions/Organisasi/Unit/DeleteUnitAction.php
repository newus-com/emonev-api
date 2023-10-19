<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Unit;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUnitAction extends UnitAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_UNIT', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->unitRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus unit", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
