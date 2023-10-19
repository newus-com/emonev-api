<?php

declare(strict_types=1);

namespace App\Service\Rekening\RincianObjekRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Service\Rekening\ObjekRekening\ObjekRekeningService;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekening;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningNotFoundException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningRepository;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningNotFoundException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningFailedDeleteException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningFailedInsertException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningFailedUpdateException;
use App\Service\Table;

class RincianObjekRekeningService implements RincianObjekRekeningRepository
{
    private $h;
    private ObjekRekeningService $objekRekeningService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $objekRekeningService = $c->get(ObjekRekeningService::class);

        $this->h = $database->h();
        $this->objekRekeningService = $objekRekeningService;
    }

    /**
     * @param array|null $options
     * @return RincianObjekRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('rincian_objek_rekening')->select("rincian_objek_rekening.id, rincian_objek_rekening.kode, rincian_objek_rekening.uraianAkun, rincian_objek_rekening.deskripsiAkun, rincian_objek_rekening.objekRekeningId")->join('objek_rekening', 'objek_rekening.id', '=', 'rincian_objek_rekening.objekRekeningId')->whereNull('rincian_objek_rekening.deleteAt');
        if (isset($options['objekRekeningId']) && $options['objekRekeningId'] != 0) {
            $table = $table->where('objekRekeningId', '=', $options['objekRekeningId']);
        }
        $dataTable = new Table($table, columnOrder: ['rincian_objek_rekening.id', 'objekRekeningId', 'rincian_objek_rekening.kode', 'rincian_objek_rekening.uraianAkun', 'rincian_objek_rekening.deskripsiAkun'], columnSearch: ['rincian_objek_rekening.kode', 'rincian_objek_rekening.uraianAkun', 'rincian_objek_rekening.deskripsiAkun']);

        $dataTable->post = $options;

        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningNotFoundException
     */
    public function findOneById(int $id): RincianObjekRekening
    {
        $rincianObjekRekening = new RincianObjekRekening();

        $data = $this->h->table('rincian_objek_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new RincianObjekRekeningNotFoundException();
        }

        $return = $rincianObjekRekening->fromArray($data);

        return $return;
    }

    /**
     * @param RincianObjekRekening $rincianObjekRekening
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningFailedInsertException
     * @throws ObjekRekeningNotFoundException
     */
    public function create(RincianObjekRekening $rincianObjekRekening): RincianObjekRekening
    {
        $kelompokRekening = $this->objekRekeningService->findOneById($rincianObjekRekening->objekRekeningId);
        if ($kelompokRekening) {
            try {
                $insert = $this->h->table('rincian_objek_rekening')->insert($rincianObjekRekening->toArray())->execute();
                if ($insert) {
                    $rincianObjekRekening->id = (int)$insert;
                    return $rincianObjekRekening;
                }
            } catch (Exception $e) {
                throw new RincianObjekRekeningFailedInsertException();
            }
        } else {
            throw new ObjekRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param RincianObjekRekening $rincianObjekRekening
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningNotFoundException
     * @throws RincianObjekRekeningFailedUpdateException
     * @throws ObjekRekeningNotFoundException
     */
    public function update(int $id, RincianObjekRekening $rincianObjekRekening): RincianObjekRekening
    {
        $kelompokRekening = $this->objekRekeningService->findOneById($rincianObjekRekening->objekRekeningId);
        if ($kelompokRekening) {
            $oldRincianObjekRekening = $this->findOneById($id);
            if ($oldRincianObjekRekening) {
                try {
                    $update = $this->h->table('rincian_objek_rekening')->update(array_filter($rincianObjekRekening->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new RincianObjekRekeningFailedUpdateException();
                }
            } else {
                throw new RincianObjekRekeningNotFoundException();
            }
        } else {
            throw new ObjekRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws RincianObjekRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldRincianObjekRekening = $this->findOneById($id);
        if ($oldRincianObjekRekening) {
            try {
                $delete = $this->h->table('rincian_objek_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new RincianObjekRekeningFailedDeleteException();
            }
        } else {
            throw new RincianObjekRekeningNotFoundException();
        }
    }
}
