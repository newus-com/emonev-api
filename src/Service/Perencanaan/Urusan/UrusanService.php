<?php

declare(strict_types=1);

namespace App\Service\Perencanaan\Urusan;

use Exception;
use Psr\Container\ContainerInterface;
use App\Domain\Perencanaan\Urusan\Urusan;
use App\Application\Database\DatabaseInterface;
use App\Domain\Perencanaan\Urusan\UrusanRepository;
use App\Domain\Perencanaan\Urusan\UrusanNotFoundException;
use App\Domain\Perencanaan\Urusan\UrusanFailedDeleteException;
use App\Domain\Perencanaan\Urusan\UrusanFailedInsertException;
use App\Domain\Perencanaan\Urusan\UrusanFailedUpdateException;
use App\Service\Table;

class UrusanService implements UrusanRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return Urusan[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('urusan')->select("urusan.id, urusan.kode, urusan.nomenklatur")->whereNull('urusan.deleteAt');

        $dataTable = new Table($table,columnOrder: ['urusan.id','urusan.kode','urusan.nomenklatur'], columnSearch: ['urusan.kode', 'urusan.nomenklatur']);
        $dataTable->post = $options;

        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return Urusan
     * @throws UrusanNotFoundException
     */
    public function findOneById(int $id): Urusan
    {
        $urusan = new Urusan();

        $data = $this->h->table('urusan')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new UrusanNotFoundException();
        }

        $return = $urusan->fromArray($data);

        return $return;
    }

    /**
     * @param Urusan $urusan
     * @return Urusan
     * @throws UrusanFailedInsertException
     */
    public function create(Urusan $urusan): Urusan
    {
        try {
            $insert = $this->h->table('urusan')->insert($urusan->toArray())->execute();
            if ($insert) {
                $urusan->id = (int)$insert;
                return $urusan;
            }
        } catch (Exception $e) {
            throw new UrusanFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param Urusan $urusan
     * @return Urusan
     * @throws UrusanNotFoundException
     * @throws UrusanFailedUpdateException
     */
    public function update(int $id, Urusan $urusan): Urusan
    {
        $oldUrusan = $this->findOneById($id);
        if ($oldUrusan) {
            try {
                $update = $this->h->table('urusan')->update(array_filter($urusan->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new UrusanFailedUpdateException();
            }
        } else {
            throw new UrusanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws UrusanNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldUrusan = $this->findOneById($id);
        if ($oldUrusan) {
            try {
                $delete = $this->h->table('urusan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new UrusanFailedDeleteException();
            }
        } else {
            throw new UrusanNotFoundException();
        }
    }
}
