<?php

declare(strict_types=1);

namespace App\Service\Tenant\Anggaran;

use Exception;
use App\Service\Table;
use App\Service\Dinas\DinasService;
use App\Service\Tahun\TahunService;
use Psr\Container\ContainerInterface;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use App\Domain\Dinas\DinasNotFoundException;
use App\Domain\Tahun\TahunNotFoundException;
use App\Service\Organisasi\Unit\UnitService;
use App\Application\Database\DatabaseInterface;
use App\Domain\Tenant\Anggaran\Dpa\DpaRepository;
use App\Service\Perencanaan\Bidang\BidangService;
use App\Service\Perencanaan\Urusan\UrusanService;
use App\Service\Perencanaan\Program\ProgramService;
use App\Domain\Organisasi\Unit\UnitNotFoundException;
use App\Service\Perencanaan\Kegiatan\KegiatanService;
use App\Domain\Tenant\Anggaran\Dpa\DpaIsExistException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaRepository;
use App\Domain\Tenant\Anggaran\Dpa\DpaNotFoundException;
use App\Service\Organisasi\Organisasi\OrganisasiService;
use App\Domain\Perencanaan\Bidang\BidangNotFoundException;
use App\Domain\Perencanaan\Urusan\UrusanNotFoundException;
use App\Domain\Perencanaan\Program\ProgramNotFoundException;
use App\Domain\Tenant\Anggaran\Dpa\DpaFailedDeleteException;
use App\Domain\Tenant\Anggaran\Dpa\DpaFailedInsertException;
use App\Domain\Tenant\Anggaran\Dpa\DpaFailedUpdateException;
use App\Domain\Perencanaan\Kegiatan\KegiatanNotFoundException;
use App\Domain\Organisasi\Organisasi\OrganisasiNotFoundException;
use App\Domain\Perencanaan\Kegiatan\Kegiatan;
use ClanCats\Hydrahon\Query\Sql\Func as F;

class DpaService implements DpaRepository
{
    public $h;
    public $c;
    public $conn;
    private TahunService $tahunService;
    private DinasService $dinasService;
    private UrusanService $urusanService;
    private BidangService $bidangService;
    private ProgramService $programService;
    private KegiatanService $kegiatanService;
    private OrganisasiService $organisasiService;
    private UnitService $unitService;


    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $tahunService = $c->get(TahunService::class);
        $dinasService = $c->get(DinasService::class);
        $urusanService = $c->get(UrusanService::class);
        $bidangService = $c->get(BidangService::class);
        $programService = $c->get(ProgramService::class);
        $kegiatanService = $c->get(KegiatanService::class);
        $organisasiService = $c->get(OrganisasiService::class);
        $unitService = $c->get(UnitService::class);

        $this->h = $database->h();
        $this->conn = $database->c();

        $this->tahunService = $tahunService;
        $this->dinasService = $dinasService;
        $this->urusanService = $urusanService;
        $this->bidangService = $bidangService;
        $this->programService = $programService;
        $this->kegiatanService = $kegiatanService;
        $this->organisasiService = $organisasiService;
        $this->unitService = $unitService;
    }

    /**
     * @param array|null $options
     * @return Dpa[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('dpa')
            ->select("dpa.id, dinas.name, tahun.tahun, dpa.noDpa, urusan.nomenklatur")
            ->leftJoin('kegiatan', 'kegiatan.id', '=', 'dpa.kegiatanId')
            ->leftJoin('program', 'program.id', '=', 'kegiatan.programId')
            ->leftJoin('bidang', 'bidang.id', '=', 'program.bidangId')
            ->leftJoin('urusan', 'urusan.id', '=', 'bidang.urusanId')
            ->join('tahun', 'tahun.id', '=', 'dpa.tahunId')
            ->join('dinas', 'dinas.id', '=', 'dpa.dinasId')
            ->whereNull('dpa.deleteAt');
        if (isset($options['tahunId']) && $options['tahunId'] != "" && $options['tahunId'] != "0") {
            $table = $table->where('tahun.id', $options['tahunId']);
        } else {
            $table = $table->where('tahun.active', 1);
        }

        if (isset($options['dinasId']) && $options['dinasId'] != "" && $options['dinasId'] != "0") {
            $table = $table->where('dinas.id', $options['dinasId']);
        }
        $dataTable = new Table($table, columnOrder: ['dpa.id', 'dinas.name', 'tahun.tahun', 'dpa.noDpa', 'urusan.nomenklatur'], columnSearch: ['dpa.id', 'dinas.name', 'tahun.tahun', 'dpa.noDpa', 'urusan.nomenklatur']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param array|null $options
     * @return Dpa[]
     */
    public function findAllPengambilan(array|null $options): array
    {
        $table = $this->h->table('rencana_penarikan_dpa')->select("
            rencana_penarikan_dpa.id, 
            rencana_penarikan_dpa.dpaId, 
            rencana_penarikan_dpa.pagu,
            rencana_penarikan_dpa.bulan,
            rencana_penarikan_dpa.jumlah
        ")->whereNull('rencana_penarikan_dpa.deleteAt');

        if (isset($options['dpaId']) && $options['dpaId'] != "" && $options['dpaId'] != "0") {
            $table = $table->where('rencana_penarikan_dpa.dpaId', $options['dpaId']);
        }
        $data = [
            [
                "bulan" => "Januari",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Februari",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Maret",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "April",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Mei",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Juni",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Juli",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Agustus",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "September",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Oktober",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "November",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ],
            [
                "bulan" => "Desember",
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ]
        ];
        $table = $table->get();
        foreach ($table as $k => $v) {
            if ($v['bulan'] == "Januari") {
                if ($v['pagu'] == "1") {
                    $data[0]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[0]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[0]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[0]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Februari") {
                if ($v['pagu'] == "1") {
                    $data[1]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[1]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[1]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[1]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Maret") {
                if ($v['pagu'] == "1") {
                    $data[2]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[2]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[2]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[2]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "April") {
                if ($v['pagu'] == "1") {
                    $data[3]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[3]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[3]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[3]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Mei") {
                if ($v['pagu'] == "1") {
                    $data[4]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[4]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[4]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[4]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Juni") {
                if ($v['pagu'] == "1") {
                    $data[5]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[5]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[5]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[5]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Juli") {
                if ($v['pagu'] == "1") {
                    $data[6]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[6]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[6]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[6]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Agustus") {
                if ($v['pagu'] == "1") {
                    $data[7]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[7]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[7]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[7]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "September") {
                if ($v['pagu'] == "1") {
                    $data[8]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[8]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[8]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[8]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Oktober") {
                if ($v['pagu'] == "1") {
                    $data[9]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[9]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[9]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[9]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "November") {
                if ($v['pagu'] == "1") {
                    $data[10]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[10]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[10]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[10]['belanjaTransfer'] = $v['jumlah'];
                }
            }
            if ($v['bulan'] == "Desember") {
                if ($v['pagu'] == "1") {
                    $data[11]['belanjaOperasi'] = $v['jumlah'];
                }
                if ($v['pagu'] == "2") {
                    $data[11]['belanjaModal'] = $v['jumlah'];
                }
                if ($v['pagu'] == "3") {
                    $data[11]['belanjaTidakTerduga'] = $v['jumlah'];
                }
                if ($v['pagu'] == "4") {
                    $data[11]['belanjaTransfer'] = $v['jumlah'];
                }
            }
        }
        return $data;
    }

    public function getDetailPagu($dpaId)
    {
        $checkDpa = $this->findOneById((int)$dpaId);
        if (!$checkDpa) {
            throw new DpaNotFoundException();
        }

        $getSubDpa = $this->h->table('sub_dpa')->select("
            sub_dpa.id, 
            sub_dpa.dpaId,
            sub_dpa.jumlahAnggaran
        ")
            ->where('sub_dpa.dpaId', $dpaId)
            ->whereNull('sub_dpa.deleteAt')->get();

        $dataBase = [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
        ];

        foreach ($getSubDpa as $k => $v) {
            $getDetailPagu = $this->h->table('detail_anggaran_sub_dpa')->select("
                        detail_anggaran_sub_dpa.id, 
                        detail_anggaran_sub_dpa.subDpaId,
                        detail_anggaran_sub_dpa.pagu,
                        detail_anggaran_sub_dpa.jumlah
                    ")
                ->where('detail_anggaran_sub_dpa.subDpaId', $v['id'])
                ->whereNull('detail_anggaran_sub_dpa.deleteAt')->get();

            foreach ($getDetailPagu as $d => $p) {
                if ($p['pagu'] == '1') {
                    $dataBase['belanjaOperasi'] += $p['jumlah'];
                }
                if ($p['pagu'] == '2') {
                    $dataBase['belanjaModal'] += $p['jumlah'];
                }
                if ($p['pagu'] == '3') {
                    $dataBase['belanjaTidakTerduga'] += $p['jumlah'];
                }
                if ($p['pagu'] == '4') {
                    $dataBase['belanjaTransfer'] += $p['jumlah'];
                }
            }
        }

        return $dataBase;
    }

    public function createOrUpdateRencanaPenarikan($dpaId, $rencanaPenarikan)
    {
        $checkDpa = $this->findOneById((int)$dpaId);
        if (!$checkDpa) {
            throw new DpaNotFoundException();
        }

        $getSubDpa = $this->h->table('sub_dpa')->select("
            sub_dpa.id, 
            sub_dpa.dpaId,
            sub_dpa.jumlahAnggaran
        ")
            ->where('sub_dpa.dpaId', $dpaId)
            ->whereNull('sub_dpa.deleteAt')->get();

        $dataBase = [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
        ];

        foreach ($getSubDpa as $k => $v) {
            $getDetailPagu = $this->h->table('detail_anggaran_sub_dpa')->select("
                        detail_anggaran_sub_dpa.id, 
                        detail_anggaran_sub_dpa.subDpaId,
                        detail_anggaran_sub_dpa.pagu,
                        detail_anggaran_sub_dpa.jumlah
                    ")
                ->where('detail_anggaran_sub_dpa.subDpaId', $v['id'])
                ->whereNull('detail_anggaran_sub_dpa.deleteAt')->get();

            foreach ($getDetailPagu as $d => $p) {
                if ($p['pagu'] == '1') {
                    $dataBase['belanjaOperasi'] += $p['jumlah'];
                }
                if ($p['pagu'] == '2') {
                    $dataBase['belanjaModal'] += $p['jumlah'];
                }
                if ($p['pagu'] == '3') {
                    $dataBase['belanjaTidakTerduga'] += $p['jumlah'];
                }
                if ($p['pagu'] == '4') {
                    $dataBase['belanjaTransfer'] += $p['jumlah'];
                }
            }
        }

        $dataNew = [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
        ];

        foreach ($rencanaPenarikan as $k => $v) {
            $dataNew['belanjaOperasi'] += $v['belanjaOperasi'];
            $dataNew['belanjaModal'] += $v['belanjaModal'];
            $dataNew['belanjaTidakTerduga'] += $v['belanjaTidakTerduga'];
            $dataNew['belanjaTransfer'] += $v['belanjaTransfer'];
        }

        if ($dataBase['belanjaOperasi'] < $dataNew['belanjaOperasi']) {
            throw new Exception('Belanja operasi tidak boleh lebih dari pagu');
        }

        if ($dataBase['belanjaModal'] < $dataNew['belanjaModal']) {
            throw new Exception('Belanja modal tidak boleh lebih dari pagu');
        }

        if ($dataBase['belanjaTidakTerduga'] < $dataNew['belanjaTidakTerduga']) {
            throw new Exception('Belanja tidak terduga tidak boleh lebih dari pagu');
        }

        if ($dataBase['belanjaTransfer'] < $dataNew['belanjaTransfer']) {
            throw new Exception('Belanja transfer tidak boleh lebih dari pagu');
        }
        try {
            $this->conn->beginTransaction();
            foreach ($rencanaPenarikan as $k => $v) {
                foreach ($v as $g => $r) {
                    $dd = [
                        'dpaId' => $dpaId,
                        'pagu' => ($g == "belanjaOperasi" ? "1" : ($g == "belanjaModal" ? "2" : ($g == "belanjaTidakTerduga" ? "3" : "4"))),
                        'bulan' => $k,
                        'jumlah' => $r
                    ];
                    $check = $this->h->table('rencana_penarikan_dpa')->select()
                        ->where('dpaId', $dpaId)
                        ->where('pagu', $dd['pagu'])
                        ->where('bulan', $dd['bulan'])
                        ->whereNull('rencana_penarikan_dpa.deleteAt')->one();
                    if ($check != NULL) {
                        //update
                        $dd['updateAt'] = date('Y-m-d H:i:s');
                        $this->h->table('rencana_penarikan_dpa')->update($dd)->where('id', $check['id'])->execute();
                    } else {
                        //insert
                        $dd['createAt'] = date('Y-m-d H:i:s');
                        $this->h->table('rencana_penarikan_dpa')->insert($dd)->execute();
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception('Gagal memperbarui data rencana penarikan');
            // throw new Exception($e->getMessage());
        }
    }


    public function findPenggunaAnggaran(int $id)
    {
        $data = $this->h->table('dpa')->select("
            dpa.id, dpa.penggunaAnggaran
        ")
            ->where('dpa.id', $id)
            ->whereNull('dpa.deleteAt')
            ->one();
        if ($data == NULL) {
            throw new DpaNotFoundException();
        }
        $data['penggunaAnggaran'] = ($data['penggunaAnggaran'] == null ? [] : json_decode($data['penggunaAnggaran'], true));
        $return = $data;
        return $return;
    }

    public function updatePenggunaAnggaran(int $id, $penggunaAnggaran)
    {
        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('dpa')->update([
                    'penggunaAnggaran' => json_encode($penggunaAnggaran)
                ])->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new DpaFailedUpdateException('Gagal menyimpan pengguna anggaran');
            }
        } else {
            throw new DpaNotFoundException();
        }
    }

    public function findTandaTangan(int $id)
    {
        $data = $this->h->table('dpa')->select("
            dpa.id, dpa.ttdDpa
        ")
            ->where('dpa.id', $id)
            ->whereNull('dpa.deleteAt')
            ->one();
        if ($data == NULL) {
            throw new DpaNotFoundException();
        }
        $ttd = ($data['ttdDpa'] == null ? [] : json_decode($data['ttdDpa'], true));
        if ($ttd != []) {
            $data['dataTandaTangan'] = $ttd['data'];
        }else{
            $data['dataTandaTangan'] = [];
        }
        $data['ttdDpa'] = [
            'kota' => (isset($ttd['kota']) ? $ttd['kota'] : ''),
            'tanggal' => (isset($ttd['tanggal']) ? $ttd['tanggal'] : '')
        ];
        $return = $data;
        return $return;
    }

    public function updateTandaTangan(int $id, $ttdDpa)
    {
        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('dpa')->update([
                    'ttdDpa' => json_encode($ttdDpa)
                ])->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new DpaFailedUpdateException('Gagal menyimpan tanda tangan');
            }
        } else {
            throw new DpaNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws DpaNotFoundException
     */
    public function findOneById(int $id)
    {
        $data = $this->h->table('dpa')->select("
            dpa.id, dpa.noDpa, dpa.jumlahAlokasi, 
            dpa.dinasId, dpa.tahunId, bidang.urusanId, program.bidangId, kegiatan.programId, dpa.kegiatanId, unit.organisasiId, dpa.unitId,
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
            ->join('dinas', 'dinas.id', '=', 'dpa.dinasId')
            ->join('tahun', 'tahun.id', '=', 'dpa.tahunId')
            ->join('kegiatan', 'kegiatan.id', '=', 'dpa.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->join('bidang', 'bidang.id', '=', 'program.bidangId')
            ->join('urusan', 'urusan.id', '=', 'bidang.urusanId')
            ->join('unit', 'unit.id', '=', 'dpa.unitId')
            ->join('organisasi', 'organisasi.id', '=', 'unit.organisasiId')
            ->where('dpa.id', $id)
            ->whereNull('dpa.deleteAt')
            ->one();
        if ($data == NULL) {
            throw new DpaNotFoundException();
        }
        $subDpa = $this->h->table('sub_dpa')->select()->addFieldSum('jumlahAnggaran', 'jumlahAnggaran')->where('dpaId', $id)->whereNull('deleteAt')->one();
        $data['jumlahTerAlokasi'] = ($subDpa['jumlahAnggaran'] == NULL ? 0 : $subDpa['jumlahAnggaran']);
        $return = $data;
        return $return;
    }

    /**
     * @param string $noDpa
     * @return Dpa
     * @throws DpaNotFoundException
     */
    public function findOneByNoDpa(string $noDpa): Dpa
    {
        $Dpa = new Dpa();

        $data = $this->h->table('dpa')->select()->where('noDpa', $noDpa)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new DpaNotFoundException();
        }
        $return = $Dpa->fromArray($data);

        return $return;
    }

    /**
     * @param int $id
     * @return void
     * @throws DpaNotFoundException
     */
    public function findDetailByid(int $id)
    {
        $return = [];
        $data = $this->h->table('dpa')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new DpaNotFoundException();
        }
        $return = $data;
        $return["capaianProgram"] = json_decode($data['capaianProgram'], TRUE);
        $return["alokasiTahun"] = json_decode($data['alokasiTahun'], TRUE);
        $return["indikatorKinerja"] = json_decode($data['indikatorKinerja'], TRUE);
        $return["rencanaPenarikan"] = json_decode($data['rencanaPenarikan'], TRUE);
        $return["timAnggaran"] = json_decode($data['timAnggaran'], TRUE);
        $return["ttdDpa"] = json_decode($data['ttdDpa'], TRUE);

        $subDpaRepository = $this->c->get(SubDpaRepository::class);

        $subDpa = $subDpaRepository->findAllByDpaId($data['id']);
        $return['SubDpa'] = $subDpa;

        return $return;
    }

    /**
     * @param Dpa $Dpa
     * @return array
     * @throws DpaFailedInsertException
     */
    public function create(Dpa $Dpa)
    {
        $checkTahun = $this->tahunService->findOneById($Dpa->tahunId);
        if (!$checkTahun) {
            throw new TahunNotFoundException();
        }

        $checkDinas = $this->dinasService->findOneById($Dpa->dinasId);
        if (!$checkDinas) {
            throw new DinasNotFoundException();
        }

        $checkNoDpa = $this->h->table('dpa')->select()
            ->where('noDpa', '=', $Dpa->noDpa)
            ->where('dinasId', '=', $Dpa->dinasId)
            ->whereNull('deleteAt')->one();

        if ($checkNoDpa) {
            throw new Exception("No DPA telah ditambahkan sebelumnya");
        }
        try {
            $insert = $this->h->table('dpa')->insert($Dpa->toArray())->execute();
            if ($insert) {
                $Dpa->id = (int)$insert;
                return $Dpa;
            }
        } catch (Exception $e) {
            throw new DpaFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param Dpa $Dpa
     * @return void
     * @throws DpaNotFoundException
     * @throws DpaFailedUpdateException
     */
    public function update(int $id, Dpa $Dpa)
    {
        $checkTahun = $this->tahunService->findOneById($Dpa->tahunId);
        if (!$checkTahun) {
            throw new TahunNotFoundException();
        }

        $checkDinas = $this->dinasService->findOneById($Dpa->dinasId);
        if (!$checkDinas) {
            throw new DinasNotFoundException();
        }

        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('dpa')->update(array_filter($Dpa->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new DpaFailedUpdateException($e->getMessage());
            }
        } else {
            throw new DpaNotFoundException();
        }
    }


    public function updateRincian(int $id, Dpa $Dpa)
    {
        $checkKegiatan = $this->kegiatanService->findOneById($Dpa->kegiatanId);
        if (!$checkKegiatan) {
            throw new KegiatanNotFoundException();
        }

        $checkUnit = $this->unitService->findOneById($Dpa->unitId);
        if (!$checkUnit) {
            throw new UnitNotFoundException();
        }

        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $update = $this->h->table('dpa')->update(array_filter($Dpa->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new DpaFailedUpdateException($e->getMessage());
            }
        } else {
            throw new DpaNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws DpaNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldDpa = $this->findOneById($id);
        if ($oldDpa) {
            try {
                $delete = $this->h->table('dpa')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new DpaFailedDeleteException();
            }
        } else {
            throw new DpaNotFoundException();
        }
    }
}
