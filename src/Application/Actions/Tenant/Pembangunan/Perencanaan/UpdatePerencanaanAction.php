<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Perencanaan;

use App\Domain\Tenant\Pembangunan\Perencanaan\Perencanaan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdatePerencanaanAction extends PerencanaanAction
{
    private $rule = [
        "detailKetSubDpaId" => "required",
        "nilaiKontrak" => "required|numeric",
        "nomorKontrak" => "required",
        "tanggalKontrak" => "required",
        "pejabatPpk" => "required",
        "pelaksana" => "required",
        "lokasiRealisasiAnggaran" => "required",
        "jangkaWaktu" => "required|numeric",
        "mulaiKerja" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RENCANA_PEMBANGUNAN', $permission)) {
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
            $body = $this->request->getParsedBody();
            $rencana = new Perencanaan();
            $dataRencana = $rencana->fromArray($body);
            $perencanaan = $this->perencanaanRepository->createORupdate($dataRencana);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan, message: "Berhasil memperbarui data");
    }
}
