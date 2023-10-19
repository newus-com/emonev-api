<?php

declare(strict_types=1);

namespace App\Service\Rekening\AkunRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Rekening\AkunRekening\AkunRekening;
use App\Domain\Rekening\AkunRekening\AkunRekeningRepository;
use App\Domain\Rekening\AkunRekening\AkunRekeningNotFoundException;
use App\Domain\Rekening\AkunRekening\AkunRekeningFailedDeleteException;
use App\Domain\Rekening\AkunRekening\AkunRekeningFailedInsertException;
use App\Domain\Rekening\AkunRekening\AkunRekeningFailedUpdateException;
use App\Service\Table;

class AkunRekeningService implements AkunRekeningRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);

        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return AkunRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('akun_rekening')->select("akun_rekening.id, akun_rekening.kode, akun_rekening.uraianAkun, akun_rekening.deskripsiAkun")->whereNull('deleteAt');
        $dataTable = new Table($table, columnOrder: ['akun_rekening.id', 'akun_rekening.kode', 'akun_rekening.uraianAkun', 'akun_rekening.deskripsiAkun'], columnSearch: ['akun_rekening.kode', 'akun_rekening.uraianAkun', 'akun_rekening.deskripsiAkun']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return AkunRekening
     * @throws AkunRekeningNotFoundException
     */
    public function findOneById(int $id): AkunRekening
    {
        $akunRekening = new AkunRekening();

        $data = $this->h->table('akun_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new AkunRekeningNotFoundException();
        }

        $return = $akunRekening->fromArray($data);

        return $return;
    }

    /**
     * @param AkunRekening $akunRekening
     * @return AkunRekening
     * @throws AkunRekeningFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(AkunRekening $akunRekening): AkunRekening
    {
        try {
            $insert = $this->h->table('akun_rekening')->insert($akunRekening->toArray())->execute();
            if ($insert) {
                $akunRekening->id = (int)$insert;
                return $akunRekening;
            }
        } catch (Exception $e) {
            throw new AkunRekeningFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param AkunRekening $akunRekening
     * @return AkunRekening
     * @throws AkunRekeningNotFoundException
     * @throws AkunRekeningFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, AkunRekening $akunRekening): AkunRekening
    {
        $oldAkunRekening = $this->findOneById($id);
        if ($oldAkunRekening) {
            try {
                $update = $this->h->table('akun_rekening')->update(array_filter($akunRekening->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new AkunRekeningFailedUpdateException();
            }
        } else {
            throw new AkunRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws AkunRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldAkunRekening = $this->findOneById($id);
        if ($oldAkunRekening) {
            try {
                $delete = $this->h->table('akun_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new AkunRekeningFailedDeleteException();
            }
        } else {
            throw new AkunRekeningNotFoundException();
        }
    }
}
