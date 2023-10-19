<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa;

use Exception;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpa;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSubDpaAction extends SubDpaAction
{
    private $rule = [
        "dpaId" => "required",
        "sumberDanaId" => "required",
        "subKegiatanId" => "required",
        "lokasi" => "required",
        "target" => "required|numeric",
        "waktuPelaksanaan" => "required",
        "belanjaOperasi" => "required|numeric",
        "belanjaModal" => "required|numeric",
        "belanjaTidakTerduga" => "required|numeric",
        "belanjaTransfer" => "required|numeric",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_SUB_KEGIATAN_DPA', $permission)) {
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

            $insertService = $this->subDpaRepository->update((int)$this->args['id'], $body);
            return $this->respondWithData(data: $insertService, message: "Berhasil mengubah Sub Kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
