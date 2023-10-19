<?php

declare(strict_types=1);

namespace App\Service\Dinas;

use Exception;
use App\Domain\Role\Role;
use App\Domain\User\User;
use App\Domain\Dinas\Dinas;
use App\Domain\Dinas\DinasRepository;
use Psr\Container\ContainerInterface;
use App\Domain\User\UserNotFoundException;
use App\Domain\Dinas\DinasNotFoundException;
use App\Application\Database\DatabaseInterface;
use App\Domain\Dinas\DinasFailedDeleteException;
use App\Domain\Dinas\DinasFailedInsertException;
use App\Domain\Dinas\DinasFailedUpdateException;
use App\Service\Table;

class DinasService implements DinasRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return Dinas[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('dinas')->select("dinas.id, dinas.name, dinas.email, dinas.noHp, dinas.address, dinas.logo")->whereNull('deleteAt');

        $dataTable = new Table($table,columnOrder:['dinas.id','dinas.name','dinas.email','dinas.noHp','dinas.address','dinas.logo'], columnSearch: ['dinas.name','dinas.email']);

        $dataTable->post = $options;
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll()
        ];
    }

    /**
     * @param int $id
     * @return Dinas
     * @throws DinasNotFoundException
     */
    public function findOneById(int $id): Dinas
    {
        $dinas = new Dinas();

        $data = $this->h->table('dinas')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new DinasNotFoundException();
        }

        $return = $dinas->fromArray($data);

        return $return;
    }


    /**
     * @param int $id
     * @return Dinas
     * @throws DinasNotFoundException
     */
    public function findOneByIdWithRole(int $id): Dinas
    {
        $dinas = new Dinas();

        $data = $this->h->table('dinas')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new DinasNotFoundException();
        } else {
            $dataRole = $this->h->table('role')->select()->where('dinasId', $data['id'])->whereNull('deleteAt')->get();

            $fixRole = [];
            foreach ($dataRole as $r => $f) {
                $tempRole = new Role();
                $tempRole->id = $f['id'];
                $tempRole->role = $f['role'];
                $fixRole[] = $tempRole;
            }
            $dinas->role = $fixRole;
        }

        $dinas->id = $data['id'];
        $dinas->name = $data['name'];
        $dinas->email = $data['email'];
        $dinas->noHp = $data['noHp'];
        $dinas->address = $data['address'];
        $dinas->logo = $data['logo'];
        $dinas->createAt = $data['createAt'];
        $dinas->updateAt = $data['updateAt'];
        $dinas->deleteAt = $data['deleteAt'];

        return $dinas;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $noHp
     * @param string $address
     * @param string $logo
     * @return int|bool|string
     * @throws DinasFailedInsertException
     */
    public function create(string $name, string $email, string $noHp, string $address, string $logo): int|bool|string
    {
        try {
            return $this->h->table('dinas')->insert([
                'name' => $name,
                'email' => $email,
                'noHp' => $noHp,
                'address' => $address,
                'logo' => $logo,
                'createAt' => date('Y-m-d H:i:s')
            ])->execute();
        } catch (Exception $e) {
            throw new DinasFailedInsertException();
            // return $e->getMessage();
        }
        return false;
    }

    /**
     * @param int $id
     * @param Dinas $dinas
     * @return Dinas
     * @throws UserNotFoundException
     **/
    public function updateDinas(int $id, Dinas $dinas): Dinas
    {
        $oldDinas = $this->findOneById($id);
        if ($oldDinas) {
            try{
                $update = $this->h->table('dinas')->update(array_filter($dinas->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            }catch(Exception $e){
                throw new DinasFailedUpdateException();
            }
        }else{
            throw new DinasNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws DinasNotFoundException
     **/

    public function deleteDinas(int $id):bool
    {
        $oldDinas = $this->findOneById($id);
        if ($oldDinas) {
            try{
                $delete = $this->h->table('dinas')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            }catch(Exception $e){
                throw new DinasFailedDeleteException();
            }
        }else{
            throw new DinasNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws RoleNotFoundException
     */
    public function getAllRoleInDinas(int $id): array
    {
        $dinas = $this->h->table('dinas')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if(!$dinas){
            throw new DinasNotFoundException();
        }
        $roleDinas = $this->h->table('role')->select(['role.id','role.role'])->where('role.dinasId', $id)->whereNull('role.deleteAt')->get();

        $roles = [];
        foreach ($roleDinas as $roleData) {
            $role = new Role(
                id: $roleData['id'],
                dinasId: $id,
                role: $roleData['role'],
            );
            $roles[] = $role;
        }
        return $roles;
    }

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws UserNotFoundException
     * */
    public function getAllUserInDinas(int $id): array
    {
        $user = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if(!$user){
            throw new UserNotFoundException();
        }

        $userDinas = $this->h->table('user_dinas')->select(['user.id','user.name'])->join('user', 'user_dinas.userId', '=', 'user.id')->where('user_dinas.dinasId', $id)->whereNull('user_dinas.deleteAt')->get();

        $users = [];
        foreach ($userDinas as $userData) {
            $user = new User(
                id: $userData['id'],
                name: $userData['name'],
            );
            $users[] = $user;
        }
        return $users;
    }

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws UserNotFoundException
     * */
    public function getAllDinasInUser(int $id): array{
        $dinas = $this->h->table('dinas')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if(!$dinas){
            throw new DinasNotFoundException();
        }
        $dinasUser = $this->h->table('user_dinas')->select(['dinas.id','dinas.name'])->join('dinas', 'user_dinas.dinasId', '=', 'dinas.id')->where('user_dinas.userId', $id)->whereNull('user_dinas.deleteAt')->get();

        $allDinas = [];
        foreach ($dinasUser as $dinasData) {
            $dinas = new Dinas(
                id: $dinasData['id'],
                name: $dinasData['name'],
            );
            $allDinas[] = $dinas;
        }
        return $allDinas;
    }
}
