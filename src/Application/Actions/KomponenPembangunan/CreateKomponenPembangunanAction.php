<?php

declare(strict_types=1);

namespace App\Application\Actions\KomponenPembangunan;

use App\Domain\KomponenPembangunan\KomponenPembangunan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateKomponenPembangunanAction extends KomponenPembangunanAction
{
    private $rule = [
        'komponen' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_KOMPONEN_PEMBANGUNAN', $permission)) {
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
            $body['createAt'] = (string)date('Y-m-d H:i:s');
            $komponenPembangunan = new KomponenPembangunan();
            $putDataKomponenPembangunan = $komponenPembangunan->fromArray($body);
            $insertService = $this->komponenPembangunanRepository->create($putDataKomponenPembangunan);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah komponen pembangunan", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
