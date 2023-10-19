<?php

declare(strict_types=1);

namespace App\Application\Actions\Wilayah;

use App\Domain\Wilayah\Wilayah;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateWilayahAction extends WilayahAction
{
    private $rule = [
        'wilayah' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_WILAYAH', $permission)) {
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
            $wilayah = new Wilayah();
            $putDataWilayah = $wilayah->fromArray($body);
            $updateService = $this->wilayahRepository->update((int)$this->args['id'], $putDataWilayah);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah wilayah", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
