<?php

declare(strict_types=1);

namespace App\Service\Tenant\Anggaran;

use Exception;
use App\Service\Satuan\SatuanService;
use Psr\Container\ContainerInterface;
use App\Domain\Satuan\SatuanRepository;
use App\Domain\Satuan\SatuanNotFoundException;
use App\Service\Tenant\Anggaran\KetSubDpaService;
use App\Application\Database\DatabaseTenantInterface;
use App\Domain\Tenant\Anggaran\SubDpa\JumlahAnggaranException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpa;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaRepository;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedDeleteException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedUpdateException;

class DetailKetSubDpaService implements DetailKetSubDpaRepository
{
    private $h;
    private $c;
    private KetSubDpaService $ketSubDpaService;
    private SatuanService $satuanService;


    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $database = $c->get(DatabaseTenantInterface::class);
        $ketSubDpaService = $c->get(KetSubDpaService::class);
        $satuanService = $c->get(SatuanService::class);

        $this->h = $database->h();
        $this->ketSubDpaService = $ketSubDpaService;
        $this->satuanService = $satuanService;
    }

    /**
     * @param array|null $options
     * @return DetailKetSubDpa[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('detail_ket_sub_dpa')->select("
            detail_ket_sub_dpa.id, 
            detail_ket_sub_dpa.uraian, 
            detail_ket_sub_dpa.spesifikasi, 
            detail_ket_sub_dpa.koefisien, 
            detail_ket_sub_dpa.harga, 
            detail_ket_sub_dpa.ppn, 
            detail_ket_sub_dpa.jumlah, 
            detail_ket_sub_dpa.ketSubDpaId, 
            detail_ket_sub_dpa.satuanId,
            satuan.satuan as satuan
        ")
        ->join('satuan', 'satuan.id', '=', 'detail_ket_sub_dpa.satuanId');
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
            $table = $table->where('satuan', 'like', "%$search%");
            $table = $table->orWhere('uraian', 'like', "%$search%");
            $table = $table->orWhere('spesifikasi', 'like', "%$search%");
            $table = $table->orWhere('koefisien', 'like', "%$search%");
            $table = $table->orWhere('harga', 'like', "%$search%");
            $table = $table->orWhere('ppn', 'like', "%$search%");
            $table = $table->orWhere('jumlah', 'like', "%$search%");
        } else {
            $search = "";
        }
        $dataDetailKetSubDpa = [];
        $data = $table->whereNull('detail_ket_sub_dpa.deleteAt')->get();
        foreach ($data as $k) {
            $noDetailKetSubDpa = [
                "id" => $k['id'],
                "satuan" => $k['satuan'],
                "uraian" => $k['uraian'],
                "spesifikasi" => $k['spesifikasi'],
                "koefisien" => $k['koefisien'],
                "harga" => $k['harga'],
                "ppn" => $k['ppn'],
                "jumlah" => $k['jumlah'],
            ];
            $dataDetailKetSubDpa[] = $noDetailKetSubDpa;
        }
        return [
            'detailKetSubDpa' => $dataDetailKetSubDpa,
            'page' => $offset,
            'limit' => $limit,
            'search' => $search,
        ];
    }


    /**
     * @param int $id
     * @return void
     * @throws DetailKetSubDpaNotFoundException
     */
    public function findOneById(int $id)
    {
        $data = $this->h->table('detail_ket_sub_dpa')->select("
            detail_ket_sub_dpa.id, 
            detail_ket_sub_dpa.uraian, 
            detail_ket_sub_dpa.spesifikasi, 
            detail_ket_sub_dpa.koefisien, 
            detail_ket_sub_dpa.harga, 
            detail_ket_sub_dpa.ppn, 
            detail_ket_sub_dpa.jumlah, 
            detail_ket_sub_dpa.ketSubDpaId, 
            detail_ket_sub_dpa.satuanId,
            satuan.satuan as satuan
        ")
            ->join('satuan', 'satuan.id', '=', 'detail_ket_sub_dpa.satuanId')
            ->where('detail_ket_sub_dpa.id', $id)->whereNull('detail_ket_sub_dpa.deleteAt')->one();
        if ($data == NULL) {
            throw new DetailKetSubDpaNotFoundException();
        }

        $return = $data;
        return $return;
    }

    /**
     * @param int $id
     * @return array
     */
    public function findAllByKetSubDpaId(int $id): array
    {
        $return = [];
        $data = $this->h->table('detail_ket_sub_dpa')->select()->where('ketSubDpaId', $id)->whereNull('deleteAt')->get();
        if ($data == NULL) {
            return $return;
        }
        $return = $data;
        return $return;
    }

    /**
     * @param DetailKetSubDpa $DetailKetSubDpa
     * @return DetailKetSubDpa
     * @throws DetailKetSubDpaFailedInsertException
     */
    public function create(DetailKetSubDpa $DetailKetSubDpa): DetailKetSubDpa
    {
        $checkKetSubDpa = $this->ketSubDpaService->findOneById($DetailKetSubDpa->ketSubDpaId);
        if(!$checkKetSubDpa){
            throw new KetSubDpaNotFoundException();
        }

        $checkSatuan = $this->satuanService->findOneById($DetailKetSubDpa->satuanId);
        if(!$checkSatuan){
            throw new SatuanNotFoundException();
        }

        $dataDetailKetSubDpa = $this->findAllByKetSubDpaId($DetailKetSubDpa->ketSubDpaId);
        $total = 0;
        if($dataDetailKetSubDpa != NULL){
            $totalOnData = array_values(array_column($dataDetailKetSubDpa, 'jumlah'));
            $total = array_sum($totalOnData) + $DetailKetSubDpa->jumlah;
        }

        if($checkKetSubDpa['jumlahAnggaran'] < $total){
            throw new JumlahAnggaranException();
        }

        try {
            $insert = $this->h->table('detail_ket_sub_dpa')->insert($DetailKetSubDpa->toArray())->execute();
            if ($insert) {
                $DetailKetSubDpa->id = (int)$insert;
                return $DetailKetSubDpa;
            }
        } catch (Exception $e) {
            throw new DetailKetSubDpaFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param DetailKetSubDpa $detailKetSubDpa
     * @return void
     * @throws DetailKetSubDpaNotFoundException
     * @throws DetailKetSubDpaFailedUpdateException
     */
    public function update(int $id, DetailKetSubDpa $detailKetSubDpa)
    {
        $checkKetSubDpa = $this->ketSubDpaService->findOneById($detailKetSubDpa->ketSubDpaId);
        if(!$checkKetSubDpa){
            throw new KetSubDpaNotFoundException();
        }

        $checkSatuan = $this->satuanService->findOneById($detailKetSubDpa->satuanId);
        if(!$checkSatuan){
            throw new SatuanNotFoundException();
        }
        $oldDetailKetSubDpa = $this->findOneById($id);
        if ($oldDetailKetSubDpa) {
            $dataDetailKetSubDpa = $this->findAllByKetSubDpaId($detailKetSubDpa->ketSubDpaId);
            $total = 0;
            if($dataDetailKetSubDpa != NULL){
                $totalOnData = array_values(array_column($dataDetailKetSubDpa, 'jumlah'));
                $total = (array_sum($totalOnData) - $oldDetailKetSubDpa['jumlah']) + $detailKetSubDpa->jumlah;
            }
    
            if($checkKetSubDpa['jumlahAnggaran'] < $total){
                throw new JumlahAnggaranException();
            }


            try {
                $update = $this->h->table('detail_ket_sub_dpa')->update(array_filter($detailKetSubDpa->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new DetailKetSubDpaFailedUpdateException();
            }
        } else {
            throw new DetailKetSubDpaNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws DetailKetSubDpaNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldDetailKetSubDpa = $this->findOneById($id);
        if ($oldDetailKetSubDpa) {
            try {
                $delete = $this->h->table('detail_ket_sub_dpa')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new DetailKetSubDpaFailedDeleteException();
            }
        } else {
            throw new DetailKetSubDpaNotFoundException();
        }
    }
}
