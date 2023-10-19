<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Monitoring;

use App\Domain\Tenant\Pembangunan\Perencanaan\Perencanaan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateInformasiMonitoringAction extends MonitoringAction
{
    private $rule = [
        "keselamatanKontruksi" => "required",
        "keselamatanKontruksi.SemuaPekerjaDilindungiDenganAsuransiKesehatan" => "required",
        "keselamatanKontruksi.KalauTerjadiKecelakaanKerjaSudahAdaRencana" => "required",
        "keselamatanKontruksi.DilokasiKerjaSudahTersediaKotakP3K" => "required",
        "keselamatanKontruksi.DilokasiKerjaSudahAdaRambuKeselamatanAtauPetunjuk" => "required",
        "keselamatanKontruksi.AdaKeluhanDariWargaSekitarTerkaitAkibatPelaksanaanPembangunan" => "required",

        "catatan" => "required",

        "catatan.KepalaTukangBerasal" => "required",
        "catatan.KepalaTukangBerasal.Asal" => "required",
        "catatan.KepalaTukangBerasal.PunyaSKTatauSKK" => "required",

        "catatan.JumlahPekerja" => "required",
        "catatan.PekerjaBerasalDariKabupatenPesisirBarat" => "required|numeric",
        "catatan.PekerjaBerasalDariLuarKabupatenPesisirBarat" => "required|numeric",

        "catatan.MaterialBerasalDari" => "required",
        "catatan.MaterialBerasalDari.PesisirBarat" => "required|numeric",
        "catatan.MaterialBerasalDari.LuarPesisirBarat" => "required|numeric",

        "timMonitoring" => "required",
        "timMonitoring.*.NamaTimAnggaran" => "required",
        "timMonitoring.*.NipTimAnggaran" => "required",
        "timMonitoring.*.JabatanTimAnggaran" => "required"
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_MONITORING_PEMBANGUNAN', $permission)) {
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
            $newBody = [
                "keselamatanKontruksi" => (string)json_encode($body['keselamatanKontruksi'], JSON_PRETTY_PRINT),
                "catatan" => (string)json_encode($body['catatan'], JSON_PRETTY_PRINT),
                "timMonitoring" => (string)json_encode($body['timMonitoring'], JSON_PRETTY_PRINT),
                "updateAt" => (string)date('Y-m-d H:i:s')
            ];

            $dataRencana = $rencana->fromArray($newBody);
            $perencanaan = $this->perencanaanRepository->update((int)$this->args['id'], $dataRencana);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan, message: "Berhasil memperbarui data");
    }
}
