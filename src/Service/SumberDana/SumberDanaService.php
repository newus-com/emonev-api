<?php

declare(strict_types=1);

namespace App\Service\SumberDana;

use Exception;
use App\Domain\SumberDana\SumberDana;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\SumberDana\SumberDanaRepository;
use App\Domain\SumberDana\SumberDanaNotFoundException;
use App\Domain\SumberDana\SumberDanaFailedDeleteException;
use App\Domain\SumberDana\SumberDanaFailedInsertException;
use App\Domain\SumberDana\SumberDanaFailedUpdateException;
use App\Service\Table;

class SumberDanaService implements SumberDanaRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return SumberDana[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('sumber_dana')->select()->whereNull('deleteAt');
        $dataTable = new Table($table,columnOrder: ['sumber_dana.id','sumber_dana.sumberDana'], columnSearch: ['sumber_dana.sumberDana']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return SumberDana
     * @throws SumberDanaNotFoundException
     */
    public function findOneById(int $id): SumberDana
    {
        $sumberDana = new SumberDana();

        $data = $this->h->table('sumber_dana')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new SumberDanaNotFoundException();
        }

        $return = $sumberDana->fromArray($data);

        return $return;
    }

    /**
     * @param SumberDana $sumberDana
     * @return SumberDana
     * @throws SumberDanaFailedInsertException
     */
    public function create(SumberDana $sumberDana): SumberDana
    {
        try {
            $insert = $this->h->table('sumber_dana')->insert($sumberDana->toArray())->execute();
            if ($insert) {
                $sumberDana->id = (int)$insert;
                return $sumberDana;
            }
        } catch (Exception $e) {
            throw new SumberDanaFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param SumberDana $sumberDana
     * @return SumberDana
     * @throws SumberDanaNotFoundException
     * @throws SumberDanaFailedUpdateException
     */
    public function update(int $id, SumberDana $sumberDana): SumberDana
    {
        $oldSumberDana = $this->findOneById($id);
        if ($oldSumberDana) {
            try {
                $update = $this->h->table('sumber_dana')->update(array_filter($sumberDana->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new SumberDanaFailedUpdateException();
            }
        } else {
            throw new SumberDanaNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SumberDanaNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldSumberDana = $this->findOneById($id);
        if ($oldSumberDana) {
            try {
                $delete = $this->h->table('sumber_dana')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new SumberDanaFailedDeleteException();
            }
        } else {
            throw new SumberDanaNotFoundException();
        }
    }
}
