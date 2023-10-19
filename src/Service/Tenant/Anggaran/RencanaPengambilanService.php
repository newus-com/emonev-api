<?php

declare(strict_types=1);

namespace App\Service\Tenant\Anggaran;

use Exception;
use App\Service\Tahun\TahunService;
use Psr\Container\ContainerInterface;
use App\Service\Organisasi\Unit\UnitService;
use App\Service\Perencanaan\Bidang\BidangService;
use App\Service\Perencanaan\Urusan\UrusanService;
use App\Service\Perencanaan\Program\ProgramService;
use App\Application\Database\DatabaseInterface;
use App\Service\Perencanaan\Kegiatan\KegiatanService;
use App\Service\Organisasi\Organisasi\OrganisasiService;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilanRepository;

class RencanaPengambilanService implements RencanaPengambilanRepository
{
    private $h;
    private $c;
    private $conn;

    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
        $this->conn = $database->c();
    }

    public function findAllBySubDpa(int $subDpaId, string $bulan): array
    {
        $data = $this->h->table('rencana_pengambilan')->select()
            ->where('subDpaId', $subDpaId)
            ->where('bulan', $bulan)
            ->whereNull('deleteAt')->get();

        $newDataPenarikan = [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
        ];
        foreach ($data as $k => $v) {
            if ($v['pagu'] == '1') {
                $newDataPenarikan['belanjaOperasi'] = $v['realisasi'];
            }
            if ($v['pagu'] == '2') {
                $newDataPenarikan['belanjaModal'] = $v['realisasi'];
            }
            if ($v['pagu'] == '3') {
                $newDataPenarikan['belanjaTidakTerduga'] = $v['realisasi'];
            }
            if ($v['pagu'] == '4') {
                $newDataPenarikan['belanjaTransfer'] = $v['realisasi'];
            }
            $return['keteranganPermasalahan'] = $v['keteranganPermasalahan'];
        }
        $return['pagu'] = $newDataPenarikan;
        return $return;
    }


    public function findOneById(int $id): RencanaPengambilan
    {
        $rencanaPengambilan = new RencanaPengambilan();

        $data = $this->h->table('rencana_pengambilan')->select()
            ->where('rencana_pengambilan.id', $id)
            ->whereNull('rencana_pengambilan.deleteAt')->one();
        if ($data == NULL) {
            throw new Exception('Realisasi tidak ditemukan');
        }

        $return = $rencanaPengambilan->fromArray($data);

        return $return;
    }

    public function createRealisasi($rencanaPengambilan)
    {
        $subDpaService = $this->c->get(SubDpaService::class);
        $checkSubDpa = $subDpaService->findOneById((int)$rencanaPengambilan['subDpaId']);
        if (!$checkSubDpa) {
            throw new SubDpaNotFoundException();
        }

        $detailAnggaranDataOld = $this->h->table('detail_anggaran_sub_dpa')->select()->where('subDpaId', $rencanaPengambilan['subDpaId'])->whereNull('deleteAt')->get();
        foreach ($detailAnggaranDataOld as $k => $v) {
            $total = 0;
            $rencanaPengambilanDataOld = $this->h->table('rencana_pengambilan')->select()->where('pagu', $v['pagu'])->where('subDpaId', $rencanaPengambilan['subDpaId'])->whereNull('deleteAt')->get();
            if ($rencanaPengambilanDataOld != NULL) {
                $totalOnData = array_values(array_column($rencanaPengambilanDataOld, 'realisasi'));
                $check = $this->h->table('rencana_pengambilan')->select()->where('pagu', $v['pagu'])->where('bulan', $rencanaPengambilan['bulan'])->where('subDpaId', $rencanaPengambilan['subDpaId'])->one();
                if ($check != NULL) {
                    $total = (array_sum($totalOnData) - $check['realisasi']) + $rencanaPengambilan['pagu'][($v['pagu'] == "1" ? "belanjaOperasi" : ($v['pagu'] == "2" ? "belanjaModal" : ($v['pagu'] == "3" ? "belanjaTidakTerduga" : "belanjaTransfer")))];
                } else {
                    $total = (array_sum($totalOnData)) + $rencanaPengambilan['pagu'][($v['pagu'] == "1" ? "belanjaOperasi" : ($v['pagu'] == "2" ? "belanjaModal" : ($v['pagu'] == "3" ? "belanjaTidakTerduga" : "belanjaTransfer")))];
                }
            }

            if ($v['jumlah'] < $total) {
                throw new Exception('Realisasi tidak boleh melebihi total pagu belanja');
                break;
            }
        }


        try {
            $this->conn->beginTransaction();
            foreach ($rencanaPengambilan['pagu'] as $k => $v) {
                $dataUpdate = [
                    'subDpaId' => $rencanaPengambilan['subDpaId'],
                    'bulan' => $rencanaPengambilan['bulan'],
                    'keteranganPermasalahan' => (!isset($rencanaPengambilan['keteranganPermasalahan']) ? '-' : $rencanaPengambilan['keteranganPermasalahan']),
                    'pagu' => ($k == "belanjaOperasi" ? "1" : ($k == "belanjaModal" ? "2" : ($k == "belanjaTidakTerduga" ? "3" : "4"))),
                    'realisasi' => $v,
                    'createAt' => date('Y-m-d H:i:s')
                ];
                $check = $this->h->table('rencana_pengambilan')->select()->where('pagu', $dataUpdate['pagu'])->where('bulan', $rencanaPengambilan['bulan'])->where('subDpaId', $rencanaPengambilan['subDpaId'])->one();
                if ($check != NULL) {
                    $update = $this->h->table('rencana_pengambilan')->update($dataUpdate)->where('pagu', $dataUpdate['pagu'])->where('bulan', $rencanaPengambilan['bulan'])->where('subDpaId', $rencanaPengambilan['subDpaId'])->execute();
                } else {
                    $update = $this->h->table('rencana_pengambilan')->insert($dataUpdate)->execute();
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Gagal menyimpan realisasi");
        }
    }

    public function updateRealisasi(int $id, RencanaPengambilan $rencabaPengambilan): RencanaPengambilan
    {
        $subDpaService = $this->c->get(SubDpaService::class);
        $checkSubDpa = $subDpaService->findOneById((int)$rencabaPengambilan->subDpaId);
        if (!$checkSubDpa) {
            throw new SubDpaNotFoundException();
        }


        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('rencana_pengambilan')->update(array_filter($rencabaPengambilan->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new Exception('Gagal mengubah realisasi');
            }
        } else {
            throw new Exception('Realisasi tidak ditemukan');
        }
    }
}
