<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Monitoring;

use App\Domain\Tenant\Pembangunan\Perencanaan\Perencanaan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateDataUmumMonitoringAction extends MonitoringAction
{
    private $rule = [
        "judulKontrak" => "required",
        "nilaiKontrak" => "required|numeric",
        "noKontrak" => "required",
        "jenisPengadaan" => "required",
        "mekanismePengadaan" => "required",
        "swakelola" => "required",
        "tanggalKontrak" => "required",
        "tanggalMulai" => "required",
        "tanggalSelesai" => "required",
        "ppk" => "required",
        "pelaksana" => "required",
        "lokasi" => "required",
        "kendala" => "required",
        "tenagaKerja" => "required|numeric",
        "penerapanK3" => "required",
        "keterangan" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_DATA_UMUM_PEMBANGUNAN', $permission)) {
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
            $body = $this->request->getParsedBody();

            $newBody = [
                "judulKontrak" => $body['judulKontrak'],
                "nilaiKontrak" => $body['nilaiKontrak'],
                "noKontrak" => $body['noKontrak'],
                "jenisPengadaan" => $body['jenisPengadaan'],
                "mekanismePengadaan" => $body['mekanismePengadaan'],
                "swakelola" => $body['swakelola'],
                "tanggalKontrak" => $body['tanggalKontrak'],
                "tanggalMulai" => $body['tanggalMulai'],
                "tanggalSelesai" => $body['tanggalSelesai'],
                "ppk" => $body['ppk'],
                "pelaksana" => $body['pelaksana'],
                "lokasi" => $body['lokasi'],
                "kendala" => $body['kendala'],
                "tenagaKerja" => $body['tenagaKerja'],
                "penerapanK3" => $body['penerapanK3'],
                "keterangan" => $body['keterangan'],
            ];
            $newBody['updateAt'] = date('Y-m-d H:i:s');
            $perencanaan = $this->perencanaanRepository->update((int)$this->args['id'], $newBody);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan, message: "Berhasil memperbarui data");
    }
}
