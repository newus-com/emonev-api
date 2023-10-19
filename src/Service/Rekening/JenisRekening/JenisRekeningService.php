<?php

declare(strict_types=1);

namespace App\Service\Rekening\JenisRekening;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\Rekening\JenisRekening\JenisRekening;
use App\Domain\Rekening\JenisRekening\JenisRekeningRepository;
use App\Service\Rekening\KelompokRekening\KelompokRekeningService;
use App\Domain\Rekening\JenisRekening\JenisRekeningNotFoundException;
use App\Domain\Rekening\JenisRekening\JenisRekeningFailedDeleteException;
use App\Domain\Rekening\JenisRekening\JenisRekeningFailedInsertException;
use App\Domain\Rekening\JenisRekening\JenisRekeningFailedUpdateException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningNotFoundException;
use App\Service\Table;

class JenisRekeningService implements JenisRekeningRepository
{
    private $h;
    private KelompokRekeningService $kelompokRekeningService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $kelompokRekeningService = $c->get(KelompokRekeningService::class);

        $this->h = $database->h();
        $this->kelompokRekeningService = $kelompokRekeningService;
    }

    /**
     * @param array|null $options
     * @return JenisRekening[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('jenis_rekening')->select("jenis_rekening.id, jenis_rekening.kode, jenis_rekening.uraianAkun, jenis_rekening.deskripsiAkun, jenis_rekening.kelompokRekeningId")->join('kelompok_rekening','kelompok_rekening.id','=','jenis_rekening.kelompokRekeningId')->whereNull('jenis_rekening.deleteAt');
        if (isset($options['kelompokRekeningId']) && $options['kelompokRekeningId'] != 0) {
            $table = $table->where('kelompokRekeningId', '=', $options['kelompokRekeningId']);
        }
        $dataTable = new Table($table, columnOrder: ['jenis_rekening.id', 'kelompokRekeningId', 'jenis_rekening.kode', 'jenis_rekening.uraianAkun', 'jenis_rekening.deskripsiAkun'], columnSearch: ['jenis_rekening.kode', 'jenis_rekening.uraianAkun']);

        $dataTable->post = $options;
        
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'total' => $dataTable->countAll()
        ];
    }


    /**
     * @param int $id
     * @return JenisRekening
     * @throws JenisRekeningNotFoundException
     */
    public function findOneById(int $id): JenisRekening
    {
        $jenisRekening = new JenisRekening();

        $data = $this->h->table('jenis_rekening')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new JenisRekeningNotFoundException();
        }

        $return = $jenisRekening->fromArray($data);

        return $return;
    }

    /**
     * @param JenisRekening $jenisRekening
     * @return JenisRekening
     * @throws JenisRekeningFailedInsertException
     * @throws KelompokRekeningNotFoundException
     */
    public function create(JenisRekening $jenisRekening): JenisRekening
    {
        $kelompokRekening = $this->kelompokRekeningService->findOneById($jenisRekening->kelompokRekeningId);
        if ($kelompokRekening) {
            try {
                $insert = $this->h->table('jenis_rekening')->insert($jenisRekening->toArray())->execute();
                if ($insert) {
                    $jenisRekening->id = (int)$insert;
                    return $jenisRekening;
                }
            } catch (Exception $e) {
                throw new JenisRekeningFailedInsertException();
            }
        } else {
            throw new KelompokRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param JenisRekening $jenisRekening
     * @return JenisRekening
     * @throws JenisRekeningNotFoundException
     * @throws JenisRekeningFailedUpdateException
     * @throws KelompokRekeningNotFoundException
     */
    public function update(int $id, JenisRekening $jenisRekening): JenisRekening
    {
        $kelompokRekening = $this->kelompokRekeningService->findOneById($jenisRekening->kelompokRekeningId);
        if ($kelompokRekening) {
            $oldJenisRekening = $this->findOneById($id);
            if ($oldJenisRekening) {
                try {
                    $update = $this->h->table('jenis_rekening')->update(array_filter($jenisRekening->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new JenisRekeningFailedUpdateException();
                }
            } else {
                throw new JenisRekeningNotFoundException();
            }
        } else {
            throw new KelompokRekeningNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws JenisRekeningNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldJenisRekening = $this->findOneById($id);
        if ($oldJenisRekening) {
            try {
                $delete = $this->h->table('jenis_rekening')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new JenisRekeningFailedDeleteException();
            }
        } else {
            throw new JenisRekeningNotFoundException();
        }
    }
}
