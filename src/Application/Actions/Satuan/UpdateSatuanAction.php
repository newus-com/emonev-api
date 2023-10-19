<?php

declare(strict_types=1);

namespace App\Application\Actions\Satuan;

use App\Domain\Satuan\Satuan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSatuanAction extends SatuanAction
{
    private $rule = [
        'satuan' => 'required',
        'pembangunan' => 'required|in:0,1',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_SATUAN', $permission)) {
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
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $satuan = new Satuan();
            $putDataSatuan = $satuan->fromArray($body);
            $updateService = $this->satuanRepository->update((int)$this->args['id'], $putDataSatuan);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah satuan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
