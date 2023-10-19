<?php

declare(strict_types=1);

namespace App\Service\Tenant\Pembangunan;

use Exception;
use App\Service\Table;
use Psr\Container\ContainerInterface;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpa;
use App\Service\SumberDana\SumberDanaService;
use App\Application\Database\DatabaseInterface;
use App\Application\Database\DatabaseTenantInterface;
use App\Domain\SumberDana\SumberDanaNotFoundException;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpaRepository;
use App\Domain\Tenant\Pembangunan\Dpa\DpaNotFoundException;
use App\Service\Perencanaan\SubKegiatan\SubKegiatanService;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanRepository;
use App\Domain\Tenant\Pembangunan\KetSubDpa\KetSubDpaRepository;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpaNotFoundException;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpaFailedInsertException;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpaFailedUpdateException;
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
        $table = $this->h->table('sub_dpa_pembangunan')->select("
            sub_dpa_pembangunan.id, 
            sub_dpa_pembangunan.ppk, 
            sub_dpa_pembangunan.jumlahAnggaran,
            sub_dpa_pembangunan.jenisPengadaan,
            sub_dpa_pembangunan.mekanismePengadaan,

            sub_dpa_pembangunan.swakelola,
            sub_dpa_pembangunan.noKontrak,
            sub_dpa_pembangunan.judulKontrak,
            sub_dpa_pembangunan.nilaiKontrak,
            sub_dpa_pembangunan.tanggalKontrak,

            sub_dpa_pembangunan.tanggalMulai,
            sub_dpa_pembangunan.tanggalSelesai,
            sub_dpa_pembangunan.pelaksana,
            sub_dpa_pembangunan.lokasi,
            sub_dpa_pembangunan.kendala,

            sub_dpa_pembangunan.tenagaKerja,
            sub_dpa_pembangunan.penerapanK3,
            sub_dpa_pembangunan.keterangan,
            sub_dpa_pembangunan.informasi,

            sub_dpa_pembangunan.dpaPembangunanId,
            sub_dpa_pembangunan.subKegiatanId,
            sub_dpa_pembangunan.sumberDanaId,
            sumber_dana.sumberDana,

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
            ->join('dpa_pembangunan', 'dpa_pembangunan.id', '=', 'sub_dpa_pembangunan.dpaPembangunanId')
            ->join('tahun', 'tahun.id', '=', 'dpa_pembangunan.tahunId')
            ->join('dinas', 'dinas.id', '=', 'dpa_pembangunan.dinasId')
            ->join('sumber_dana', 'sumber_dana.id', '=', 'sub_dpa_pembangunan.sumberDanaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa_pembangunan.subKegiatanId')
            ->join('kegiatan', 'kegiatan.id', '=', 'sub_kegiatan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->whereNull('sub_dpa_pembangunan.deleteAt');

        if (isset($options['dpaPembangunanId']) && $options['dpaPembangunanId'] != "" && $options['dpaPembangunanId'] != "0") {
            $table = $table->where('sub_dpa_pembangunan.dpaPembangunanId', $options['dpaPembangunanId']);
        }

        if (isset($options['dinasId']) && $options['dinasId'] != "" && $options['dinasId'] != "0") {
            $table = $table->where('dpa_pembangunan.dinasId', $options['dinasId']);
        }

        if (isset($options['tahunId']) && $options['tahunId'] != "" && $options['tahunId'] != "0") {
            $table = $table->where('dpa_pembangunan.tahunId', $options['tahunId']);
        }
        $dataTable = new Table($table, columnOrder: [
            "sub_dpa_pembangunan.ppk",
            "sub_dpa_pembangunan.jumlahAnggaran",
            "sub_dpa_pembangunan.jenisPengadaan",
            "sub_dpa_pembangunan.mekanismePengadaan",

            "sub_dpa_pembangunan.swakelola",
            "sub_dpa_pembangunan.noKontrak",
            "sub_dpa_pembangunan.judulKontrak",
            "sub_dpa_pembangunan.nilaiKontrak",
            "sub_dpa_pembangunan.tanggalKontrak",

            "sub_dpa_pembangunan.tanggalMulai",
            "sub_dpa_pembangunan.tanggalSelesai",
            "sub_dpa_pembangunan.pelaksana",
            "sub_dpa_pembangunan.lokasi",
            "sub_dpa_pembangunan.kendala",

            "sub_dpa_pembangunan.tenagaKerja",
            "sub_dpa_pembangunan.penerapanK3",
            "sub_dpa_pembangunan.keterangan",
            "sub_dpa_pembangunan.informasi",

            "sub_dpa_pembangunan.dpaPembangunanId",
            "sub_dpa_pembangunan.subKegiatanId",
            "sub_dpa_pembangunan.sumberDanaId",
            "sumber_dana.sumberDana",
            "sub_kegiatan.kode",
            "sub_kegiatan.nomenklatur"
        ], columnSearch: [
            "sub_dpa_pembangunan.ppk",
            "sub_dpa_pembangunan.jumlahAnggaran",
            "sub_dpa_pembangunan.jenisPengadaan",
            "sub_dpa_pembangunan.mekanismePengadaan",

            "sub_dpa_pembangunan.swakelola",
            "sub_dpa_pembangunan.noKontrak",
            "sub_dpa_pembangunan.judulKontrak",
            "sub_dpa_pembangunan.nilaiKontrak",
            "sub_dpa_pembangunan.tanggalKontrak",

            "sub_dpa_pembangunan.tanggalMulai",
            "sub_dpa_pembangunan.tanggalSelesai",
            "sub_dpa_pembangunan.pelaksana",
            "sub_dpa_pembangunan.lokasi",
            "sub_dpa_pembangunan.kendala",

            "sub_dpa_pembangunan.tenagaKerja",
            "sub_dpa_pembangunan.penerapanK3",
            "sub_dpa_pembangunan.keterangan",
            "sub_dpa_pembangunan.informasi",

            "sub_dpa_pembangunan.dpaPembangunanId",
            "sub_dpa_pembangunan.subKegiatanId",
            "sub_dpa_pembangunan.sumberDanaId",
            "sumber_dana.sumberDana",
            "sub_kegiatan.kode",
            "sub_kegiatan.nomenklatur"
        ]);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return void
     * @throws SubDpaNotFoundException
     */
    public function findOneById(int $id)
    {
        $data = $this->h->table('sub_dpa_pembangunan')->select("
            sub_dpa_pembangunan.id, 
            sub_dpa_pembangunan.ppk, 
            sub_dpa_pembangunan.jumlahAnggaran,
            sub_dpa_pembangunan.jenisPengadaan,
            sub_dpa_pembangunan.mekanismePengadaan,

            sub_dpa_pembangunan.swakelola,
            sub_dpa_pembangunan.noKontrak,
            sub_dpa_pembangunan.judulKontrak,
            sub_dpa_pembangunan.nilaiKontrak,
            sub_dpa_pembangunan.tanggalKontrak,

            sub_dpa_pembangunan.tanggalMulai,
            sub_dpa_pembangunan.tanggalSelesai,
            sub_dpa_pembangunan.pelaksana,
            sub_dpa_pembangunan.lokasi,
            sub_dpa_pembangunan.kendala,

            sub_dpa_pembangunan.tenagaKerja,
            sub_dpa_pembangunan.penerapanK3,
            sub_dpa_pembangunan.keterangan,
            sub_dpa_pembangunan.informasi,

            sub_dpa_pembangunan.dpaPembangunanId,
            sub_dpa_pembangunan.subKegiatanId,
            sub_dpa_pembangunan.sumberDanaId,
            sumber_dana.sumberDana,

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
            ->join('dpa_pembangunan', 'dpa_pembangunan.id', '=', 'sub_dpa_pembangunan.dpaPembangunanId')
            ->join('tahun', 'tahun.id', '=', 'dpa_pembangunan.tahunId')
            ->join('dinas', 'dinas.id', '=', 'dpa_pembangunan.dinasId')
            ->join('sumber_dana', 'sumber_dana.id', '=', 'sub_dpa_pembangunan.sumberDanaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa_pembangunan.subKegiatanId')
            ->join('kegiatan', 'kegiatan.id', '=', 'sub_kegiatan.kegiatanId')
            ->join('program', 'program.id', '=', 'kegiatan.programId')
            ->where('sub_dpa_pembangunan.id', $id)->whereNull('sub_dpa_pembangunan.deleteAt')->one();
        if ($data == NULL) {
            throw new Exception("Sub kegiatan tidak ditemukan");
        }

        $return = $data;
        return $return;
    }


    /**
     * @param array $SubDpa
     * @return array
     * @throws SubDpaFailedInsertException
     */
    public function create(array $SubDpa)
    {
        $checkDpa = $this->dpaService->findOneById((int)$SubDpa['dpaPembangunanId']);

        $checkSumberDana = $this->sumberDanaService->findOneById((int)$SubDpa['sumberDanaId']);
        if (!$checkSumberDana) {
            throw new SumberDanaNotFoundException();
        }

        $checkSubKegiatan = $this->subKegiatanService->findOneById((int)$SubDpa['subKegiatanId']);
        if (!$checkSubKegiatan) {
            throw new SubKegiatanNotFoundException();
        }

        $dataSubDpa = [
            'dpaPembangunanId' => $SubDpa['dpaPembangunanId'],
            'sumberDanaId' => $SubDpa['sumberDanaId'],
            'subKegiatanId' => $SubDpa['subKegiatanId'],
            'jumlahAnggaran' => $SubDpa['jumlahAnggaran'],
            'createAt' => $SubDpa['createAt'],
        ];

        $total = $checkDpa['jumlahTerAlokasi'] + $dataSubDpa['jumlahAnggaran'];
        if ($checkDpa['jumlahAlokasi'] < $total) {
            throw new Exception('Jumlah alokasi tidak cukup');
        }

        try {
            $this->conn->beginTransaction();
            $insert = $this->h->table('sub_dpa_pembangunan')->insert($dataSubDpa)->execute();

            $this->conn->commit();

            return $this->findOneById((int)$insert);
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Gagal menambah sub kegiatan");
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
        $checkDpa = $this->dpaService->findOneById((int)$SubDpa['dpaPembangunanId']);

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
                'dpaPembangunanId' => $SubDpa['dpaPembangunanId'],
                'sumberDanaId' => $SubDpa['sumberDanaId'],
                'subKegiatanId' => $SubDpa['subKegiatanId'],
                'jumlahAnggaran' => $SubDpa['jumlahAnggaran'],
                'updateAt' => $SubDpa['updateAt'],
            ];

            $total = ($checkDpa['jumlahTerAlokasi'] - $oldSubDpa['jumlahAnggaran']) + $dataSubDpa['jumlahAnggaran'];
            if ($checkDpa['jumlahAlokasi'] < $total) {
                throw new Exception('Jumlah alokasi tidak cukup');
            }

            try {
                $this->conn->beginTransaction();
                $this->h->table('sub_dpa_pembangunan')->update($dataSubDpa)->where('id', $id)->execute();

                $this->conn->commit();

                return $this->findOneById((int)$id);
            } catch (Exception $e) {
                $this->conn->rollBack();
                throw new Exception("Gagal mengubah sub kegiatan");
            }
        } else {
            throw new Exception("Sub kegiatan tidak ditemukan");
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
                $delete = $this->h->table('sub_dpa_pembangunan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new Exception("Gagal menghapus sub kegiatan");
            }
        } else {
            throw new Exception("Sub kegiatan tidak ditemukan");
        }
    }
}
