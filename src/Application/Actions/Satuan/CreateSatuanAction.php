<?php

declare(strict_types=1);

namespace App\Application\Actions\Satuan;

use App\Domain\Satuan\Satuan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateSatuanAction extends SatuanAction
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

        if (!in_array('C_SATUAN', $permission)) {
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
            $body['createAt'] = (string)date('Y-m-d H:i:s');
            $satuan = new Satuan();
            $putDataSatuan = $satuan->fromArray($body);
            $insertService = $this->satuanRepository->create($putDataSatuan);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah satuan", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
