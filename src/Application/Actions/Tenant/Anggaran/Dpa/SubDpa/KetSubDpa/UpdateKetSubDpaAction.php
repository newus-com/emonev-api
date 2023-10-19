<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa;

use Exception;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpa;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateKetSubDpaAction extends KetSubDpaAction
{
    private $rule = [
        "subDpaId" => "required",
        "subRincianObjekRekeningId" => "required",
        "satuanId" => "required",
        "uraian" => "required",
        "koefisien" => "required|numeric",
        "harga" => "required|numeric",
        "ppn" => "required|numeric|max:100",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RINCIAN_BELANJA_DPA', $permission)) {
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
            $body['jumlah'] = ($body['harga'] * $body['koefisien']) - (($body['harga'] * $body['koefisien']) * ($body['ppn'] / 100));

            $insertService = $this->ketSubDpaRepository->update((int)$this->args['id'], $body);
            return $this->respondWithData(data: $insertService, message: "Berhasil mengubah rincian belanja", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
