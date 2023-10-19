<?php

declare(strict_types=1);

namespace App\Service\Tenant\Anggaran;

use Exception;
use App\Service\Table;
use App\Service\Satuan\SatuanService;
use Psr\Container\ContainerInterface;
use App\Domain\Satuan\SatuanNotFoundException;
use App\Service\Tenant\Anggaran\SubDpaService;
use App\Application\Database\DatabaseInterface;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpa;
use App\Application\Database\DatabaseTenantInterface;
use App\Service\Tenant\Anggaran\DetailKetSubDpaService;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaRepository;
use App\Domain\Tenant\Anggaran\SubDpa\JumlahAnggaranException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaNotFoundException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanNotFoundException;
use App\Domain\Tenant\Anggaran\KetSubDpa\JumlahAnggaranKetException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaFailedDeleteException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaFailedUpdateException;
use App\Service\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningService;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningRepository;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedInsertException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningNotFoundException;

class KetSubDpaService implements KetSubDpaRepository
{
    private $h;
    private $c;
    private SubDpaService $subDpaService;
    private SubRincianObjekRekeningService $subRincianObjekRekeningService;
    private DetailKetSubDpaService $detailKetSubDpaService;
    private SatuanService $satuanService;


    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $subDpaService = $c->get(SubDpaService::class);
        $subRincianObjekRekeningService = $c->get(SubRincianObjekRekeningService::class);
        $satuanService = $c->get(SatuanService::class);

        $this->h = $database->h();
        $this->subDpaService = $subDpaService;
        $this->subRincianObjekRekeningService = $subRincianObjekRekeningService;
        $this->satuanService = $satuanService;
    }

    /**
     * @param array|null $options
     * @return KetSubDpa[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('ket_sub_dpa')->select("
            ket_sub_dpa.id, 
            ket_sub_dpa.satuanId,
            satuan.satuan,
            ket_sub_dpa.uraian,
            ket_sub_dpa.spesifikasi,
            ket_sub_dpa.koefisien,
            ket_sub_dpa.harga,
            ket_sub_dpa.ppn,
            ket_sub_dpa.jumlah,

            ket_sub_dpa.subRincianObjekRekeningId,
            sub_rincian_objek_rekening.kode as subRincianObjekRekeningKode,
            sub_rincian_objek_rekening.uraianAkun as subRincianObjekRekeningUraian,

            sub_rincian_objek_rekening.rincianObjekRekeningId,
            rincian_objek_rekening.kode as rincianObjekRekeningKode,
            rincian_objek_rekening.uraianAkun as rincianObjekRekeningUraian,

            rincian_objek_rekening.objekRekeningId,
            objek_rekening.kode as objekRekeningKode,
            objek_rekening.uraianAkun as objekRekeningUraian,

            objek_rekening.jenisRekeningId,
            jenis_rekening.kode as jenisRekeningKode,
            jenis_rekening.uraianAkun as jenisRekeningUraian,

            jenis_rekening.kelompokRekeningId,
            kelompok_rekening.kode as kelompokRekeningKode,
            kelompok_rekening.uraianAkun as kelompokRekeningUraian,

            kelompok_rekening.akunRekeningId,
            akun_rekening.kode as akunRekeningKode,
            akun_rekening.uraianAkun as akunRekeningUraian
        ")
            ->join('satuan', 'satuan.id', '=', 'ket_sub_dpa.satuanId')
            ->join('sub_rincian_objek_rekening', 'sub_rincian_objek_rekening.id', '=', 'ket_sub_dpa.subRincianObjekRekeningId')
            ->join('rincian_objek_rekening', 'rincian_objek_rekening.id', '=', 'sub_rincian_objek_rekening.rincianObjekRekeningId')
            ->join('objek_rekening', 'objek_rekening.id', '=', 'rincian_objek_rekening.objekRekeningId')
            ->join('jenis_rekening', 'jenis_rekening.id', '=', 'objek_rekening.jenisRekeningId')
            ->join('kelompok_rekening', 'kelompok_rekening.id', '=', 'jenis_rekening.kelompokRekeningId')
            ->join('akun_rekening', 'akun_rekening.id', '=', 'kelompok_rekening.akunRekeningId')
            ->whereNull('ket_sub_dpa.deleteAt');
        if (isset($options['subDpaId']) && $options['subDpaId'] != "" && $options['subDpaId'] != "0") {
            $table = $table->where('ket_sub_dpa.subDpaId', $options['subDpaId']);
        }
        $dataTable = new Table($table, columnOrder: [
            "ket_sub_dpa.id",
            "ket_sub_dpa.satuanId",
            "satuan.satuan",
            "ket_sub_dpa.uraian",
            "ket_sub_dpa.spesifikasi",
            "ket_sub_dpa.koefisien",
            "ket_sub_dpa.harga",
            "ket_sub_dpa.ppn",
            "ket_sub_dpa.jumlah",
            "ket_sub_dpa.subRincianObjekRekeningId",
            "sub_rincian_objek_rekening.kode",
            "sub_rincian_objek_rekening.uraianAkun",

            "sub_rincian_objek_rekening.rincianObjekRekeningId",
            "rincian_objek_rekening.kode",
            "rincian_objek_rekening.uraianAkun",

            "rincian_objek_rekening.objekRekeningId",
            "objek_rekening.kode",
            "objek_rekening.uraianAkun",

            "objek_rekening.jenisRekeningId",
            "jenis_rekening.kode",
            "jenis_rekening.uraianAkun",

            "jenis_rekening.kelompokRekeningId",
            "kelompok_rekening.kode",
            "kelompok_rekening.uraianAkun",

            "kelompok_rekening.akunRekeningId",
            "akun_rekening.kode",
            "akun_rekening.uraianAkun"
        ], columnSearch: [
            "ket_sub_dpa.id",
            "ket_sub_dpa.satuanId",
            "satuan.satuan",
            "ket_sub_dpa.uraian",
            "ket_sub_dpa.spesifikasi",
            "ket_sub_dpa.koefisien",
            "ket_sub_dpa.harga",
            "ket_sub_dpa.ppn",
            "ket_sub_dpa.jumlah",
            "ket_sub_dpa.subRincianObjekRekeningId",
            "sub_rincian_objek_rekening.kode",
            "sub_rincian_objek_rekening.uraianAkun",

            "sub_rincian_objek_rekening.rincianObjekRekeningId",
            "rincian_objek_rekening.kode",
            "rincian_objek_rekening.uraianAkun",

            "rincian_objek_rekening.objekRekeningId",
            "objek_rekening.kode",
            "objek_rekening.uraianAkun",

            "objek_rekening.jenisRekeningId",
            "jenis_rekening.kode",
            "jenis_rekening.uraianAkun",

            "jenis_rekening.kelompokRekeningId",
            "kelompok_rekening.kode",
            "kelompok_rekening.uraianAkun",

            "kelompok_rekening.akunRekeningId",
            "akun_rekening.kode",
            "akun_rekening.uraianAkun"
        ]);
        $dataTable->post = $options;

        $dataKetSubDpa = $dataTable->getDatatables();
        $this->transformData($dataKetSubDpa);

        $ketSubDpa = $this->h->table('ket_sub_dpa')->select()->addFieldSum('jumlah', 'jumlah')->whereNull('deleteAt');
        if (isset($options['subDpaId']) && $options['subDpaId'] != "" && $options['subDpaId'] != "0") {
            $ketSubDpa = $ketSubDpa->where('ket_sub_dpa.subDpaId', $options['subDpaId']);
        }
        $ketSubDpa = $ketSubDpa->one();
        return [
            'data' => $this->newData,
            'dataOri' => $dataKetSubDpa,
            'totalKetSubDpa' => $ketSubDpa['jumlah'],
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }

    private $newData = [];

    private function transformData(array $data = [])
    {
        foreach ($data as $k => $v) {
            $checkAkun = $this->isObjectInArray($this->newData, [
                'akunRekeningId' => $v['akunRekeningId'],
            ], 'akunRekeningId');
            if ($checkAkun['status'] != true) {
                $key = $this->addAkunRekening($v);
                $checkAkun['key'] = $key;
            }
            $this->addJumlahAkunRekening($checkAkun['key'], $v);

            $checkKelompok = $this->isObjectInArray($this->newData[$checkAkun['key']]['kelompok'], [
                'kelompokRekeningId' => $v['kelompokRekeningId'],
            ], 'kelompokRekeningId');
            if ($checkKelompok['status'] != true) {
                $key = $this->addKelompokRekening($checkAkun['key'], $v);
                $checkKelompok['key'] = $key;
            }
            $this->addJumlahKelompokRekening($checkAkun['key'], $checkKelompok['key'], $v);

            $checkJenis = $this->isObjectInArray($this->newData[$checkAkun['key']]['kelompok'][$checkKelompok['key']]['jenis'], [
                'jenisRekeningId' => $v['jenisRekeningId'],
            ], 'jenisRekeningId');
            if ($checkJenis['status'] != true) {
                $key = $this->addJenisRekening($checkAkun['key'], $checkKelompok['key'], $v);
                $checkJenis['key'] = $key;
            }
            $this->addJumlahJenisRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $v);


            $checkObjek = $this->isObjectInArray($this->newData[$checkAkun['key']]['kelompok'][$checkKelompok['key']]['jenis'][$checkJenis['key']]['objek'], [
                'objekRekeningId' => $v['objekRekeningId'],
            ], 'objekRekeningId');
            if ($checkObjek['status'] != true) {
                $key = $this->addObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $v);
                $checkObjek['key'] = $key;
            }
            $this->addJumlahObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $v);

            $checkRincianObjek = $this->isObjectInArray($this->newData[$checkAkun['key']]['kelompok'][$checkKelompok['key']]['jenis'][$checkJenis['key']]['objek'][$checkObjek['key']]['rincianObjek'], [
                'rincianObjekRekeningId' => $v['rincianObjekRekeningId'],
            ], 'rincianObjekRekeningId');
            if ($checkRincianObjek['status'] != true) {
                $key = $this->addRincianObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $v);
                $checkRincianObjek['key'] = $key;
            }
            $this->addJumlahRincianObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $checkRincianObjek['key'], $v);

            $checkSubRincianObjek = $this->isObjectInArray($this->newData[$checkAkun['key']]['kelompok'][$checkKelompok['key']]['jenis'][$checkJenis['key']]['objek'][$checkObjek['key']]['rincianObjek'][$checkRincianObjek['key']]['subRincianObjek'], [
                'subRincianObjekRekeningId' => $v['subRincianObjekRekeningId'],
            ], 'subRincianObjekRekeningId');
            if ($checkSubRincianObjek['status'] != true) {
                $key = $this->addSubRincianObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $checkRincianObjek['key'], $v);
                $checkSubRincianObjek['key'] = $key;
            }
            $this->addJumlahSubRincianObjekRekening($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $checkRincianObjek['key'], $checkSubRincianObjek['key'], $v);

            $this->addKetSubDpa($checkAkun['key'], $checkKelompok['key'], $checkJenis['key'], $checkObjek['key'], $checkRincianObjek['key'], $checkSubRincianObjek['key'], $v);
        }
    }

    private function addAkunRekening($data)
    {
        $this->newData[] = [
            'akunRekeningId' => $data['akunRekeningId'],
            'akunRekeningKode' => $data['akunRekeningKode'],
            'akunRekeningUraian' => $data['akunRekeningUraian'],
            'akunRekeningJumlah' => 0,
            'kelompok' => []
        ];
        return count($this->newData) - 1;
    }

    private function addKelompokRekening($keyAkunRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][] = [
            'kelompokRekeningId' => $data['kelompokRekeningId'],
            'kelompokRekeningKode' => $data['kelompokRekeningKode'],
            'kelompokRekeningUraian' => $data['kelompokRekeningUraian'],
            'kelompokRekeningJumlah' => 0,
            'jenis' => []
        ];
        return count($this->newData[$keyAkunRekening]['kelompok']) - 1;
    }

    private function addJenisRekening($keyAkunRekening, $keyKelompokRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][] = [
            'jenisRekeningId' => $data['jenisRekeningId'],
            'jenisRekeningKode' => $data['jenisRekeningKode'],
            'jenisRekeningUraian' => $data['jenisRekeningUraian'],
            'jenisRekeningJumlah' => 0,
            'objek' => []
        ];
        return count($this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis']) - 1;
    }

    private function addObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][] = [
            'objekRekeningId' => $data['objekRekeningId'],
            'objekRekeningKode' => $data['objekRekeningKode'],
            'objekRekeningUraian' => $data['objekRekeningUraian'],
            'objekRekeningJumlah' => 0,
            'rincianObjek' => []
        ];
        return count($this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek']) - 1;
    }

    private function addRincianObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][] = [
            'rincianObjekRekeningId' => $data['rincianObjekRekeningId'],
            'rincianObjekRekeningKode' => $data['rincianObjekRekeningKode'],
            'rincianObjekRekeningUraian' => $data['rincianObjekRekeningUraian'],
            'rincianObjekRekeningJumlah' => 0,
            'subRincianObjek' => []
        ];
        return count($this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek']) - 1;
    }

    private function addSubRincianObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $keyRincianObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][$keyRincianObjekRekening]['subRincianObjek'][] = [
            'subRincianObjekRekeningId' => $data['subRincianObjekRekeningId'],
            'subRincianObjekRekeningKode' => $data['subRincianObjekRekeningKode'],
            'subRincianObjekRekeningUraian' => $data['subRincianObjekRekeningUraian'],
            'subRincianObjekRekeningJumlah' => 0,
            'ket_sub_dpa' => []
        ];
        return count($this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][$keyRincianObjekRekening]['subRincianObjek']) - 1;
    }

    private function addKetSubDpa($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $keyRincianObjekRekening, $keySubRincianObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][$keyRincianObjekRekening]['subRincianObjek'][$keySubRincianObjekRekening]['ket_sub_dpa'][] = [
            "id" => $data['id'],
            "satuanId" => $data['satuanId'],
            "satuan" => $data['satuan'],
            "uraian" => $data['uraian'],
            "spesifikasi" => $data['spesifikasi'],
            "koefisien" => (int)$data['koefisien'],
            "harga" => (int)$data['harga'],
            "ppn" => (int)$data['ppn'],
            "jumlah" => (int)$data['jumlah'],
        ];
    }


    private function addJumlahAkunRekening($keyAkunRekening, $data)
    {
        $this->newData[$keyAkunRekening]['akunRekeningJumlah'] += $data['jumlah'];
    }

    private function addJumlahKelompokRekening($keyAkunRekening, $keyKelompokRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['kelompokRekeningJumlah'] += $data['jumlah'];
    }

    private function addJumlahJenisRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['jenisRekeningJumlah'] += $data['jumlah'];
    }

    private function addJumlahObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['objekRekeningJumlah'] += $data['jumlah'];
    }

    private function addJumlahRincianObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $keyRincianObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][$keyRincianObjekRekening]['rincianObjekRekeningJumlah'] += $data['jumlah'];
    }

    private function addJumlahSubRincianObjekRekening($keyAkunRekening, $keyKelompokRekening, $keyJenisRekening, $keyObjekRekening, $keyRincianObjekRekening, $keySubRincianObjekRekening, $data)
    {
        $this->newData[$keyAkunRekening]['kelompok'][$keyKelompokRekening]['jenis'][$keyJenisRekening]['objek'][$keyObjekRekening]['rincianObjek'][$keyRincianObjekRekening]['subRincianObjek'][$keySubRincianObjekRekening]['subRincianObjekRekeningJumlah'] += $data['jumlah'];
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
     * @throws KetSubDpaNotFoundException
     */
    public function findOneById(int $id)
    {
        $data = $this->h->table('ket_sub_dpa')->select("
            ket_sub_dpa.id, 
            ket_sub_dpa.satuanId,
            satuan.satuan,
            ket_sub_dpa.uraian,
            ket_sub_dpa.spesifikasi,
            ket_sub_dpa.koefisien,
            ket_sub_dpa.harga,
            ket_sub_dpa.ppn,
            ket_sub_dpa.jumlah,

            ket_sub_dpa.subRincianObjekRekeningId,
            sub_rincian_objek_rekening.kode as subRincianObjekRekeningKode,
            sub_rincian_objek_rekening.uraianAkun as subRincianObjekRekeningUraian,

            sub_rincian_objek_rekening.rincianObjekRekeningId,
            rincian_objek_rekening.kode as rincianObjekRekeningKode,
            rincian_objek_rekening.uraianAkun as rincianObjekRekeningUraian,

            rincian_objek_rekening.objekRekeningId,
            objek_rekening.kode as objekRekeningKode,
            objek_rekening.uraianAkun as objekRekeningUraian,

            objek_rekening.jenisRekeningId,
            jenis_rekening.kode as jenisRekeningKode,
            jenis_rekening.uraianAkun as jenisRekeningUraian,

            jenis_rekening.kelompokRekeningId,
            kelompok_rekening.kode as kelompokRekeningKode,
            kelompok_rekening.uraianAkun as kelompokRekeningUraian,

            kelompok_rekening.akunRekeningId,
            akun_rekening.kode as akunRekeningKode,
            akun_rekening.uraianAkun as akunRekeningUraian                                                                          
        ")->join('satuan', 'satuan.id', '=', 'ket_sub_dpa.satuanId')
            ->join('sub_rincian_objek_rekening', 'sub_rincian_objek_rekening.id', '=', 'ket_sub_dpa.subRincianObjekRekeningId')
            ->join('rincian_objek_rekening', 'rincian_objek_rekening.id', '=', 'sub_rincian_objek_rekening.rincianObjekRekeningId')
            ->join('objek_rekening', 'objek_rekening.id', '=', 'rincian_objek_rekening.objekRekeningId')
            ->join('jenis_rekening', 'jenis_rekening.id', '=', 'objek_rekening.jenisRekeningId')
            ->join('kelompok_rekening', 'kelompok_rekening.id', '=', 'jenis_rekening.kelompokRekeningId')
            ->join('akun_rekening', 'akun_rekening.id', '=', 'kelompok_rekening.akunRekeningId')
            ->where('ket_sub_dpa.id', $id)->whereNull('ket_sub_dpa.deleteAt')->one();
        if ($data == NULL) {
            throw new Exception("Rincian belanja tidak ditemukan");
        }

        $return = $data;
        return $return;
    }

    /**
     * @param int $id
     * @return array
     */
    public function findAllBySubDpaId(int $id): array
    {
        $detailKetSubDpaService = $this->c->get(DetailKetSubDpaService::class);

        $return = [];
        $data = $this->h->table('ket_sub_dpa')->select()->where('subDpaId', $id)->whereNull('deleteAt')->get();
        if ($data == NULL) {
            return $return;
        }
        $temp = [];
        foreach ($data as $k => $v) {
            $tempSubDpa = $v;
            $tempSubDpa['DetailKetSubDpa'] = $detailKetSubDpaService->findAllByKetSubDpaId($v['id']);
            $temp[] = $tempSubDpa;
        }
        $return = $temp;
        return $return;
    }


    /**
     * @param KetSubDpa $KetSubDpa
     * @return KetSubDpa
     * @throws KetSubDpaFailedInsertException
     */
    public function create(array $KetSubDpa)
    {
        $checkSubDpa = $this->subDpaService->findOneById((int)$KetSubDpa['subDpaId']);
        if (!$checkSubDpa) {
            throw new SubDpaNotFoundException();
        }

        $checkSubRincianObjekRekening = $this->subRincianObjekRekeningService->findOneById((int)$KetSubDpa['subRincianObjekRekeningId']);
        if (!$checkSubRincianObjekRekening) {
            throw new SubRincianObjekRekeningNotFoundException();
        }

        $checkSatuan = $this->satuanService->findOneById((int)$KetSubDpa['satuanId']);
        if (!$checkSatuan) {
            throw new SatuanNotFoundException();
        }

        $subDpaService = $this->c->get(SubDpaService::class);

        $dataSubDpa = $subDpaService->findOneById((int)$KetSubDpa['subDpaId']);
        $total = 0;

        $dataKetSubDpa = $this->h->table('ket_sub_dpa')->select()->where('subDpaId', (int)$KetSubDpa['subDpaId'])->whereNull('deleteAt')->get();
        if ($dataKetSubDpa != NULL) {
            $totalOnData = array_values(array_column($dataKetSubDpa, 'jumlah'));
            $total = array_sum($totalOnData) + $KetSubDpa['jumlah'];
        }

        if ($dataSubDpa['jumlahAnggaran'] < $total) {
            throw new Exception('Rincian belanja tidak boleh melebihi total pagu');
        }

        try {
            $dataNew = [
                'subDpaId' => $KetSubDpa['subDpaId'],
                'subRincianObjekRekeningId' => $KetSubDpa['subRincianObjekRekeningId'],
                'satuanId' => $KetSubDpa['satuanId'],
                'uraian' => $KetSubDpa['uraian'],
                'spesifikasi' => $KetSubDpa['spesifikasi'] == null ? '-' : $KetSubDpa['spesifikasi'],
                'koefisien' => $KetSubDpa['koefisien'],
                'harga' => $KetSubDpa['harga'],
                'ppn' => $KetSubDpa['ppn'],
                'jumlah' => $KetSubDpa['jumlah'],
                'createAt' => $KetSubDpa['createAt']
            ];
            $insert = $this->h->table('ket_sub_dpa')->insert($dataNew)->execute();
            if ($insert) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception('Gagal menambah rincian belanja');
        }
    }

    /**
     * @param int $id
     * @param array $ketSubDpa
     * @return void
     * @throws KetSubDpaNotFoundException
     * @throws KetSubDpaFailedUpdateException
     */
    public function update(int $id, $KetSubDpa)
    {
        $checkSubDpa = $this->subDpaService->findOneById((int)$KetSubDpa['subDpaId']);
        if (!$checkSubDpa) {
            throw new SubDpaNotFoundException();
        }

        $checkSubRincianObjekRekening = $this->subRincianObjekRekeningService->findOneById((int)$KetSubDpa['subRincianObjekRekeningId']);
        if (!$checkSubRincianObjekRekening) {
            throw new SubRincianObjekRekeningNotFoundException();
        }

        $checkSatuan = $this->satuanService->findOneById((int)$KetSubDpa['satuanId']);
        if (!$checkSatuan) {
            throw new SatuanNotFoundException();
        }

        $subDpaService = $this->c->get(SubDpaService::class);

        $oldKetSubDpa = $this->findOneById($id);
        if ($oldKetSubDpa) {
            $dataKetSubDpa = $dataSubDpa = $subDpaService->findOneById((int)$KetSubDpa['subDpaId']);
            $total = 0;

            $this->h->table('ket_sub_dpa')->select()->where('subDpaId', (int)$KetSubDpa['subDpaId'])->whereNull('deleteAt')->get();
            if ($dataKetSubDpa != NULL) {
                $totalOnData = array_values(array_column($dataKetSubDpa, 'jumlah'));
                $total = (array_sum($totalOnData) - $oldKetSubDpa['jumlah']) + $KetSubDpa['jumlah'];
            }

            if ($dataSubDpa['jumlahAnggaran'] < $total) {
                throw new Exception('Rincian belanja tidak boleh melebihi total pagu');
            }

            try {
                $dataNew = [
                    'subDpaId' => $KetSubDpa['subDpaId'],
                    'subRincianObjekRekeningId' => $KetSubDpa['subRincianObjekRekeningId'],
                    'satuanId' => $KetSubDpa['satuanId'],
                    'uraian' => $KetSubDpa['uraian'],
                    'spesifikasi' => $KetSubDpa['spesifikasi'] == null ? '-' : $KetSubDpa['spesifikasi'],
                    'koefisien' => $KetSubDpa['koefisien'],
                    'harga' => $KetSubDpa['harga'],
                    'ppn' => $KetSubDpa['ppn'],
                    'jumlah' => $KetSubDpa['jumlah'],
                    'updateAt' => $KetSubDpa['updateAt']
                ];
                $update = $this->h->table('ket_sub_dpa')->update($dataNew)->where('id', $id)->execute();
                if ($update) {
                    return true;
                }
            } catch (Exception $e) {
                throw new Exception('Gagal mengubah rincian belanja');
            }
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws KetSubDpaNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldKetSubDpa = $this->findOneById($id);
        if ($oldKetSubDpa) {
            try {
                $delete = $this->h->table('ket_sub_dpa')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new KetSubDpaFailedDeleteException();
            }
        } else {
            throw new KetSubDpaNotFoundException();
        }
    }
}
