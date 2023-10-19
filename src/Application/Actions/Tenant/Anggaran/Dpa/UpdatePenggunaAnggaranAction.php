<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa;

use Exception;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class UpdatePenggunaAnggaranAction extends DpaAction
{
    private $rule = [
        "dpaId" => "required",
        "penggunaAnggaran.*.id" => "required",
        "penggunaAnggaran.*.nama" => "required",
        "penggunaAnggaran.*.nip" => "required",
        "penggunaAnggaran.*.jabatan" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_PENGGUNA_ANGGARAN_DPA', $permission)) {
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
            $updateService = $this->dpaRepository->updatePenggunaAnggaran((int)$body['dpaId'], $body['penggunaAnggaran']);
            
            return $this->respondWithData(data: $updateService, message: "Berhasil menyimpan pengguna anggaran", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
