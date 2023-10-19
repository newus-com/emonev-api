<?php

declare(strict_types=1);

namespace App\Service\Perencanaan\Kegiatan;

use Exception;
use Psr\Container\ContainerInterface;
use App\Domain\Perencanaan\Kegiatan\Kegiatan;
use App\Application\Database\DatabaseInterface;
use App\Service\Perencanaan\Program\ProgramService;
use App\Domain\Perencanaan\Kegiatan\KegiatanRepository;
use App\Domain\Perencanaan\Program\ProgramNotFoundException;
use App\Domain\Perencanaan\Kegiatan\KegiatanNotFoundException;
use App\Domain\Perencanaan\Kegiatan\KegiatanFailedDeleteException;
use App\Domain\Perencanaan\Kegiatan\KegiatanFailedInsertException;
use App\Domain\Perencanaan\Kegiatan\KegiatanFailedUpdateException;
use App\Service\Table;

class KegiatanService implements KegiatanRepository
{
    private $h;
    private ProgramService $programService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $programService = $c->get(ProgramService::class);

        $this->h = $database->h();
        $this->programService = $programService;
    }

    /**
     * @param array|null $options
     * @return Kegiatan[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('kegiatan')->select("kegiatan.id, programId, kegiatan.kode, kegiatan.nomenklatur")->join('program','program.id','=','kegiatan.programId')->whereNull('kegiatan.deleteAt');
        if (isset($options['programId']) && $options['programId'] != 0) {
            $table = $table->where('programId', '=', $options['programId']);
        }
        $dataTable = new Table($table,columnOrder: ['kegiatan.id','programId','kegiatan.kode','kegiatan.nomenklatur'], columnSearch: ['kegiatan.kode', 'kegiatan.nomenklatur']);
        $dataTable->post = $options;
        
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return Kegiatan
     * @throws KegiatanNotFoundException
     */
    public function findOneById(int $id): Kegiatan
    {
        $kegiatan = new Kegiatan();

        $data = $this->h->table('kegiatan')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new KegiatanNotFoundException();
        }

        $return = $kegiatan->fromArray($data);

        return $return;
    }

    /**
     * @param Kegiatan $kegiatan
     * @return Kegiatan
     * @throws KegiatanFailedInsertException
     * @throws ProgramNotFoundException
     */
    public function create(Kegiatan $kegiatan): Kegiatan
    {
        $program = $this->programService->findOneById($kegiatan->programId);
        if ($program) {
            try {
                $insert = $this->h->table('kegiatan')->insert($kegiatan->toArray())->execute();
                if ($insert) {
                    $kegiatan->id = (int)$insert;
                    return $kegiatan;
                }
            } catch (Exception $e) {
                throw new KegiatanFailedInsertException();
            }
        } else {
            throw new ProgramNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param Kegiatan $kegiatan
     * @return Kegiatan
     * @throws KegiatanNotFoundException
     * @throws KegiatanFailedUpdateException
     * @throws ProgramNotFoundException
     */
    public function update(int $id, Kegiatan $kegiatan): Kegiatan
    {
        $program = $this->programService->findOneById($kegiatan->programId);
        if ($program) {
            $oldKegiatan = $this->findOneById($id);
            if ($oldKegiatan) {
                try {
                    $update = $this->h->table('kegiatan')->update(array_filter($kegiatan->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new KegiatanFailedUpdateException();
                }
            } else {
                throw new KegiatanNotFoundException();
            }
        } else {
            throw new ProgramNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws KegiatanNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldKegiatan = $this->findOneById($id);
        if ($oldKegiatan) {
            try {
                $delete = $this->h->table('kegiatan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new KegiatanFailedDeleteException();
            }
        } else {
            throw new KegiatanNotFoundException();
        }
    }
}
