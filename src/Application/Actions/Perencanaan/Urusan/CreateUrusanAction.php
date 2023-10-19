<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Urusan;

use App\Domain\Perencanaan\Urusan\Urusan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUrusanAction extends UrusanAction
{
    private $rule = [
        'kode' => 'required',
        'nomenklatur' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_URUSAN', $permission)) {
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
            $urusan = new Urusan();
            $putDataUrusan = $urusan->fromArray($body);
            $insertService = $this->urusanRepository->create($putDataUrusan);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah urusan", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
