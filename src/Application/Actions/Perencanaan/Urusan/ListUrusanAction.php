<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Urusan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListUrusanAction extends UrusanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission',[]);

        if(!in_array('R_URUSAN', $permission)){
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        try {
            $body = $this->request->getParsedBody();
            $urusan = $this->urusanRepository->findAll($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $urusan);
    }
}
