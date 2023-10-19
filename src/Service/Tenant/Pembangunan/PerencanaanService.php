<?php

declare(strict_types=1);

namespace App\Service\Tenant\Pembangunan;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\KomponenPembangunan\KomponenPembangunan;
use App\Domain\KomponenPembangunan\KomponenPembangunanRepository;
use App\Domain\Tenant\Pembangunan\Perencanaan\Perencanaan;
use App\Domain\Tenant\Pembangunan\Perencanaan\PerencanaanRepository;
use ClanCats\Hydrahon\Query\Expression as Ex;

class PerencanaanService implements PerencanaanRepository
{
    public $h;
    public $c;


    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    public function findAll(?array $options): array
    {
        $table = $this->h->table('detail_ket_sub_dpa')
            ->select("
                detail_ket_sub_dpa.id, 
                detail_ket_sub_dpa.uraian, 
                detail_ket_sub_dpa.jumlah,
                sub_kegiatan.kode,
                sub_kegiatan.nomenklatur
            ")
            ->join('satuan', 'satuan.id', '=', 'detail_ket_sub_dpa.satuanId')
            ->join('ket_sub_dpa', 'ket_sub_dpa.id', '=', 'detail_ket_sub_dpa.ketSubDpaId')
            ->join('sub_dpa', 'sub_dpa.id', '=', 'ket_sub_dpa.subDpaId')
            ->join('sub_kegiatan', 'sub_kegiatan.id', '=', 'sub_dpa.subKegiatanId')
            ->join('dpa', 'dpa.id', '=', 'sub_dpa.dpaId')
            ->join('tahun', 'tahun.id', '=', 'dpa.tahunId')
            ->whereNull('detail_ket_sub_dpa.deleteAt');
        if (isset($options['tahunId'])) {
            $table = $table->where('tahun.id', $options['tahunId']);
        } else {
            $table = $table->where('tahun.active', 1);
        }
        if (isset($options['limit']) && $options['limit'] > 0) {
            $limit = $options['limit'];
            $table = $table->limit($limit);
        } else {
            $limit = 10;
            $table = $table->limit(10);
        }
        if (isset($options['page']) && $options['page'] > 0) {
            $offset = $options['page'];
            $table = $table->offset($offset - 1);
        } else {
            $offset = 1;
            $table = $table->offset(0);
        }
        if (isset($options['search'])) {
            $search = $options['search'];
            $table = $table->where('detail_ket_sub_dpa.uraian', 'like', "%$search%");
            $table = $table->orWhere('sub_kegiatan.kode', 'like', "%$search%");
            $table = $table->orWhere('sub_kegiatan.nomenklatur', 'like', "%$search%");
        } else {
            $search = "";
        }
        $rencana = [];
        $data = $table->get();
        foreach ($data as $k) {
            $rencana[] = [
                "id" => $k['id'],
                "uraian" => $k['uraian'],
                "jumlah" => $k['jumlah'],
                "kode" => $k['kode'],
                "nomenklatur" => $k['nomenklatur'],
            ];
        }
        return [
            'rencana' => $rencana,
            'page' => $offset,
            'limit' => $limit,
            'search' => $search,
        ];
    }

    public function findOneByDetailKetSubDpaId(int $id)
    {
        $data = $this->h->table('rencana_pembangunan')->select("")
            ->where('rencana_pembangunan.id', $id)
            ->whereNull('rencana_pembangunan.deleteAt')
            ->one();
        if ($data == NULL) {
            return [];
        }
        $return = $data;
        $return["keselamatanKontruksi"] = ($data['keselamatanKontruksi'] != NULL ? json_decode($data['keselamatanKontruksi'], TRUE) : NULL);
        $return["catatan"] = ($data['catatan'] != NULL ? json_decode($data['catatan'], TRUE) : NULL);
        $return["timMonitoring"] = ($data['timMonitoring'] != NULL ? json_decode($data['timMonitoring'], TRUE) : NULL);

        $komponen = $this->h->table('komponen_pembangunan')->select()->whereNull('komponen_pembangunan.deleteAt')->get();
        $pembangunan = $this->h->table('detail_rencana_pembangunan')->select(
            [
                new Ex('*,detail_rencana_pembangunan.id')
            ]
        )
            ->join('komponen_pembangunan', 'komponen_pembangunan.id', '=', 'detail_rencana_pembangunan.komponenPembangunanId')
            ->where('rencanaPembangunanId', $data['id'])
            ->whereNull('detail_rencana_pembangunan.deleteAt')->get();

        function buildTree(array $elements, array $pembangunan, $parentId = null)
        {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['parentId'] == $parentId) {
                    $children = buildTree($elements, $pembangunan, $element['id']);
                    if ($children) {
                        $temp = [];
                        foreach ($children as $k => $v) {
                            $tempChildren = $v;
                            $tempChildren['pembangunan'] = [];
                            foreach ($pembangunan as $p => $w) {
                                if ($v['id'] == $w['komponenPembangunanId']) {
                                    $tempChildren['pembangunan'][] = $w;
                                }
                            }
                            $temp[] = $tempChildren;
                        }
                        $element['children'] = $temp;
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        }

        $return['detail'] = $pembangunan;
        $return['detailTree'] = buildTree($komponen, $pembangunan);
        return $return;
    }

    public function createORupdate(Perencanaan $rencana)
    {
        $check = $this->h->table('rencana_pembangunan')
            ->select()
            ->where('detailKetSubDpaId', $rencana->detailKetSubDpaId)
            ->whereNull('rencana_pembangunan.deleteAt')
            ->one();
        if ($check != NULL) {
            //update
            try {
                $rencana->updateAt = (string)date('Y-m-d H:i:s');
                $update = $this->h->table('rencana_pembangunan')->update(array_filter($rencana->toArray()))->where('id', $check['id'])->execute();
                if ($update) {
                    return $this->findOneByDetailKetSubDpaId($check['detailKetSubDpaId']);
                }
            } catch (Exception $e) {
                throw new Exception("Gagal memperbarui data");
            }
        } else {
            //create
            try {
                $rencana->createAt = (string)date('Y-m-d H:i:s');
                $insert = $this->h->table('rencana_pembangunan')->insert($rencana->toArray())->execute();
                if ($insert) {
                    return $this->findOneByDetailKetSubDpaId((int)$insert);
                }
            } catch (Exception $e) {
                throw new Exception("Gagal memperbarui data");
            }
        }
    }

    public function addKomponen(int $rencanaPembangunanId, int $komponenPembangunanId)
    {
        $komponenRepository = $this->c->get(KomponenPembangunanRepository::class);
        $checkKomponen = $komponenRepository->findOneById($komponenPembangunanId);
        if (!$checkKomponen) {
            throw new Exception('Komponen pembangunan tidak ditemukan');
        }

        $checkRencanaPembangunan = $this->h->table('rencana_pembangunan')->select("")
            ->where('rencana_pembangunan.id', $rencanaPembangunanId)
            ->whereNull('rencana_pembangunan.deleteAt')
            ->one();
        if (!$checkRencanaPembangunan) {
            throw new Exception('Rencana pembangunan tidak ditemukan');
        }

        try {
            $rencana = [
                'rencanaPembangunanId' => $rencanaPembangunanId,
                'komponenPembangunanId' => $komponenPembangunanId
            ];
            $rencana['createAt'] = (string)date('Y-m-d H:i:s');
            $insert = $this->h->table('detail_rencana_pembangunan')->insert($rencana)->execute();
            if ($insert) {
                return $this->findOneByDetailKetSubDpaId((int)$checkRencanaPembangunan['detailKetSubDpaId']);
            }
        } catch (Exception $e) {
            throw new Exception("Gagal menambah komponen pembangunan");
        }
    }

    public function update(int $id, $rencana)
    {
        $check = $this->h->table('sub_dpa_pembangunan')
            ->select()
            ->where('id', $id)
            ->whereNull('sub_dpa_pembangunan.deleteAt')
            ->one();
        if(!$check){
            throw new Exception("Data umum tidak ditemukan");
        }

        try {
            $update = $this->h->table('sub_dpa_pembangunan')->update($rencana)->where('id', $check['id'])->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function updateBlanko(int $id, array $blanko)
    {
        $check = $this->h->table('rencana_pembangunan')
            ->select()
            ->where('id', $id)
            ->whereNull('rencana_pembangunan.deleteAt')
            ->one();
        if(!$check){
            throw new Exception("Rencana pembangunan tidak ditemukan");
        }

        try {
            $blanko['updateAt'] = (string)date('Y-m-d H:i:s');
            $update = $this->h->table('detail_rencana_pembangunan')->update(array_filter($blanko))->where('id', $id)->execute();
            if ($update) {
                return $this->findOneRunning($id);
            }
        } catch (Exception $e) {
            throw new Exception("Gagal memperbarui data");
        }

    }

    public function findAllRunning(?array $options): array
    {
        $table = $this->h->table('rencana_pembangunan')
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
        if (isset($options['limit']) && $options['limit'] > 0) {
            $limit = $options['limit'];
            $table = $table->limit($limit);
        } else {
            $limit = 10;
            $table = $table->limit(10);
        }
        if (isset($options['page']) && $options['page'] > 0) {
            $offset = $options['page'];
            $table = $table->offset($offset - 1);
        } else {
            $offset = 1;
            $table = $table->offset(0);
        }
        if (isset($options['search'])) {
            $search = $options['search'];
            $table = $table->where('detail_ket_sub_dpa.uraian', 'like', "%$search%");
            $table = $table->orWhere('sub_kegiatan.kode', 'like', "%$search%");
            $table = $table->orWhere('sub_kegiatan.nomenklatur', 'like', "%$search%");
        } else {
            $search = "";
        }
        $rencana = [];
        $data = $table->get();
        foreach ($data as $k) {
            $detail = $this->h->table('detail_rencana_pembangunan')->select()
                ->addFieldSum('persentase', 'totalPersen')
                ->addFieldCount('persentase', 'total')
                ->where('rencanaPembangunanId', $k['id'])
                ->whereNull('detail_rencana_pembangunan.deleteAt')
                ->one();

            if ($detail != NULL) {
                $totalPersen = ($detail['total'] * 100);
                $persen = ($detail['totalPersen'] / $totalPersen) * 100;
            } else {
                $persen = 0;
            }

            $rencana[] = [
                "id" => $k['id'],
                "detailKetSubDpaId" => $k['detailKetSubDpaId'],
                "nilaiKontrak" => $k['nilaiKontrak'],
                "nomorKontrak" => $k['nomorKontrak'],
                "tanggalKontrak" => $k['tanggalKontrak'],
                "pejabatPpk" => $k['pejabatPpk'],
                "pelaksana" => $k['pelaksana'],
                "lokasiRealisasiAnggaran" => $k['lokasiRealisasiAnggaran'],
                "jangkaWaktu" => $k['jangkaWaktu'],
                "mulaiKerja" => $k['mulaiKerja'],
                "kendalaHambatan" => $k['kendalaHambatan'],
                "tenagaTerja" => $k['tenagaTerja'],
                "penerapanK3" => $k['penerapanK3'],
                "keterangan" => $k['keterangan'],
                "progressPelaksanaan" => $k['progressPelaksanaan'],
                "rencanaPelaksanaan" => $k['rencanaPelaksanaan'],
                "realisasiPelaksanaan" => $k['realisasiPelaksanaan'],
                "deviasiPelaksanaan" => $k['deviasiPelaksanaan'],
                "keselamatanKontruksi" => ($k['keselamatanKontruksi'] != NULL ? json_decode($k['keselamatanKontruksi'], TRUE) : NULL),
                "catatan" => ($k['catatan'] != NULL ? json_decode($k['catatan'], TRUE) : NULL),
                "timMonitoring" => ($k['timMonitoring'] != NULL ? json_decode($k['timMonitoring'], TRUE) : NULL),
                "persentase" => $persen
            ];
        }
        return [
            'rencana' => $rencana,
            'page' => $offset,
            'limit' => $limit,
            'search' => $search,
        ];
    }

    public function findOneRunning(int $id)
    {
        $data = $this->h->table('rencana_pembangunan')->select("")
            ->where('rencana_pembangunan.detailKetSubDpaId', $id)
            ->whereNull('rencana_pembangunan.deleteAt')
            ->one();
        if ($data == NULL) {
            return [];
        }
        $return = $data;
        $return["keselamatanKontruksi"] = ($data['keselamatanKontruksi'] != NULL ? json_decode($data['keselamatanKontruksi'], TRUE) : NULL);
        $return["catatan"] = ($data['catatan'] != NULL ? json_decode($data['catatan'], TRUE) : NULL);
        $return["timMonitoring"] = ($data['timMonitoring'] != NULL ? json_decode($data['timMonitoring'], TRUE) : NULL);

        $komponen = $this->h->table('komponen_pembangunan')->select()->whereNull('komponen_pembangunan.deleteAt')->get();
        $pembangunan = $this->h->table('detail_rencana_pembangunan')->select(
            [
                new Ex('*,detail_rencana_pembangunan.id')
            ]
        )
            ->join('komponen_pembangunan', 'komponen_pembangunan.id', '=', 'detail_rencana_pembangunan.komponenPembangunanId')
            ->where('rencanaPembangunanId', $data['id'])
            ->whereNull('detail_rencana_pembangunan.deleteAt')->get();

        $newPembangunan = [];
        foreach ($pembangunan as $k => $v) {
            $tempPembangunan = $v;
            $tempPembangunan['dokumentasi'] = $this->h->table('dokumentasi_pekerjaan_pembangunan')->select()->where('detailRencanaPembangunanId', $v['id'])->whereNull('dokumentasi_pekerjaan_pembangunan.deleteAt')->get();
            $newPembangunan[] = $tempPembangunan;
        }

        $pembangunan = $newPembangunan;

        function buildTreeRunning(array $elements, array $pembangunan, $parentId = null)
        {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['parentId'] == $parentId) {
                    $children = buildTreeRunning($elements, $pembangunan, $element['id']);
                    if ($children) {
                        $temp = [];
                        foreach ($children as $k => $v) {
                            $tempChildren = $v;
                            $tempChildren['pembangunan'] = [];
                            foreach ($pembangunan as $p => $w) {
                                if ($v['id'] == $w['komponenPembangunanId']) {
                                    $tempChildren['pembangunan'][] = $w;
                                }
                            }
                            $temp[] = $tempChildren;
                        }
                        $element['children'] = $temp;
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        }

        $return['detail'] = $pembangunan;
        $return['detailTree'] = buildTreeRunning($komponen, $pembangunan);
        return $return;
    }
}
