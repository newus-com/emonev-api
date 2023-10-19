<?php

declare(strict_types=1);

namespace App\Service\KomponenPembangunan;

use Exception;
use Psr\Container\ContainerInterface;
use App\Application\Database\DatabaseInterface;
use App\Domain\KomponenPembangunan\KomponenPembangunan;
use App\Domain\KomponenPembangunan\KomponenPembangunanRepository;
use App\Domain\KomponenPembangunan\KomponenPembangunanNotFoundException;
use App\Domain\KomponenPembangunan\KomponenPembangunanFailedDeleteException;
use App\Domain\KomponenPembangunan\KomponenPembangunanFailedInsertException;
use App\Domain\KomponenPembangunan\KomponenPembangunanFailedUpdateException;


class KomponenPembangunanService implements KomponenPembangunanRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return array
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('komponen_pembangunan')->select()->whereNull('deleteAt');
        if (isset($options['search'])) {
            $search = $options['search'];
            $table = $table->where('komponen', 'like', "%$search%");
        } else {
            $search = "";
        }
        $data = $table->get();
        return $this->buildTree($data);
    }

    private function buildTree(array $elements, $parentId = 0) {
        $branch = array();
    
        foreach ($elements as $element) {
            if ($element['parentId'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
    
        return $branch;
    }


    /**
     * @param int $id
     * @return KomponenPembangunan
     * @throws KomponenPembangunanNotFoundException
     */
    public function findOneById(int $id): KomponenPembangunan
    {
        $komponenPembangunan = new KomponenPembangunan();

        $data = $this->h->table('komponen_pembangunan')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new KomponenPembangunanNotFoundException();
        }

        $return = $komponenPembangunan->fromArray($data);

        return $return;
    }

    /**
     * @param KomponenPembangunan $komponenPembangunan
     * @return KomponenPembangunan
     * @throws KomponenPembangunanFailedInsertException
     */
    public function create(KomponenPembangunan $komponenPembangunan): KomponenPembangunan
    {
        if($komponenPembangunan->parentId == null){
            $komponenPembangunan->parentId = 0;
        }else{
            try{
                $this->findOneById($komponenPembangunan->parentId);
            }catch(Exception $e){
                throw new KomponenPembangunanNotFoundException();
            }
        }
        try {
            $insert = $this->h->table('komponen_pembangunan')->insert($komponenPembangunan->toArray())->execute();
            if ($insert) {
                $komponenPembangunan->id = (int)$insert;
                return $komponenPembangunan;
            }
        } catch (Exception $e) {
            throw new KomponenPembangunanFailedInsertException();
        }
    }

    /**
     * @param int $id
     * @param KomponenPembangunan $komponenPembangunan
     * @return KomponenPembangunan
     * @throws KomponenPembangunanNotFoundException
     * @throws KomponenPembangunanFailedUpdateException
     */
    public function update(int $id, KomponenPembangunan $komponenPembangunan): KomponenPembangunan
    {
        $oldKomponenPembangunan = $this->findOneById($id);
        if ($oldKomponenPembangunan) {
            if($komponenPembangunan->parentId == null){
                $komponenPembangunan->parentId = 0;
            }else{
                try{
                    $this->findOneById($komponenPembangunan->parentId);
                }catch(Exception $e){
                    throw new KomponenPembangunanNotFoundException();
                }
            }
            try {
                $update = $this->h->table('komponen_pembangunan')->update(array_filter($komponenPembangunan->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new KomponenPembangunanFailedUpdateException();
            }
        } else {
            throw new KomponenPembangunanNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws KomponenPembangunanNotFoundException
     */
    public function delete(int $id): bool
    {
        $oldKomponenPembangunan = $this->findOneById($id);
        if ($oldKomponenPembangunan) {
            try {
                $delete = $this->h->table('komponen_pembangunan')->update(['deleteAt' => (string)date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new KomponenPembangunanFailedDeleteException();
            }
        } else {
            throw new KomponenPembangunanNotFoundException();
        }
    }
}