<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Perencanaan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class AddKomponenPerencanaanAction extends PerencanaanAction
{
    private $rule = [
        "rencanaPembangunanId" => "required",
        "komponenPembangunanId" => "required"
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RENCANA_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }

        try {
            $body = $this->request->getParsedBody();
            $perencanaan = $this->perencanaanRepository->addKomponen($body['rencanaPembangunanId'], $body['komponenPembangunanId']);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan, message: "Berhasil menambah komponen pembangunan");
    }
}
