<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa;

use Exception;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpa;

class UpdateDetailKetSubDpaAction extends DetailKetSubDpaAction
{
    private $rule = [
        "ketSubDpaId" => "required",
        "satuanId" => "required",
        "uraian" => "required",
        "spesifikasi" => "required",
        "koefisien" => "required|numeric",
        "harga" => "required|numeric",
        "ppn" => "required|numeric",     
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RINCIANG_BELANJA_DPA', $permission)) {
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
            if($body['ppn'] <= 0){
                $body['jumlah'] = ($body['koefisien'] * $body['harga']);
            }else{
                $body['jumlah'] = ($body['koefisien'] * $body['harga']) + (($body['koefisien'] * $body['harga']) * ($body['ppn'] / 100));
            }
            $DetailKetSubDpa = new DetailKetSubDpa();
            $putDataDetailKetSubDpa = $DetailKetSubDpa->fromArray($body);

            $insertService = $this->detailKetSubDpaRepository->update((int)$this->args['id'], $putDataDetailKetSubDpa);
            return $this->respondWithData(data: $insertService, message: "Berhasil mengubah rincian belanja", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
