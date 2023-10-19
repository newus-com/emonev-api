<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\PengambilanAction;

class UpdateRealisasiPengambilanAction extends PengambilanAction
{
    private $rule = [
        "subDpaId" => "required",
        "bulan" => "required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember",
        "pengambilan" => "required",
        // "realisasi" => "required",
        "jenisBelanja" => "required|in:1,2,3,4",
        // "totalAnggaranJenisBelanja" => "required|numeric",
        "statusPelaksana" => "required",
        "keteranganPelaksanaan" => "required",

        "persentase" => "required|numeric|max:100",
        "statusKemanfaatan" => "required|in:1,2",
        "keteranganPermasalahan" => "required",
        "dokumenBuktiPendukung" => "required",
        "fotoBuktiPendukung" => "required",
        "videoBuktiPendukung" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_REALIASI_ANGGARAN', $permission)) {
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
            $body["updateAt"] = (string)date('Y-m-d H:i:s');
            $RencanaPengambilan = new RencanaPengambilan();

            $body['realisasi'] = $body['pengambilan'] * ($body['persentase'] / 100);
            $body['totalAnggaranJenisBelanja'] = $body['pengambilan'];

            $putDataRencanaPengambilan = $RencanaPengambilan->fromArray($body);

            $insertService = $this->rencanaPengambilanRepository->updateRealisasi((int)$this->args['id'],$putDataRencanaPengambilan);

            $return = $insertService->toArray();

            return $this->respondWithData(data: $return, message: "Berhasil mengubah realisasi", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
