<?php

declare(strict_types=1);

namespace App\Service\Organisasi\Organisasi;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Organisasi\Organisasi\Organisasi;
use App\Service\Perencanaan\Bidang\BidangService;
use App\Domain\Organisasi\Organisasi\OrganisasiRepository;
use App\Domain\Perencanaan\Bidang\BidangNotFoundException;
use App\Domain\Organisasi\Organisasi\OrganisasiNotFoundException;
use App\Domain\Organisasi\Organisasi\OrganisasiFailedDeleteException;
use App\Domain\Organisasi\Organisasi\OrganisasiFailedInsertException;
use App\Domain\Organisasi\Organisasi\OrganisasiFailedUpdateException;
use App\Service\Table;
use PHPUnit\Framework\Constraint\IsEmpty;

use function PHPUnit\Framework\isNull;

class OrganisasiService implements OrganisasiRepository
{
    private $h;
    private BidangService $bidangService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $bidangService = $c->get(BidangService::class);

        $this->h = $database->h();
        $this->bidangService = $bidangService;
    }

    /**
     * @param array|null $options
     * @return Organisasi[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('organisasi')->select("organisasi.id,bidangId, organisasi.kode, organisasi.nomenklatur")->join('bidang', 'bidang.id', '=', 'organisasi.bidangId')->whereNull('organisasi.deleteAt');
        if (isset($options['bidangId']) && $options['bidangId'] != 0) {
            $table = $table->where('bidangId', '=', $options['bidangId']);
        }
        $dataTable = new Table($table, columnOrder: ['organisasi.id', 'bidangId', 'organisasi.kode', 'organisasi.nomenklatur'], columnSearch: ['organisasi.kode', 'organisasi.nomenklatur']);
        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }


    /**
     * @param int $id
     * @return Organisasi
     * @throws OrganisasiNotFoundException
     */
    public function findOneById(int $id): Organisasi
    {
        $organisasi = new Organisasi();

        $data = $this->h->table('organisasi')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new OrganisasiNotFoundException();
        }

        $return = $organisasi->fromArray($data);

        return $return;
    }

    /**
     * @param Organisasi $organisasi
     * @return Organisasi
     * @throws OrganisasiFailedInsertException
     * @throws BidangNotFoundException
     */
    public function create(Organisasi $organisasi): Organisasi
    {
        $bidang = $this->bidangService->findOneById($organisasi->bidangId);
        if ($bidang) {
            try {
                $insert = $this->h->table('organisasi')->insert($organisasi->toArray())->execute();
                if ($insert) {
                    $organisasi->id = (int)$insert;
                    return $organisasi;
                }
            } catch (Exception $e) {
                throw new OrganisasiFailedInsertException();
            }
        } else {
            throw new BidangNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Organisasi $organisasi
     * @return Organisasi
     * @throws OrganisasiNotFoundException
     * @throws OrganisasiFailedUpdateException
     * @throws BidangNotFoundException
     */
    public function update(int $id, Organisasi $organisasi): Organisasi
    {
        $bidang = $this->bidangService->findOneById($organisasi->bidangId);
        if ($bidang) {
            $oldOrganisasi = $this->findOneById($id);
            if ($oldOrganisasi) {
                try {
                    $update = $this->h->table('organisasi')->update(array_filter($organisasi->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new OrganisasiFailedUpdateException();
                }
            } else {
                throw new OrganisasiNotFoundException();
            }
        } else {
            throw new BidangNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws OrganisasiNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldOrganisasi = $this->findOneById($id);
        if ($oldOrganisasi) {
            try {
                $delete = $this->h->table('organisasi')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new OrganisasiFailedDeleteException();
            }
        } else {
            throw new OrganisasiNotFoundException();
        }
    }
}
