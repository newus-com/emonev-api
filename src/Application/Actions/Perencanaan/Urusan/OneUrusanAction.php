<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Urusan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class OneUrusanAction extends UrusanAction
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
            $urusan = $this->urusanRepository->findOneById((int)$this->args['id']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $urusan);
    }
}
