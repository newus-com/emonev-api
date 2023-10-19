<?php

declare(strict_types=1);

namespace App\Service\Satuan;

use Exception;
use App\Domain\Satuan\Satuan;
use Psr\Container\ContainerInterface;
use App\Domain\Satuan\SatuanRepository;
use App\Domain\Satuan\SatuanNotFoundException;
use App\Application\Database\DatabaseInterface;
use App\Domain\Satuan\SatuanFailedDeleteException;
use App\Domain\Satuan\SatuanFailedInsertException;
use App\Domain\Satuan\SatuanFailedUpdateException;
use App\Service\Table;

class SatuanService implements SatuanRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return Satuan[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('satuan')->select()->whereNull('deleteAt');
        $dataTable = new Table($table,columnOrder: ['satuan.id','s.atuansatuan'], columnSearch: ['satuan.satuan']);
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
        //     $table = $table->where('satuan', 'like', "%$search%");
        // } else {
        //     $search = "";
        // }
        // $dataSatuan = [];
        // $data = $table->get();
        // foreach ($data as $k) {
        //     $satuan = new Satuan(
        //         id: $k['id'],
        //         satuan: $k['satuan'],
        //         pembangunan: $k['pembangunan'],
        //         createAt: $k['createAt'],
        //         updateAt: $k['updateAt'],
        //         deleteAt: $k['deleteAt'],
        //     );
        //     $dataSatuan[] = $satuan;
        // }
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return Satuan
     * @throws SatuanNotFoundException
     */
    public function findOneById(int $id): Satuan
    {
        $satuan = new Satuan();

        $data = $this->h->table('satuan')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new SatuanNotFoundException();
        }

        $return = $satuan->fromArray($data);

        return $return;
    }

    /**
     * @param Satuan $satuan
     * @return Satuan
     * @throws SatuanFailedInsertException
     */
    public function create(Satuan $satuan): Satuan
    {
        try {
            $insert = $this->h->table('satuan')->insert($satuan->toArray())->execute();
            if ($insert) {
                $satuan->id = (int)$insert;
                return $satuan;
            }
        } catch (Exception $e) {
            throw new SatuanFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param Satuan $satuan
     * @return Satuan
     * @throws SatuanNotFoundException
     * @throws SatuanFailedUpdateException
     */
    public function update(int $id, Satuan $satuan): Satuan
    {
        $oldSatuan = $this->findOneById($id);
        if ($oldSatuan) {
            try {
                $update = $this->h->table('satuan')->update(array_filter($satuan->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new SatuanFailedUpdateException();
            }
        } else {
            throw new SatuanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SatuanNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldSatuan = $this->findOneById($id);
        if ($oldSatuan) {
            try {
                $delete = $this->h->table('satuan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new SatuanFailedDeleteException();
            }
        } else {
            throw new SatuanNotFoundException();
        }
    }
}
