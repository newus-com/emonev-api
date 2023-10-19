<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\PengambilanAction;

class OneRealisasiPengambilanAction extends PengambilanAction
{
    /**
     * {@inheritdoc}
     */

    private $rule = [
        "subDpaId" => "required",
        "bulan" => "required",
    ];
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_REALIASI_ANGGARAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }
        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }
        try {
            $rencanaPengambilan = $this->rencanaPengambilanRepository->findAllBySubDpa((int)$body['subDpaId'], $body['bulan']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $rencanaPengambilan);
    }
}
