<?php

declare(strict_types=1);

namespace App\Service\Rekening\SubRincianObjekRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekening;
use App\Service\Rekening\RincianObjekRekening\RincianObjekRekeningService;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningRepository;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningNotFoundException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningNotFoundException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningFailedDeleteException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningFailedInsertException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningFailedUpdateException;
use App\Service\Table;

class SubRincianObjekRekeningService implements SubRincianObjekRekeningRepository
{
    private $h;
    private RincianObjekRekeningService $rincianObjekRekeningService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $rincianObjekRekeningService = $c->get(RincianObjekRekeningService::class);

        $this->h = $database->h();
        $this->rincianObjekRekeningService = $rincianObjekRekeningService;
    }

    /**
     * @param array|null $options
     * @return SubRincianObjekRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('sub_rincian_objek_rekening')->select("sub_rincian_objek_rekening.id, sub_rincian_objek_rekening.kode, sub_rincian_objek_rekening.uraianAkun, sub_rincian_objek_rekening.deskripsiAkun, sub_rincian_objek_rekening.rincianObjekRekeningId")->join('rincian_objek_rekening','rincian_objek_rekening.id','=','sub_rincian_objek_rekening.rincianObjekRekeningId')->whereNull('sub_rincian_objek_rekening.deleteAt');
        if (isset($options['rincianObjekRekeningId']) && $options['rincianObjekRekeningId'] != 0) {
            $table = $table->where('rincianObjekRekeningId', '=', $options['rincianObjekRekeningId']);
        }
        $dataTable = new Table($table,columnOrder:['sub_rincian_objek_rekening.id','rincianObjekRekeningId','sub_rincian_objek_rekening.kode','sub_rincian_objek_rekening.uraianAkun','sub_rincian_objek_rekening.deskripsiAkun'], columnSearch:['sub_rincian_objek_rekening.kode','sub_rincian_objek_rekening.uraianAkun','sub_rincian_objek_rekening.deskripsiAkun']);

        $dataTable->post = $options;
        
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningNotFoundException
     */
    public function findOneById(int $id): SubRincianObjekRekening
    {
        $subRincianObjekRekening = new SubRincianObjekRekening();

        $data = $this->h->table('sub_rincian_objek_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new SubRincianObjekRekeningNotFoundException();
        }

        $return = $subRincianObjekRekening->fromArray($data);

        return $return;
    }

    /**
     * @param SubRincianObjekRekening $subRincianObjekRekening
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningFailedInsertException
     * @throws RincianObjekRekeningNotFoundException
     */
    public function create(SubRincianObjekRekening $subRincianObjekRekening): SubRincianObjekRekening
    {
        $kelompokRekening = $this->rincianObjekRekeningService->findOneById($subRincianObjekRekening->rincianObjekRekeningId);
        if ($kelompokRekening) {
            try {
                $insert = $this->h->table('sub_rincian_objek_rekening')->insert($subRincianObjekRekening->toArray())->execute();
                if ($insert) {
                    $subRincianObjekRekening->id = (int)$insert;
                    return $subRincianObjekRekening;
                }
            } catch (Exception $e) {
                throw new SubRincianObjekRekeningFailedInsertException();
            }
        } else {
            throw new RincianObjekRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param SubRincianObjekRekening $subRincianObjekRekening
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningNotFoundException
     * @throws SubRincianObjekRekeningFailedUpdateException
     * @throws RincianObjekRekeningNotFoundException
     */
    public function update(int $id, SubRincianObjekRekening $subRincianObjekRekening): SubRincianObjekRekening
    {
        $kelompokRekening = $this->rincianObjekRekeningService->findOneById($subRincianObjekRekening->rincianObjekRekeningId);
        if ($kelompokRekening) {
            $oldSubRincianObjekRekening = $this->findOneById($id);
            if ($oldSubRincianObjekRekening) {
                try {
                    $update = $this->h->table('sub_rincian_objek_rekening')->update(array_filter($subRincianObjekRekening->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new SubRincianObjekRekeningFailedUpdateException();
                }
            } else {
                throw new SubRincianObjekRekeningNotFoundException();
            }
        } else {
            throw new RincianObjekRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SubRincianObjekRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldSubRincianObjekRekening = $this->findOneById($id);
        if ($oldSubRincianObjekRekening) {
            try {
                $delete = $this->h->table('sub_rincian_objek_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new SubRincianObjekRekeningFailedDeleteException();
            }
        } else {
            throw new SubRincianObjekRekeningNotFoundException();
        }
    }
}
