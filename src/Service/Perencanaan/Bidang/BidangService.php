<?php

declare(strict_types=1);

namespace App\Service\Perencanaan\Bidang;

use Exception;
use Psr\Container\ContainerInterface;
use App\Domain\Perencanaan\Bidang\Bidang;
use App\Application\Database\DatabaseInterface;
use App\Service\Perencanaan\Urusan\UrusanService;
use App\Domain\Perencanaan\Bidang\BidangRepository;
use App\Domain\Perencanaan\Bidang\BidangNotFoundException;
use App\Domain\Perencanaan\Urusan\UrusanNotFoundException;
use App\Domain\Perencanaan\Bidang\BidangFailedDeleteException;
use App\Domain\Perencanaan\Bidang\BidangFailedInsertException;
use App\Domain\Perencanaan\Bidang\BidangFailedUpdateException;
use App\Service\Table;

class BidangService implements BidangRepository
{
    private $h;
    private UrusanService $urusanService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $urusanService = $c->get(UrusanService::class);

        $this->h = $database->h();
        $this->urusanService = $urusanService;
    }

    /**
     * @param array|null $options
     * @return Bidang[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('bidang')->select("bidang.id, urusanId, bidang.kode, bidang.nomenklatur")->join('urusan', 'urusan.id', '=', 'bidang.urusanId')->whereNull('bidang.deleteAt');
        if (isset($options['urusanId']) && $options['urusanId'] != '') {
            $table = $table->where('urusanId', '=', $options['urusanId']);
        }
        $dataTable = new Table($table,columnOrder: ['bidang.id','urusanId', 'bidang.kode', 'bidang.nomenklatur'], columnSearch: ['bidang.kode', 'bidang.nomenklatur']);

        $dataTable->post = $options;

        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return Bidang
     * @throws BidangNotFoundException
     */
    public function findOneById(int $id): Bidang
    {
        $bidang = new Bidang();

        $data = $this->h->table('bidang')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new BidangNotFoundException();
        }

        $return = $bidang->fromArray($data);

        return $return;
    }

    /**
     * @param Bidang $bidang
     * @return Bidang
     * @throws BidangFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(Bidang $bidang): Bidang
    {
        $urusan = $this->urusanService->findOneById($bidang->urusanId);
        if ($urusan) {
            try {
                $insert = $this->h->table('bidang')->insert($bidang->toArray())->execute();
                if ($insert) {
                    $bidang->id = (int)$insert;
                    return $bidang;
                }
            } catch (Exception $e) {
                throw new BidangFailedInsertException();
            }
        } else {
            throw new UrusanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Bidang $bidang
     * @return Bidang
     * @throws BidangNotFoundException
     * @throws BidangFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, Bidang $bidang): Bidang
    {
        $urusan = $this->urusanService->findOneById($bidang->urusanId);
        if ($urusan) {
            $oldBidang = $this->findOneById($id);
            if ($oldBidang) {
                try {
                    $update = $this->h->table('bidang')->update(array_filter($bidang->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new BidangFailedUpdateException();
                }
            } else {
                throw new BidangNotFoundException();
            }
        } else {
            throw new UrusanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws BidangNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldBidang = $this->findOneById($id);
        if ($oldBidang) {
            try {
                $delete = $this->h->table('bidang')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new BidangFailedDeleteException();
            }
        } else {
            throw new BidangNotFoundException();
        }
    }
}
