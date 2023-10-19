<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Laporan;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListLaporanAction extends LaporanAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_LAPORAN_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $body = $this->request->getParsedBody();
            $perencanaan = $this->perencanaanRepository->findAllRunning($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan);
    }

    private function getRencana(){
        $table = $this->perencanaanRepository->h->table('rencana_pembangunan')
            ->select()
            ->join('detail_ket_sub_dpa', 'detail_ket_sub_dpa.id', '=', 'rencana_pembangunan.detailKetSubDpaId')
            ->join('satuan', 'satuan.id', '=', 'detail_ket_sub_dpa.satuanId')
            ->join('ket_sub_dpa', 'ket_sub_dpa.id', '=', 'detail_ket_sub_dpa.ketSubDpaId')
            ->join('sub_dpa', 'sub_dpa.id', '=', 'ket_sub_dpa.subDpaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa.subKegiatanId')
            ->join('dpa', 'dpa.id', '=', 'sub_dpa.dpaId')
            ->join('tahun', 'tahun.id', '=', 'dpa.tahunId')
            ->whereNull('rencana_pembangunan.deleteAt');
        if (isset($options['tahunId'])) {
            $table = $table->where('tahun.id', $options['tahunId']);
        } else {
            $table = $table->where('tahun.active', 1);
        }
    }
}
