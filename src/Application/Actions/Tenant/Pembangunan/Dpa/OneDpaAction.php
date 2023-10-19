<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneDpaAction extends DpaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_DPA_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $dpa = $this->dpaRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $dpa);
    }
}
