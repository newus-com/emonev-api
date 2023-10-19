<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa;

use Exception;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpa;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSubDpaAction extends SubDpaAction
{
    private $rule = [
        "dpaPembangunanId" => "required",
        "sumberDanaId" => "required",
        "subKegiatanId" => "required",
        "jumlahAnggaran" => "required|numeric",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_SUB_KEGIATAN_DPA_PEMBANGUNAN', $permission)) {
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
