<?php

declare(strict_types=1);

namespace App\Service\Wilayah;

use Exception;
use App\Domain\Wilayah\Wilayah;
use Psr\Container\ContainerInterface;
use App\Domain\Wilayah\WilayahRepository;
use App\Application\Database\DatabaseInterface;
use App\Domain\Wilayah\WilayahNotFoundException;
use App\Domain\Wilayah\WilayahFailedDeleteException;
use App\Domain\Wilayah\WilayahFailedInsertException;
use App\Domain\Wilayah\WilayahFailedUpdateException;
use App\Service\Table;

class WilayahService implements WilayahRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return Wilayah[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('wilayah')->select()->whereNull('deleteAt');
        $dataTable = new Table($table,columnOrder: ['wilayah.id','wilayah.wilayah'], columnSearch: ['wilayah.wilayah']);
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
        //     $table = $table->where('wilayah', 'like', "%$search%");
        // } else {
        //     $search = "";
        // }
        // $dataWilayah = [];
        // $data = $table->get();
        // foreach ($data as $k) {
        //     $wilayah = new Wilayah(
        //         id: $k['id'],
        //         wilayah: $k['wilayah'],
        //         createAt: $k['createAt'],
        //         updateAt: $k['updateAt'],
        //         deleteAt: $k['deleteAt'],
        //     );
        //     $dataWilayah[] = $wilayah;
        // }
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return Wilayah
     * @throws WilayahNotFoundException
     */
    public function findOneById(int $id): Wilayah
    {
        $wilayah = new Wilayah();

        $data = $this->h->table('wilayah')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new WilayahNotFoundException();
        }

        $return = $wilayah->fromArray($data);

        return $return;
    }

    /**
     * @param Wilayah $wilayah
     * @return Wilayah
     * @throws WilayahFailedInsertException
     */
    public function create(Wilayah $wilayah): Wilayah
    {
        try {
            $insert = $this->h->table('wilayah')->insert($wilayah->toArray())->execute();
            if ($insert) {
                $wilayah->id = (int)$insert;
                return $wilayah;
            }
        } catch (Exception $e) {
            throw new WilayahFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param Wilayah $wilayah
     * @return Wilayah
     * @throws WilayahNotFoundException
     * @throws WilayahFailedUpdateException
     */
    public function update(int $id, Wilayah $wilayah): Wilayah
    {
        $oldWilayah = $this->findOneById($id);
        if ($oldWilayah) {
            try {
                $update = $this->h->table('wilayah')->update(array_filter($wilayah->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new WilayahFailedUpdateException();
            }
        } else {
            throw new WilayahNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws WilayahNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldWilayah = $this->findOneById($id);
        if ($oldWilayah) {
            try {
                $delete = $this->h->table('wilayah')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new WilayahFailedDeleteException();
            }
        } else {
            throw new WilayahNotFoundException();
        }
    }
}
