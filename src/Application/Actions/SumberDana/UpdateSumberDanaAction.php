<?php

declare(strict_types=1);

namespace App\Application\Actions\SumberDana;

use App\Domain\SumberDana\SumberDana;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSumberDanaAction extends SumberDanaAction
{
    private $rule = [
        'sumberDana' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_SUMBER_DANA', $permission)) {
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
            $sumberDana = new SumberDana();
            $putDataSumberDana = $sumberDana->fromArray($body);
            $updateService = $this->sumberDanaRepository->update((int)$this->args['id'], $putDataSumberDana);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah sumber dana", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
