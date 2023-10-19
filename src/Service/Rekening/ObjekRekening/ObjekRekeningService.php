<?php

declare(strict_types=1);

namespace App\Service\Rekening\ObjekRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Rekening\ObjekRekening\ObjekRekening;
use App\Service\Rekening\JenisRekening\JenisRekeningService;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningRepository;
use App\Domain\Rekening\JenisRekening\JenisRekeningNotFoundException;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningNotFoundException;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningFailedDeleteException;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningFailedInsertException;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningFailedUpdateException;
use App\Service\Table;

class ObjekRekeningService implements ObjekRekeningRepository
{
    private $h;
    private JenisRekeningService $jenisRekeningService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $jenisRekeningService = $c->get(JenisRekeningService::class);

        $this->h = $database->h();
        $this->jenisRekeningService = $jenisRekeningService;
    }

    /**
     * @param array|null $options
     * @return ObjekRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('objek_rekening')->select("objek_rekening.id, objek_rekening.kode, objek_rekening.uraianAkun, objek_rekening.deskripsiAkun, objek_rekening.jenisRekeningId")->join('jenis_rekening', 'jenis_rekening.id', '=', 'objek_rekening.jenisRekeningId')->whereNull('objek_rekening.deleteAt');
        if (isset($options['jenisRekeningId']) && $options['jenisRekeningId'] != 0) {
            $table = $table->where('jenisRekeningId', '=', $options['jenisRekeningId']);
        }
        $dataTable = new Table($table, columnOrder: ['objek_rekening.id', 'jenisRekeningId', 'objek_rekening.kode', 'objek_rekening.uraianAkun', 'objek_rekening.deskripsiAkun'], columnSearch: ['objek_rekening.kode', 'objek_rekening.uraianAkun', 'objek_rekening.deskripsiAkun']);

        $dataTable->post = $options;

        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return ObjekRekening
     * @throws ObjekRekeningNotFoundException
     */
    public function findOneById(int $id): ObjekRekening
    {
        $objekRekening = new ObjekRekening();

        $data = $this->h->table('objek_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new ObjekRekeningNotFoundException();
        }

        $return = $objekRekening->fromArray($data);

        return $return;
    }

    /**
     * @param ObjekRekening $objekRekening
     * @return ObjekRekening
     * @throws ObjekRekeningFailedInsertException
     * @throws JenisRekeningNotFoundException
     */
    public function create(ObjekRekening $objekRekening): ObjekRekening
    {
        $jenisRekening = $this->jenisRekeningService->findOneById($objekRekening->jenisRekeningId);
        if ($jenisRekening) {
            try {
                $insert = $this->h->table('objek_rekening')->insert($objekRekening->toArray())->execute();
                if ($insert) {
                    $objekRekening->id = (int)$insert;
                    return $objekRekening;
                }
            } catch (Exception $e) {
                throw new ObjekRekeningFailedInsertException();
            }
        } else {
            throw new JenisRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param ObjekRekening $objekRekening
     * @return ObjekRekening
     * @throws ObjekRekeningNotFoundException
     * @throws ObjekRekeningFailedUpdateException
     * @throws JenisRekeningNotFoundException
     */
    public function update(int $id, ObjekRekening $objekRekening): ObjekRekening
    {
        $jenisRekening = $this->jenisRekeningService->findOneById($objekRekening->jenisRekeningId);
        if ($jenisRekening) {
            $oldObjekRekening = $this->findOneById($id);
            if ($oldObjekRekening) {
                try {
                    $update = $this->h->table('objek_rekening')->update(array_filter($objekRekening->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new ObjekRekeningFailedUpdateException();
                }
            } else {
                throw new ObjekRekeningNotFoundException();
            }
        } else {
            throw new JenisRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws ObjekRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldObjekRekening = $this->findOneById($id);
        if ($oldObjekRekening) {
            try {
                $delete = $this->h->table('objek_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new ObjekRekeningFailedDeleteException();
            }
        } else {
            throw new ObjekRekeningNotFoundException();
        }
    }
}
