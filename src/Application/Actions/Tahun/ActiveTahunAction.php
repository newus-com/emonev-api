<?php

declare(strict_types=1);

namespace App\Application\Actions\Tahun;

use App\Domain\Tahun\Tahun;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ActiveTahunAction extends TahunAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_TAHUN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $tahun = new Tahun();
            $putDataTahun = $tahun->fromArray($body);
            $updateService = $this->tahunRepository->active((int)$this->args['id'], $putDataTahun);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah tahun", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
