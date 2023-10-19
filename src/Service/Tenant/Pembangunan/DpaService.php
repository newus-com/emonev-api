<?php

declare(strict_types=1);

namespace App\Service\Tenant\Pembangunan;

use Exception;
use App\Service\Table;
use App\Service\Dinas\DinasService;
use App\Service\Tahun\TahunService;
use Psr\Container\ContainerInterface;
use App\Domain\Tenant\Pembangunan\Dpa\Dpa;
use App\Application\Database\DatabaseInterface;
use App\Domain\Tenant\Pembangunan\Dpa\DpaRepository;

class DpaService implements DpaRepository
{
    public $h;
    public $c;
    public $conn;
    private TahunService $tahunService;
    private DinasService $dinasService;

    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $tahunService = $c->get(TahunService::class);
        $dinasService = $c->get(DinasService::class);

        $this->h = $database->h();
        $this->conn = $database->c();

        $this->tahunService = $tahunService;
        $this->dinasService = $dinasService;

        $this->h = $database->h();
        $this->conn = $database->c();


    }

    public function findAll(array|null $options): array
    {
        $table = $this->h->table('dpa_pembangunan')
            ->select("dpa_pembangunan.id, dinas.name, tahun.tahun, dpa_pembangunan.noDpa, urusan.nomenklatur")
            ->leftJoin('kegiatan', 'kegiatan.id', '=', 'dpa_pembangunan.kegiatanId')
            ->leftJoin('program', 'program.id', '=', 'kegiatan.programId')
            ->leftJoin('bidang', 'bidang.id', '=', 'program.bidangId')
            ->leftJoin('urusan', 'urusan.id', '=', 'bidang.urusanId')
            ->join('tahun', 'tahun.id', '=', 'dpa_pembangunan.tahunId')
            ->join('dinas', 'dinas.id', '=', 'dpa_pembangunan.dinasId')
            ->whereNull('dpa_pembangunan.deleteAt');
        if (isset($options['tahunId']) && $options['tahunId'] != "" && $options['tahunId'] != "0") {
            $table = $table->where('tahun.id', $options['tahunId']);
        } else {
            $table = $table->where('tahun.active', 1);
        }

        if (isset($options['dinasId']) && $options['dinasId'] != "" && $options['dinasId'] != "0") {
            $table = $table->where('dinas.id', $options['dinasId']);
        }
        $dataTable = new Table($table, columnOrder: ['dpa_pembangunan.id', 'dinas.name', 'tahun.tahun', 'dpa_pembangunan.noDpa', 'urusan.nomenklatur'], columnSearch: ['dpa_pembangunan.id', 'dinas.name', 'tahun.tahun', 'dpa_pembangunan.noDpa', 'urusan.nomenklatur']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }

    public function create(Dpa $Dpa)
    {
        $checkTahun = $this->tahunService->findOneById($Dpa->tahunId);

        $checkDinas = $this->dinasService->findOneById($Dpa->dinasId);

        $checkNoDpa = $this->h->table('dpa_pembangunan')->select()
            ->where('noDpa', '=', $Dpa->noDpa)
            ->where('dinasId', '=', $Dpa->dinasId)
            ->whereNull('deleteAt')->one();

        if ($checkNoDpa) {
            throw new Exception("No DPA telah ditambahkan sebelumnya");
        }
        try {
            $insert = $this->h->table('dpa_pembangunan')->insert($Dpa->toArray())->execute();
            if ($insert) {
                $Dpa->id = (int)$insert;
                return $Dpa;
            }
        } catch (Exception $e) {
            throw new Exception("Gagal menambah DPA pembangunan");
        }
    }

    public function findOneById(int $id)
    {
        $data = $this->h->table('dpa_pembangunan')->select("
            dpa_pembangunan.id, dpa_pembangunan.noDpa, dpa_pembangunan.jumlahAlokasi, 
            dpa_pembangunan.dinasId, dpa_pembangunan.tahunId, bidang.urusanId, program.bidangId, kegiatan.programId, dpa_pembangunan.kegiatanId, unit.organisasiId, dpa_pembangunan.unitId,
            dinas.name,
            tahun.tahun, 
            urusan.kode as urusanKode, 
            urusan.nomenklatur as urusanNomenklatur, 
            bidang.kode as bidangKode, 
            bidang.nomenklatur as bidangNomenklatur, 
            program.kode as programKode, 
            program.nomenklatur as programNomenklatur, 
            kegiatan.kode as kegiatanKode, 
            kegiatan.nomenklatur as kegiatanNomenklatur, 
            organisasi.kode as organisasiKode, 
            organisasi.nomenklatur as organisasiNomenklatur, 
            unit.kode as unitKode, 
            unit.nomenklatur as unitNomenklatur
        ")
            ->join('dinas', 'dinas.id', '=', 'dpa_pembangunan.dinasId')
            ->join('tahun', 'tahun.id', '=', 'dpa_pembangunan.tahunId')
            ->join('kegiatan', 'kegiatan.id', '=', 'dpa_pembangunan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->join('bidang', 'bidang.id', '=', 'program.bidangId')
            ->join('urusan', 'urusan.id', '=', 'bidang.urusanId')
            ->join('unit', 'unit.id', '=', 'dpa_pembangunan.unitId')
            ->join('organisasi', 'organisasi.id', '=', 'unit.organisasiId')
            ->where('dpa_pembangunan.id', $id)
            ->whereNull('dpa_pembangunan.deleteAt')
            ->one();
        if ($data == NULL) {
            throw new Exception("DPA tidak ditemukan");
        }
        $subDpa = $this->h->table('sub_dpa_pembangunan')->select()->addFieldSum('jumlahAnggaran', 'jumlahAnggaran')->where('dpaPembangunanId', $id)->whereNull('deleteAt')->one();
        $data['jumlahTerAlokasi'] = ($subDpa['jumlahAnggaran'] == NULL ? 0 : $subDpa['jumlahAnggaran']);
        $return = $data;
        return $return;
    }

    public function update(int $id, Dpa $Dpa)
    {
        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('dpa_pembangunan')->update(array_filter($Dpa->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new Exception("DPA gagal di perbarui");
            }
        } else {
            throw new Exception("DPA tidak ditemukan");
        }
    }
}