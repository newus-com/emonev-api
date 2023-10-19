<?php

declare(strict_types=1);

namespace App\Service\Rekening\KelompokRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Rekening\KelompokRekening\KelompokRekening;
use App\Service\Rekening\AkunRekening\AkunRekeningService;
use App\Domain\Rekening\AkunRekening\AkunRekeningNotFoundException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningRepository;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningNotFoundException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningFailedDeleteException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningFailedInsertException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningFailedUpdateException;
use App\Service\Table;

class KelompokRekeningService implements KelompokRekeningRepository
{
    private $h;
    private AkunRekeningService $akunRekeningService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $akunRekeningService = $c->get(AkunRekeningService::class);

        $this->h = $database->h();
        $this->akunRekeningService = $akunRekeningService;
    }

    /**
     * @param array|null $options
     * @return KelompokRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('kelompok_rekening')->select("kelompok_rekening.id, kelompok_rekening.kode, kelompok_rekening.uraianAkun, kelompok_rekening.deskripsiAkun, kelompok_rekening.akunRekeningId")->join('akun_rekening','akun_rekening.id','=','kelompok_rekening.akunRekeningId')->whereNull('kelompok_rekening.deleteAt');
        if (isset($options['akunRekeningId']) && $options['akunRekeningId'] != 0) {
            $table = $table->where('akunRekeningId', '=', $options['akunRekeningId']);
        }
        $dataTable = new Table($table, columnOrder: ['kelompok_rekening.id', 'akunRekeningId', 'kelompok_rekening.kode', 'kelompok_rekening.uraianAkun', 'kelompok_rekening.deskripsiAkun'], columnSearch: ['kelompok_rekening.kode', 'kelompok_rekening.uraianAkun', 'kelompok_rekening.deskripsiAkun']);

        $dataTable->post = $options;


        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return KelompokRekening
     * @throws KelompokRekeningNotFoundException
     */
    public function findOneById(int $id): KelompokRekening
    {
        $kelompokRekening = new KelompokRekening();

        $data = $this->h->table('kelompok_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new KelompokRekeningNotFoundException();
        }

        $return = $kelompokRekening->fromArray($data);

        return $return;
    }

    /**
     * @param KelompokRekening $kelompokRekening
     * @return KelompokRekening
     * @throws KelompokRekeningFailedInsertException
     * @throws AkunRekeningNotFoundException
     */
    public function create(KelompokRekening $kelompokRekening): KelompokRekening
    {
        $akunRekening = $this->akunRekeningService->findOneById($kelompokRekening->akunRekeningId);
        if ($akunRekening) {
            try {
                $insert = $this->h->table('kelompok_rekening')->insert($kelompokRekening->toArray())->execute();
                if ($insert) {
                    $kelompokRekening->id = (int)$insert;
                    return $kelompokRekening;
                }
            } catch (Exception $e) {
                throw new KelompokRekeningFailedInsertException();
            }
        } else {
            throw new AkunRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param KelompokRekening $kelompokRekening
     * @return KelompokRekening
     * @throws KelompokRekeningNotFoundException
     * @throws KelompokRekeningFailedUpdateException
     * @throws AkunRekeningNotFoundException
     */
    public function update(int $id, KelompokRekening $kelompokRekening): KelompokRekening
    {
        $akunRekening = $this->akunRekeningService->findOneById($kelompokRekening->akunRekeningId);
        if ($akunRekening) {
            $oldKelompokRekening = $this->findOneById($id);
            if ($oldKelompokRekening) {
                try {
                    $update = $this->h->table('kelompok_rekening')->update(array_filter($kelompokRekening->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new KelompokRekeningFailedUpdateException();
                }
            } else {
                throw new KelompokRekeningNotFoundException();
            }
        } else {
            throw new AkunRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws KelompokRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldKelompokRekening = $this->findOneById($id);
        if ($oldKelompokRekening) {
            try {
                $delete = $this->h->table('kelompok_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new KelompokRekeningFailedDeleteException();
            }
        } else {
            throw new KelompokRekeningNotFoundException();
        }
    }
}
