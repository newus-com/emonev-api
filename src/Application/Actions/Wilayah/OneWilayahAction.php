<?php

declare(strict_types=1);

namespace App\Application\Actions\Wilayah;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneWilayahAction extends WilayahAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_WILAYAH', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }
        try {
            $wilayah = $this->wilayahRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $wilayah);
    }
}
