<?php

declare(strict_types=1);

namespace App\Service\Tahun;

use Exception;
use App\Domain\Tahun\Tahun;
use App\Domain\Tahun\TahunRepository;
use Psr\Container\ContainerInterface;
use App\Domain\Tahun\TahunNotFoundException;
use App\Application\Database\DatabaseInterface;
use App\Domain\Tahun\TahunFailedDeleteException;
use App\Domain\Tahun\TahunFailedInsertException;
use App\Domain\Tahun\TahunFailedUpdateException;
use App\Service\Table;

class TahunService implements TahunRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return Tahun[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('tahun')->select()->whereNull('deleteAt');
        $dataTable = new Table($table,columnOrder: ['tahun.id','tahun.tahun'], columnSearch: ['tahun.tahun']);
        $dataTable->post = $options;
        // if (isset($options['limit']) && $options['limit'] > 0) {
        //     $limit = $options['limit'];
        //     $table = $table->limit($limit);
        // } else {
        //     $limit = 10;
        //     $table = $table->limit(10);
        // }
        // if (isset($options['page']) && $options['page'] > 0) {
        //     $offset = $options['page'];
        //     $table = $table->offset($offset - 1);
        // } else {
        //     $offset = 1;
        //     $table = $table->offset(0);
        // }
        // if (isset($options['search'])) {
        //     $search = $options['search'];
        //     $table = $table->where('tahun', 'like', "%$search%");
        // } else {
        //     $search = "";
        // }
        // $dataTahun = [];
        // $data = $table->get();
        // foreach ($data as $k) {
        //     $tahun = new Tahun(
        //         id: $k['id'],
        //         tahun: $k['tahun'],
        //         active: $k['active'],
        //         createAt: $k['createAt'],
        //         updateAt: $k['updateAt'],
        //         deleteAt: $k['deleteAt'],
        //     );
        //     $dataTahun[] = $tahun;
        // }
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return Tahun
     * @throws TahunNotFoundException
     */
    public function findOneById(int $id): Tahun
    {
        $tahun = new Tahun();

        $data = $this->h->table('tahun')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new TahunNotFoundException();
        }

        $return = $tahun->fromArray($data);

        return $return;
    }

    /**
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunFailedInsertException
     */
    public function create(Tahun $tahun): Tahun
    {
        try {
            $insert = $this->h->table('tahun')->insert($tahun->toArray())->execute();
            if ($insert) {
                $tahun->id = (int)$insert;
                return $tahun;
            }
        } catch (Exception $e) {
            throw new TahunFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunNotFoundException
     * @throws TahunFailedUpdateException
     */
    public function update(int $id, Tahun $tahun): Tahun
    {
        $oldTahun = $this->findOneById($id);
        if ($oldTahun) {
            try {
                $update = $this->h->table('tahun')->update(array_filter($tahun->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new TahunFailedUpdateException();
            }
        } else {
            throw new TahunNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunNotFoundException
     * @throws TahunFailedUpdateException
     */
    public function active(int $id, Tahun $tahun): Tahun
    {
        $oldTahun = $this->findOneById($id);
        if ($oldTahun) {
            try {
                $deactive = $this->h->table('tahun')->update([
                    'active' => 0
                ])->whereNotIn('id', [$id])->execute();
                $active = $this->h->table('tahun')->update([
                    'active' => 1
                ])->where('id', $id)->execute();
                return $this->findOneById($id);
            } catch (Exception $e) {
                throw new TahunFailedUpdateException();
            }
        } else {
            throw new TahunNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws TahunNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldTahun = $this->findOneById($id);
        if ($oldTahun) {
            try {
                $delete = $this->h->table('tahun')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new TahunFailedDeleteException();
            }
        } else {
            throw new TahunNotFoundException();
        }
    }
}
