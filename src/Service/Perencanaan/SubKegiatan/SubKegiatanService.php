<?php

declare(strict_types=1);

namespace App\Service\Perencanaan\SubKegiatan;

use Exception;
use App\Service\Satuan\SatuanService;
use Psr\Container\ContainerInterface;
use App\Domain\Satuan\SatuanNotFoundException;
use App\Application\Database\DatabaseInterface;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatan;
use App\Service\Perencanaan\Kegiatan\KegiatanService;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanRepository;
use App\Domain\Perencanaan\Kegiatan\KegiatanNotFoundException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanNotFoundException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanFailedDeleteException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanFailedInsertException;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanFailedUpdateException;
use App\Service\Table;

class SubKegiatanService implements SubKegiatanRepository
{
    private $h;
    private KegiatanService $kegiatanService;
    private SatuanService $satuanService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $kegiatanService = $c->get(KegiatanService::class);
        $satuanService = $c->get(SatuanService::class);

        $this->h = $database->h();
        $this->kegiatanService = $kegiatanService;
        $this->satuanService = $satuanService;
    }

    /**
     * @param array|null $options
     * @return SubKegiatan[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('sub_kegiatan')->select("sub_kegiatan.id, kegiatanId, sub_kegiatan.kode, sub_kegiatan.nomenklatur, sub_kegiatan.satuanId, satuan.satuan, sub_kegiatan.kinerja, sub_kegiatan.indikator")->join('kegiatan','kegiatan.id','=','sub_kegiatan.kegiatanId')->join('satuan','satuan.id','=','sub_kegiatan.satuanId')->whereNull('sub_kegiatan.deleteAt');
        if (isset($options['kegiatanId']) && $options['kegiatanId'] != 0) {
            $table = $table->where('kegiatanId', '=', $options['kegiatanId']);
        }
        $dataTable = new Table($table,columnOrder: ['sub_kegiatan.id','sub_kegiatan.kegiatanId','sub_kegiatan.satuanId','sub_kegiatan.kode','sub_kegiatan.nomenklatur','sub_kegiatan.kinerja','sub_kegiatan.indikator','satuan.satuan'], columnSearch: ['sub_kegiatan.kegiatanId','sub_kegiatan.satuanId','sub_kegiatan.kode','sub_kegiatan.nomenklatur','sub_kegiatan.kinerja','sub_kegiatan.indikator','satuan.satuan']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return SubKegiatan
     * @throws SubKegiatanNotFoundException
     */
    public function findOneById(int $id): SubKegiatan
    {
        $subKegiatan = new SubKegiatan();

        $data = $this->h->table('sub_kegiatan')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new SubKegiatanNotFoundException();
        }

        $return = $subKegiatan->fromArray($data);

        return $return;
    }

    /**
     * @param SubKegiatan $subKegiatan
     * @return SubKegiatan
     * @throws SubKegiatanFailedInsertException
     * @throws KegiatanNotFoundException
     */
    public function create(SubKegiatan $subKegiatan): SubKegiatan
    {
        $kegiatan = $this->kegiatanService->findOneById($subKegiatan->kegiatanId);
        if ($kegiatan) {
            $satuan = $this->satuanService->findOneById($subKegiatan->satuanId);
            if ($satuan) {
                try {
                    $insert = $this->h->table('sub_kegiatan')->insert($subKegiatan->toArray())->execute();
                    if ($insert) {
                        $subKegiatan->id = (int)$insert;
                        return $subKegiatan;
                    }
                } catch (Exception $e) {
                    throw new SubKegiatanFailedInsertException();
                }
            } else {
                throw new SatuanNotFoundException();
            }
        } else {
            throw new KegiatanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param SubKegiatan $subKegiatan
     * @return SubKegiatan
     * @throws SubKegiatanNotFoundException
     * @throws SubKegiatanFailedUpdateException
     * @throws KegiatanNotFoundException
     */
    public function update(int $id, SubKegiatan $subKegiatan): SubKegiatan
    {
        $kegiatan = $this->kegiatanService->findOneById($subKegiatan->kegiatanId);
        if ($kegiatan) {
            $oldSubKegiatan = $this->findOneById($id);
            if ($oldSubKegiatan) {
                $satuan = $this->satuanService->findOneById($subKegiatan->satuanId);
                if ($satuan) {
                    try {
                        $update = $this->h->table('sub_kegiatan')->update(array_filter($subKegiatan->toArray()))->where('id', $id)->execute();
                        if ($update) {
                            return $this->findOneById($id);
                        }
                    } catch (Exception $e) {
                        throw new SubKegiatanFailedUpdateException();
                    }
                } else {
                    throw new SatuanNotFoundException();
                }
            } else {
                throw new SubKegiatanNotFoundException();
            }
        } else {
            throw new KegiatanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SubKegiatanNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldSubKegiatan = $this->findOneById($id);
        if ($oldSubKegiatan) {
            try {
                $delete = $this->h->table('sub_kegiatan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new SubKegiatanFailedDeleteException();
            }
        } else {
            throw new SubKegiatanNotFoundException();
        }
    }
}
