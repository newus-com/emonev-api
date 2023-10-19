<?php

declare(strict_types=1);

namespace App\Service\Organisasi\Unit;

use Exception;
use App\Domain\Organisasi\Unit\Unit;
use Psr\Container\ContainerInterface;
use App\Domain\Organisasi\Unit\UnitRepository;
use App\Application\Database\DatabaseInterface;
use App\Domain\Organisasi\Unit\UnitNotFoundException;
use App\Service\Organisasi\Organisasi\OrganisasiService;
use App\Domain\Organisasi\Unit\UnitFailedDeleteException;
use App\Domain\Organisasi\Unit\UnitFailedInsertException;
use App\Domain\Organisasi\Unit\UnitFailedUpdateException;
use App\Domain\Organisasi\Organisasi\OrganisasiNotFoundException;
use App\Service\Table;

class UnitService implements UnitRepository
{
    private $h;
    private OrganisasiService $organisasiService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $organisasiService = $c->get(OrganisasiService::class);

        $this->h = $database->h();
        $this->organisasiService = $organisasiService;
    }

    /**
     * @param array|null $options
     * @return Unit[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('unit')->select("unit.id, unit.organisasiId, unit.kode, unit.nomenklatur")->join('organisasi', 'organisasi.id', '=', 'unit.organisasiId')->whereNull('unit.deleteAt');
        if (isset($options['organisasiId']) && $options['organisasiId'] != 0) {
            $table = $table->where('organisasiId', '=', $options['organisasiId']);
        }
        $dataTable = new Table($table,columnOrder: ['unit.id','organisasiId', 'unit.kode', 'unit.nomenklatur'], columnSearch: ['unit.kode', 'unit.nomenklatur']);
        $dataTable->post = $options;
        
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return Unit
     * @throws UnitNotFoundException
     */
    public function findOneById(int $id): Unit
    {
        $program = new Unit();

        $data = $this->h->table('unit')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new UnitNotFoundException();
        }

        $return = $program->fromArray($data);

        return $return;
    }

    /**
     * @param Unit $program
     * @return Unit
     * @throws UnitFailedInsertException
     * @throws OrganisasiNotFoundException
     */
    public function create(Unit $program): Unit
    {
        $organisasi = $this->organisasiService->findOneById($program->organisasiId);
        if ($organisasi) {
            try {
                $insert = $this->h->table('unit')->insert($program->toArray())->execute();
                if ($insert) {
                    $program->id = (int)$insert;
                    return $program;
                }
            } catch (Exception $e) {
                throw new UnitFailedInsertException();
            }
        } else {
            throw new OrganisasiNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Unit $program
     * @return Unit
     * @throws UnitNotFoundException
     * @throws UnitFailedUpdateException
     * @throws OrganisasiNotFoundException
     */
    public function update(int $id, Unit $program): Unit
    {
        $organisasi = $this->organisasiService->findOneById($program->organisasiId);
        if ($organisasi) {
            $oldUnit = $this->findOneById($id);
            if ($oldUnit) {
                try {
                    $update = $this->h->table('unit')->update(array_filter($program->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new UnitFailedUpdateException();
                }
            } else {
                throw new UnitNotFoundException();
            }
        } else {
            throw new OrganisasiNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws UnitNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldUnit = $this->findOneById($id);
        if ($oldUnit) {
            try {
                $delete = $this->h->table('unit')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new UnitFailedDeleteException();
            }
        } else {
            throw new UnitNotFoundException();
        }
    }
}
