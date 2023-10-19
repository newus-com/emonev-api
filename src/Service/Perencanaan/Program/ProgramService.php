<?php

declare(strict_types=1);

namespace App\Service\Perencanaan\Program;

use Exception;
use Psr\Container\ContainerInterface;
use App\Domain\Perencanaan\Program\Program;
use App\Application\Database\DatabaseInterface;
use App\Service\Perencanaan\Bidang\BidangService;
use App\Domain\Perencanaan\Program\ProgramRepository;
use App\Domain\Perencanaan\Bidang\BidangNotFoundException;
use App\Domain\Perencanaan\Program\ProgramNotFoundException;
use App\Domain\Perencanaan\Program\ProgramFailedDeleteException;
use App\Domain\Perencanaan\Program\ProgramFailedInsertException;
use App\Domain\Perencanaan\Program\ProgramFailedUpdateException;
use App\Service\Table;

class ProgramService implements ProgramRepository
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
     * @return Program[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('program')->select("program.id, bidangId, program.kode, program.nomenklatur")->join('bidang', 'bidang.id', '=', 'program.bidangId')->whereNull('program.deleteAt');
        if (isset($options['bidangId']) && $options['bidangId'] != 0) {
            $table = $table->where('bidangId', '=', $options['bidangId']);
        }
        $dataTable = new Table($table,columnOrder: ['program.id','bidangId', 'program.kode', 'program.nomenklatur'], columnSearch: ['program.kode', 'program.nomenklatur']);
        $dataTable->post = $options;
        
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return Program
     * @throws ProgramNotFoundException
     */
    public function findOneById(int $id): Program
    {
        $program = new Program();

        $data = $this->h->table('program')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new ProgramNotFoundException();
        }

        $return = $program->fromArray($data);

        return $return;
    }

    /**
     * @param Program $program
     * @return Program
     * @throws ProgramFailedInsertException
     * @throws BidangNotFoundException
     */
    public function create(Program $program): Program
    {
        $bidang = $this->bidangService->findOneById($program->bidangId);
        if ($bidang) {
            try {
                $insert = $this->h->table('program')->insert($program->toArray())->execute();
                if ($insert) {
                    $program->id = (int)$insert;
                    return $program;
                }
            } catch (Exception $e) {
                throw new ProgramFailedInsertException();
            }
        } else {
            throw new BidangNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Program $program
     * @return Program
     * @throws ProgramNotFoundException
     * @throws ProgramFailedUpdateException
     * @throws BidangNotFoundException
     */
    public function update(int $id, Program $program): Program
    {
        $bidang = $this->bidangService->findOneById($program->bidangId);
        if ($bidang) {
            $oldProgram = $this->findOneById($id);
            if ($oldProgram) {
                try {
                    $update = $this->h->table('program')->update(array_filter($program->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new ProgramFailedUpdateException();
                }
            } else {
                throw new ProgramNotFoundException();
            }
        } else {
            throw new BidangNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws ProgramNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldProgram = $this->findOneById($id);
        if ($oldProgram) {
            try {
                $delete = $this->h->table('program')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new ProgramFailedDeleteException();
            }
        } else {
            throw new ProgramNotFoundException();
        }
    }
}
