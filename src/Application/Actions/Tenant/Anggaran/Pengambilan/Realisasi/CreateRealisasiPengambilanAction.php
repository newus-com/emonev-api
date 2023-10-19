<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\PengambilanAction;

class CreateRealisasiPengambilanAction extends PengambilanAction
{
    private $rule = [
        "subDpaId" => "required",
        "bulan" => "required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember",
        "pagu.belanjaOperasi" => "required|numeric",
        "pagu.belanjaModal" => "required|numeric",
        "pagu.belanjaTidakTerduga" => "required|numeric",
        "pagu.belanjaTransfer" => "required|numeric",
        // "keteranganPermasalahan" => "required",
        
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_REALIASI_ANGGARAN', $permission)) {
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
            $body["createAt"] = (string)date('Y-m-d H:i:s');

            $insertService = $this->rencanaPengambilanRepository->createRealisasi($body);

            return $this->respondWithData(data: [], message: "Berhasil menyimpan realisasi", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
