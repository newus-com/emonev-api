<?php

declare(strict_types=1);

namespace App\Service\Tenant\Anggaran;

use Exception;
use App\Service\Table;
use Psr\Container\ContainerInterface;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpa;
use App\Service\SumberDana\SumberDanaService;
use App\Application\Database\DatabaseInterface;
use App\Application\Database\DatabaseTenantInterface;
use App\Domain\SumberDana\SumberDanaNotFoundException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaRepository;
use App\Domain\Tenant\Anggaran\Dpa\DpaNotFoundException;
use App\Service\Perencanaan\SubKegiatan\SubKegiatanService;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanRepository;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaRepository;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaFailedUpdateException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanNotFoundException;
use PDOException;

class SubDpaService implements SubDpaRepository
{
    private $h;
    private $c;
    private $conn;
    private DpaService $dpaService;
    private SubKegiatanService $subKegiatanService;
    private SumberDanaService $sumberDanaService;


    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $dpaService = $c->get(DpaService::class);
        $subKegiatanService = $c->get(SubKegiatanService::class);
        $sumberDanaService = $c->get(SumberDanaService::class);

        $this->h = $database->h();
        $this->conn = $database->c();
        $this->dpaService = $dpaService;
        $this->subKegiatanService = $subKegiatanService;
        $this->sumberDanaService = $sumberDanaService;
    }

    /**
     * @param array|null $options
     * @return SubDpa[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('sub_dpa')->select("
            sub_dpa.id, 
            sub_dpa.lokasi, 
            sub_dpa.target,
            sub_dpa.waktuPelaksanaan,
            sub_dpa.keterangan,
            sub_dpa.dpaId,
            sub_dpa.subKegiatanId,
            sub_dpa.sumberDanaId,
            sumber_dana.sumberDana,
            sub_dpa.jumlahAnggaran,
            sub_kegiatan.kode as subKegiatanKode,
            sub_kegiatan.nomenklatur as subKegiatanNomenklatur, 
            sub_kegiatan.kegiatanId as kegiatanId,
            kegiatan.kode as kegiatanKode,
            kegiatan.nomenklatur as kegiatanNomenklatur, 
            program.kode as programKode,
            program.nomenklatur as programNomenklatur,
            dinas.name as dinasName,
            tahun.tahun as tahun
        ")
            ->join('dpa', 'dpa.id', '=', 'sub_dpa.dpaId')
            ->join('tahun', 'tahun.id', '=', 'dpa.tahunId')
            ->join('dinas', 'dinas.id', '=', 'dpa.dinasId')
            ->join('sumber_dana', 'sumber_dana.id', '=', 'sub_dpa.sumberDanaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa.subKegiatanId')
            ->join('kegiatan', 'kegiatan.id', '=', 'sub_kegiatan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->whereNull('sub_dpa.deleteAt');

        if (isset($options['dpaId']) && $options['dpaId'] != "" && $options['dpaId'] != "0") {
            $table = $table->where('sub_dpa.dpaId', $options['dpaId']);
        }

        if (isset($options['dinasId']) && $options['dinasId'] != "" && $options['dinasId'] != "0") {
            $table = $table->where('dpa.dinasId', $options['dinasId']);
        }

        if (isset($options['tahunId']) && $options['tahunId'] != "" && $options['tahunId'] != "0") {
            $table = $table->where('dpa.tahunId', $options['tahunId']);
        }

        $dataTable = new Table($table, columnOrder: [
            "sub_dpa.lokasi",
            "sub_dpa.target",
            "sub_dpa.waktuPelaksanaan",
            "sub_dpa.keterangan",
            "sub_dpa.dpaId",
            "sub_dpa.subKegiatanId",
            "sub_dpa.sumberDanaId",
            "sumber_dana.sumberDana",
            "sub_dpa.jumlahAnggaran",
            "sub_kegiatan.kode",
            "sub_kegiatan.nomenklatur"
        ], columnSearch: [
            "sub_dpa.id",
            "sub_dpa.lokasi",
            "sub_dpa.target",
            "sub_dpa.waktuPelaksanaan",
            "sub_dpa.keterangan",
            "sub_dpa.dpaId",
            "sub_dpa.subKegiatanId",
            "sub_dpa.sumberDanaId",
            "sumber_dana.sumberDana",
            "sub_dpa.jumlahAnggaran",
            "sub_kegiatan.kode",
            "sub_kegiatan.nomenklatur"
        ]);
        $dataTable->post = $options;
        $newData = [];
        foreach ($dataTable->getDatatables() as $k => $v) {
            $detailPagu = [
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
                "belanjaOperasiRealisasi" => 0,
                "belanjaModalRealisasi" => 0,
                "belanjaTidakTerdugaRealisasi" => 0,
                "belanjaTransferRealisasi" => 0,
            ];
            $pagu = $this->h->table('detail_anggaran_sub_dpa')->select()->where('subDpaId', $v['id'])->whereNull('deleteAt')->get();
            foreach ($pagu as $d => $s) {
                $pengambilan = $this->h->table('rencana_pengambilan')->select()->where('subDpaId', $v['id'])->where('pagu', $s['pagu']);
                if (isset($options['bulan']) && $options['bulan'] != "" && $options['bulan'] != "0") {
                    $pengambilan = $pengambilan->where('bulan', $options['bulan']);
                }
                $pengambilan = $pengambilan->whereNull('deleteAt')->get();
                foreach ($pengambilan as $g => $y) {
                    if ($y['pagu'] == '1') {
                        $detailPagu['belanjaOperasiRealisasi'] += $y['realisasi'];
                    }
                    if ($y['pagu'] == '2') {
                        $detailPagu['belanjaModalRealisasi'] += $y['realisasi'];
                    }
                    if ($y['pagu'] == '3') {
                        $detailPagu['belanjaTidakTerdugaRealisasi'] += $y['realisasi'];
                    }
                    if ($y['pagu'] == '4') {
                        $detailPagu['belanjaTransferRealisasi'] += $y['realisasi'];
                    }
                }
                if ($s['pagu'] == "1") {
                    $detailPagu["belanjaOperasi"] = $s['jumlah'];
                }
                if ($s['pagu'] == "2") {
                    $detailPagu["belanjaModal"] = $s['jumlah'];
                }
                if ($s['pagu'] == "3") {
                    $detailPagu["belanjaTidakTerduga"] = $s['jumlah'];
                }
                if ($s['pagu'] == "4") {
                    $detailPagu["belanjaTransfer"] = $s['jumlah'];
                }
            }

            $v['detailPagu'] = $detailPagu;
            $newData[] = $v;
        }

        $this->transformData($newData);

        return [
            // 'data' => $newData,
            'dataTree' => $this->newData,
            'dataTotal' => $this->total,
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }

    private $newData = [];

    private $total = [
        "pagu" => [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
        ],
        "paguRealisasi" => [
            "belanjaOperasiRealisasi" => 0,
            "belanjaModalRealisasi" => 0,
            "belanjaTidakTerdugaRealisasi" => 0,
            "belanjaTransferRealisasi" => 0,
        ]
    ];

    private function transformData(array $data = [])
    {
        foreach ($data as $k => $v) {
            $checkProgram = $this->isObjectInArray($this->newData, [
                'programKode' => $v['programKode'],
            ], 'programKode');
            if ($checkProgram['status'] != true) {
                $key = $this->addProgram($v);
                $checkProgram['key'] = $key;
            }
            $this->addJumlahProgram($checkProgram['key'], $v);

            $checkKegiatan = $this->isObjectInArray($this->newData[$checkProgram['key']]['kegiatan'], [
                'kegiatanKode' => $v['kegiatanKode'],
            ], 'kegiatanKode');
            if ($checkKegiatan['status'] != true) {
                $key = $this->addKegiatan($checkProgram['key'], $v);
                $checkKegiatan['key'] = $key;
            }
            $this->addJumlahKegiatan($checkProgram['key'], $checkKegiatan['key'], $v);

            $this->addSubKegiatan($checkProgram['key'], $checkKegiatan['key'], $v);
        }
    }

    private function addProgram($data)
    {
        $this->newData[] = [
            'programKode' => $data['programKode'],
            'programNomenklatur' => $data['programNomenklatur'],
            'programJumlah' => [
                "pagu" => [
                    "belanjaOperasi" => 0,
                    "belanjaModal" => 0,
                    "belanjaTidakTerduga" => 0,
                    "belanjaTransfer" => 0,
                ],
                "paguRealisasi" => [
                    "belanjaOperasiRealisasi" => 0,
                    "belanjaModalRealisasi" => 0,
                    "belanjaTidakTerdugaRealisasi" => 0,
                    "belanjaTransferRealisasi" => 0,
                ]
            ],
            'kegiatan' => []
        ];
        return count($this->newData) - 1;
    }

    private function addKegiatan($keyProgram, $data)
    {
        $this->newData[$keyProgram]['kegiatan'][] = [
            'kegiatanKode' => $data['kegiatanKode'],
            'kegiatanNomenklatur' => $data['kegiatanNomenklatur'],
            'kegiatanJumlah' => [
                "pagu" => [
                    "belanjaOperasi" => 0,
                    "belanjaModal" => 0,
                    "belanjaTidakTerduga" => 0,
                    "belanjaTransfer" => 0,
                ],
                "paguRealisasi" => [
                    "belanjaOperasiRealisasi" => 0,
                    "belanjaModalRealisasi" => 0,
                    "belanjaTidakTerdugaRealisasi" => 0,
                    "belanjaTransferRealisasi" => 0,
                ]
            ],
            'sub_kegiatan' => []
        ];
        return count($this->newData[$keyProgram]['kegiatan']) - 1;
    }

    private function addSubKegiatan($keyProgram, $keyKegiatan, $data)
    {
        $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['sub_kegiatan'][] = [
            'subKegiatanKode' => $data['subKegiatanKode'],
            'subKegiatanNomenklatur' => $data['subKegiatanNomenklatur'],
            'subKegiatanJumlah' => [
                "pagu" => [
                    "belanjaOperasi" => $data['detailPagu']['belanjaOperasi'],
                    "belanjaModal" => $data['detailPagu']['belanjaModal'],
                    "belanjaTidakTerduga" => $data['detailPagu']['belanjaTidakTerduga'],
                    "belanjaTransfer" => $data['detailPagu']['belanjaTransfer'],
                ],
                "paguRealisasi" => [
                    "belanjaOperasiRealisasi" => $data['detailPagu']['belanjaOperasiRealisasi'],
                    "belanjaModalRealisasi" => $data['detailPagu']['belanjaModalRealisasi'],
                    "belanjaTidakTerdugaRealisasi" => $data['detailPagu']['belanjaTidakTerdugaRealisasi'],
                    "belanjaTransferRealisasi" => $data['detailPagu']['belanjaTransferRealisasi'],
                ]
            ],
        ];

        $this->total['pagu']['belanjaOperasi'] += $data['detailPagu']['belanjaOperasi'];
        $this->total['pagu']['belanjaModal'] += $data['detailPagu']['belanjaModal'];
        $this->total['pagu']['belanjaTidakTerduga'] +=  $data['detailPagu']['belanjaTidakTerduga'];
        $this->total['pagu']['belanjaTransfer'] +=  $data['detailPagu']['belanjaTransfer'];

        $this->total['paguRealisasi']['belanjaOperasiRealisasi'] += $data['detailPagu']['belanjaOperasiRealisasi'];
        $this->total['paguRealisasi']['belanjaModalRealisasi'] += $data['detailPagu']['belanjaModalRealisasi'];
        $this->total['paguRealisasi']['belanjaTidakTerdugaRealisasi'] +=  $data['detailPagu']['belanjaTidakTerdugaRealisasi'];
        $this->total['paguRealisasi']['belanjaTransferRealisasi'] +=  $data['detailPagu']['belanjaTransferRealisasi'];
        return count($this->newData[$keyKegiatan]['kegiatan']) - 1;
    }

    private function addJumlahProgram($keyProgram, $data)
    {
        $this->newData[$keyProgram]['programJumlah'] = [
            "pagu" => [
                "belanjaOperasi" => $this->newData[$keyProgram]['programJumlah']['pagu']['belanjaOperasi'] + $data['detailPagu']['belanjaOperasi'],
                "belanjaModal" => $this->newData[$keyProgram]['programJumlah']['pagu']['belanjaModal'] + $data['detailPagu']['belanjaModal'],
                "belanjaTidakTerduga" => $this->newData[$keyProgram]['programJumlah']['pagu']['belanjaTidakTerduga'] + $data['detailPagu']['belanjaTidakTerduga'],
                "belanjaTransfer" => $this->newData[$keyProgram]['programJumlah']['pagu']['belanjaTransfer'] + $data['detailPagu']['belanjaTransfer'],
            ],
            "paguRealisasi" => [
                "belanjaOperasiRealisasi" => $this->newData[$keyProgram]['programJumlah']['paguRealisasi']['belanjaOperasiRealisasi'] + $data['detailPagu']['belanjaOperasiRealisasi'],
                "belanjaModalRealisasi" => $this->newData[$keyProgram]['programJumlah']['paguRealisasi']['belanjaModalRealisasi'] + $data['detailPagu']['belanjaModalRealisasi'],
                "belanjaTidakTerdugaRealisasi" => $this->newData[$keyProgram]['programJumlah']['paguRealisasi']['belanjaTidakTerdugaRealisasi'] + $data['detailPagu']['belanjaTidakTerdugaRealisasi'],
                "belanjaTransferRealisasi" => $this->newData[$keyProgram]['programJumlah']['paguRealisasi']['belanjaTransferRealisasi'] + $data['detailPagu']['belanjaTransferRealisasi'],
            ]
        ];
    }

    private function addJumlahKegiatan($keyProgram, $keyKegiatan, $data)
    {
        $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah'] = [
            "pagu" => [
                "belanjaOperasi" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['pagu']['belanjaOperasi'] + $data['detailPagu']['belanjaOperasi'],
                "belanjaModal" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['pagu']['belanjaModal'] + $data['detailPagu']['belanjaModal'],
                "belanjaTidakTerduga" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['pagu']['belanjaTidakTerduga'] + $data['detailPagu']['belanjaTidakTerduga'],
                "belanjaTransfer" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['pagu']['belanjaTransfer'] + $data['detailPagu']['belanjaTransfer'],
            ],
            "paguRealisasi" => [
                "belanjaOperasiRealisasi" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['paguRealisasi']['belanjaOperasiRealisasi'] + $data['detailPagu']['belanjaOperasiRealisasi'],
                "belanjaModalRealisasi" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['paguRealisasi']['belanjaModalRealisasi'] + $data['detailPagu']['belanjaModalRealisasi'],
                "belanjaTidakTerdugaRealisasi" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['paguRealisasi']['belanjaTidakTerdugaRealisasi'] + $data['detailPagu']['belanjaTidakTerdugaRealisasi'],
                "belanjaTransferRealisasi" => $this->newData[$keyProgram]['kegiatan'][$keyKegiatan]['kegiatanJumlah']['paguRealisasi']['belanjaTransferRealisasi'] + $data['detailPagu']['belanjaTransferRealisasi'],
            ]
        ];
    }

    private function isObjectInArray($array, $objectToCheck, $key)
    {
        foreach ($array as $k => $v) {
            if ($v[$key] === $objectToCheck[$key]) {
                return [
                    'status' => true,
                    'key' => $k
                ];
            }
        }
        return [
            'status' => false
        ];
    }


    /**
     * @param int $id
     * @return void
     * @throws SubDpaNotFoundException
     */
    public function findOneById(int $id)
    {
        $data = $this->h->table('sub_dpa')->select("
            sub_dpa.id, 
            sub_dpa.lokasi, 
            sub_dpa.target,
            sub_dpa.waktuPelaksanaan,
            sub_dpa.keterangan,
            sub_dpa.dpaId,
            sub_dpa.subKegiatanId,
            sub_dpa.sumberDanaId,
            sumber_dana.sumberDana,
            sub_dpa.jumlahAnggaran,
            sub_kegiatan.kode as subKegiatanKode,
            sub_kegiatan.nomenklatur as subKegiatanNomenklatur,
            kegiatan.kode as kegiatanKode,
            kegiatan.nomenklatur as kegiatanNomenklatur, 
            program.kode as programKode,
            program.nomenklatur as programNomenklatur
        ")
            ->join('sumber_dana', 'sumber_dana.id', '=', 'sub_dpa.sumberDanaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa.subKegiatanId')
            ->join('kegiatan', 'kegiatan.id', '=', 'sub_kegiatan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->where('sub_dpa.id', $id)->whereNull('sub_dpa.deleteAt')->one();
        if ($data == NULL) {
            throw new SubDpaNotFoundException();
        }

        $newDataPenarikan = [
            "belanjaOperasi" => 0,
            "belanjaModal" => 0,
            "belanjaTidakTerduga" => 0,
            "belanjaTransfer" => 0,
            "belanjaOperasiRealisasi" => 0,
            "belanjaModalRealisasi" => 0,
            "belanjaTidakTerdugaRealisasi" => 0,
            "belanjaTransferRealisasi" => 0,
        ];
        $penarikan = $this->h->table('detail_anggaran_sub_dpa')->select()->where('subDpaId', $id)->whereNull('deleteAt')->get();
        foreach ($penarikan as $k => $v) {
            if ($v['pagu'] == '1') {
                $newDataPenarikan['belanjaOperasi'] = $v['jumlah'];
            }
            if ($v['pagu'] == '2') {
                $newDataPenarikan['belanjaModal'] = $v['jumlah'];
            }
            if ($v['pagu'] == '3') {
                $newDataPenarikan['belanjaTidakTerduga'] = $v['jumlah'];
            }
            if ($v['pagu'] == '4') {
                $newDataPenarikan['belanjaTransfer'] = $v['jumlah'];
            }
        }

        $newDataPengambilan = [];
        $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Agustus", "September", "Oktober", "November", "Desember"];
        foreach ($bulan as $k => $v) {
            $newDataPengambilan[$v] = [
                "belanjaOperasi" => 0,
                "belanjaModal" => 0,
                "belanjaTidakTerduga" => 0,
                "belanjaTransfer" => 0,
            ];
            $pengambilan = $this->h->table('rencana_pengambilan')->select()->where('subDpaId', $id)->where('bulan', $v)->whereNull('deleteAt')->get();
            foreach ($pengambilan as $g => $y) {
                if ($y['pagu'] == '1') {
                    $newDataPengambilan[$v]['belanjaOperasi'] += $y['realisasi'];
                    $newDataPenarikan['belanjaOperasiRealisasi'] += $y['realisasi'];
                }
                if ($y['pagu'] == '2') {
                    $newDataPengambilan[$v]['belanjaModal'] += $y['realisasi'];
                    $newDataPenarikan['belanjaModalRealisasi'] += $y['realisasi'];
                }
                if ($y['pagu'] == '3') {
                    $newDataPengambilan[$v]['belanjaTidakTerduga'] += $y['realisasi'];
                    $newDataPenarikan['belanjaTidakTerdugaRealisasi'] += $y['realisasi'];
                }
                if ($y['pagu'] == '4') {
                    $newDataPengambilan[$v]['belanjaTransfer'] += $y['realisasi'];
                    $newDataPenarikan['belanjaTransferRealisasi'] += $y['realisasi'];
                }
            }
        }

        $data['penarikan'] = $newDataPenarikan;
        $data['pengambilan'] = $newDataPengambilan;

        $return = $data;
        return $return;
    }

    public function findOneByIdAndDetail(int $id)
    {
        $data = $this->h->table('sub_dpa')->select("
            sub_dpa.id, 
            sub_dpa.lokasi, 
            sub_dpa.target,
            sub_dpa.waktuPelaksanaan,
            sub_dpa.keterangan,
            sub_dpa.dpaId,
            sub_dpa.subKegiatanId,
            sub_dpa.sumberDanaId,
            dpa.noDpa as noDpa,
            sumber_dana.sumberDana,
            sub_kegiatan.kode as subKegiatanKode,
            sub_kegiatan.nomenklatur as subKegiatanNomenklatur,
            kegiatan.kode as kegiatanKode,
            kegiatan.nomenklatur as kegiatanNomenklatur,
            program.kode as programKode,
            program.nomenklatur as programNomenklatur
        ")
            ->join('sumber_dana', 'sumber_dana.id', '=', 'sub_dpa.sumberDanaId')
            ->join('dpa', 'dpa.id', '=', 'sub_dpa.dpaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa.subKegiatanId')
            ->join('kegiatan', 'kegiatan.id', '=', 'sub_kegiatan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->where('sub_dpa.id', $id)->whereNull('sub_dpa.deleteAt')->one();
        if ($data == NULL) {
            throw new SubDpaNotFoundException();
        }

        $data['RincianRekening'] = $this->h->table('ket_sub_dpa')->select("
                ket_sub_dpa.id, 
                ket_sub_dpa.jumlahAnggaran, 
                ket_sub_dpa.subRincianObjekRekeningId,
                sub_rincian_objek_rekening.kode as subRincianObjekRekeningKode,
                sub_rincian_objek_rekening.uraianAkun as subRincianObjekRekeningUraian
            ")
            ->join('sub_rincian_objek_rekening', 'sub_rincian_objek_rekening.id', '=', 'ket_sub_dpa.subRincianObjekRekeningId')
            ->where('ket_sub_dpa.subDpaId', $data['id'])->whereNull('ket_sub_dpa.deleteAt')->get();
        $data['TotalAnggaran'] = 0;
        if ($data['RincianRekening'] != NULL) {
            $data['TotalAnggaran'] = array_sum(array_values(array_column($data['RincianRekening'], 'jumlahAnggaran')));
        }
        $return = $data;
        return $return;
    }

    /**
     * @param int $dpaId
     * @return array
     */
    public function findAllByDpaId(int $dpaId)
    {
        $ketSubDpa = $this->c->get(KetSubDpaRepository::class);
        $return = [];
        $data = $this->h->table('sub_dpa')->select()->where('dpaId', $dpaId)->whereNull('deleteAt')->get();
        if ($data == NULL) {
            return $return;
        }
        $temp = [];
        foreach ($data as $k => $v) {
            $tempSubDpa = $v;
            $tempSubDpa['KetSubDpa'] = $ketSubDpa->findAllBySubDpaId($v['id']);
            $temp[] = $tempSubDpa;
        }
        $return = $temp;
        return $return;
    }

    /**
     * @param array $SubDpa
     * @return array
     * @throws SubDpaFailedInsertException
     */
    public function create(array $SubDpa)
    {
        $checkDpa = $this->dpaService->findOneById((int)$SubDpa['dpaId']);
        if (!$checkDpa) {
            throw new DpaNotFoundException();
        }

        $checkSumberDana = $this->sumberDanaService->findOneById((int)$SubDpa['sumberDanaId']);
        if (!$checkSumberDana) {
            throw new SumberDanaNotFoundException();
        }

        $checkSubKegiatan = $this->subKegiatanService->findOneById((int)$SubDpa['subKegiatanId']);
        if (!$checkSubKegiatan) {
            throw new SubKegiatanNotFoundException();
        }

        $dataSubDpa = [
            'dpaId' => $SubDpa['dpaId'],
            'sumberDanaId' => $SubDpa['sumberDanaId'],
            'subKegiatanId' => $SubDpa['subKegiatanId'],
            'lokasi' => $SubDpa['lokasi'],
            'target' => $SubDpa['target'],
            'waktuPelaksanaan' => $SubDpa['waktuPelaksanaan'],
            'keterangan' => $SubDpa['keterangan'],
            'jumlahAnggaran' => $SubDpa['belanjaOperasi'] + $SubDpa['belanjaModal'] + $SubDpa['belanjaTidakTerduga'] + $SubDpa['belanjaTransfer'],
            'createAt' => $SubDpa['createAt'],
        ];

        $total = $checkDpa['jumlahTerAlokasi'] + $dataSubDpa['jumlahAnggaran'];
        if ($checkDpa['jumlahAlokasi'] < $total) {
            throw new Exception('Jumlah alokasi tidak cukup');
        }

        try {
            $this->conn->beginTransaction();
            $insert = $this->h->table('sub_dpa')->insert($dataSubDpa)->execute();
            $dataPagu = [
                [
                    "subDpaId" => (int)$insert,
                    "pagu" => "1",
                    "jumlah" => $SubDpa['belanjaOperasi'],
                    'createAt' => $SubDpa['createAt'],
                ],
                [
                    "subDpaId" => (int)$insert,
                    "pagu" => "2",
                    "jumlah" => $SubDpa['belanjaModal'],
                    'createAt' => $SubDpa['createAt'],
                ],
                [
                    "subDpaId" => (int)$insert,
                    "pagu" => "3",
                    "jumlah" => $SubDpa['belanjaTidakTerduga'],
                    'createAt' => $SubDpa['createAt'],
                ],
                [
                    "subDpaId" => (int)$insert,
                    "pagu" => "4",
                    "jumlah" => $SubDpa['belanjaTransfer'],
                    'createAt' => $SubDpa['createAt'],
                ]
            ];

            $insertPagu = $this->h->table('detail_anggaran_sub_dpa')->insert($dataPagu)->execute();

            $this->conn->commit();

            return $this->findOneById((int)$insert);
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new SubDpaFailedInsertException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @param array $subDpa
     * @return void
     * @throws SubDpaNotFoundException
     * @throws SubDpaFailedUpdateException
     */
    public function update(int $id, $SubDpa)
    {
        $checkDpa = $this->dpaService->findOneById((int)$SubDpa['dpaId']);
        if (!$checkDpa) {
            throw new DpaNotFoundException();
        }

        $checkSumberDana = $this->sumberDanaService->findOneById((int)$SubDpa['sumberDanaId']);
        if (!$checkSumberDana) {
            throw new SumberDanaNotFoundException();
        }

        $checkSubKegiatan = $this->subKegiatanService->findOneById((int)$SubDpa['subKegiatanId']);
        if (!$checkSubKegiatan) {
            throw new SubKegiatanNotFoundException();
        }
        $oldSubDpa = $this->findOneById($id);
        if ($oldSubDpa) {
            $dataSubDpa = [
                'dpaId' => $SubDpa['dpaId'],
                'sumberDanaId' => $SubDpa['sumberDanaId'],
                'subKegiatanId' => $SubDpa['subKegiatanId'],
                'lokasi' => $SubDpa['lokasi'],
                'target' => $SubDpa['target'],
                'waktuPelaksanaan' => $SubDpa['waktuPelaksanaan'],
                'keterangan' => $SubDpa['keterangan'],
                'jumlahAnggaran' => $SubDpa['belanjaOperasi'] + $SubDpa['belanjaModal'] + $SubDpa['belanjaTidakTerduga'] + $SubDpa['belanjaTransfer'],
                'updateAt' => $SubDpa['updateAt'],
            ];

            $total = ($checkDpa['jumlahTerAlokasi'] - $oldSubDpa['jumlahAnggaran']) + $dataSubDpa['jumlahAnggaran'];
            if ($checkDpa['jumlahAlokasi'] < $total) {
                throw new Exception('Jumlah alokasi tidak cukup');
            }

            //check juga rincian belanja
            $total = 0;

            $dataKetSubDpa = $this->h->table('ket_sub_dpa')->select()->where('subDpaId', (int)$id)->whereNull('deleteAt')->get();
            if ($dataKetSubDpa != NULL) {
                $totalOnData = array_values(array_column($dataKetSubDpa, 'jumlah'));
                $total = array_sum($totalOnData);
            }

            if ($dataSubDpa['jumlahAnggaran'] < $total) {
                throw new Exception('Ubah rincian belanja terlebih dahulu, karena rincian belanja melebihi pagu');
            }


            try {
                $this->conn->beginTransaction();
                $this->h->table('sub_dpa')->update($dataSubDpa)->where('id', $id)->execute();
                $dataPagu = [
                    [
                        "subDpaId" => $id,
                        "pagu" => "1",
                        "jumlah" => $SubDpa['belanjaOperasi'],
                        'updateAt' => $SubDpa['updateAt'],
                    ],
                    [
                        "subDpaId" => $id,
                        "pagu" => "2",
                        "jumlah" => $SubDpa['belanjaModal'],
                        'updateAt' => $SubDpa['updateAt'],
                    ],
                    [
                        "subDpaId" => $id,
                        "pagu" => "3",
                        "jumlah" => $SubDpa['belanjaTidakTerduga'],
                        'updateAt' => $SubDpa['updateAt'],
                    ],
                    [
                        "subDpaId" => $id,
                        "pagu" => "4",
                        "jumlah" => $SubDpa['belanjaTransfer'],
                        'updateAt' => $SubDpa['updateAt'],
                    ]
                ];

                foreach ($dataPagu as $k => $v) {
                    $this->h->table('detail_anggaran_sub_dpa')->update($v)->where('subDpaId', $v['subDpaId'])->where('pagu', $v['pagu'])->execute();
                }

                $this->conn->commit();

                return $this->findOneById((int)$id);
            } catch (Exception $e) {
                $this->conn->rollBack();
                throw new SubDpaFailedInsertException($e->getMessage());
            }
        } else {
            throw new SubDpaNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SubDpaNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldSubDpa = $this->findOneById($id);
        if ($oldSubDpa) {
            try {
                $delete = $this->h->table('sub_dpa')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new SubDpaFailedInsertException();
            }
        } else {
            throw new SubDpaNotFoundException();
        }
    }
}
