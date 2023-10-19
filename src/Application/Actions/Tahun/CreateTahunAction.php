<?php

declare(strict_types=1);

namespace App\Application\Actions\Tahun;

use App\Domain\Tahun\Tahun;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTahunAction extends TahunAction
{
    private $rule = [
        'tahun' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_TAHUN', $permission)) {
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
            $tahun = new Tahun();
            $putDataTahun = $tahun->fromArray($body);
            $insertService = $this->tahunRepository->create($putDataTahun);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah tahun", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
