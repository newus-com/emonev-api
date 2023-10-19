<?php

declare(strict_types=1);

namespace App\Application\Actions\KomponenPembangunan;

use App\Domain\KomponenPembangunan\KomponenPembangunan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateKomponenPembangunanAction extends KomponenPembangunanAction
{
    private $rule = [
        'parentId' => 'required',
        'komponen' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_KOMPONEN_PEMBANGUNAN', $permission)) {
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
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $komponenPembangunan = new KomponenPembangunan();
            $putDataKomponenPembangunan = $komponenPembangunan->fromArray($body);
            $updateService = $this->komponenPembangunanRepository->update((int)$this->args['id'], $putDataKomponenPembangunan);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah komponen pembangunan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
