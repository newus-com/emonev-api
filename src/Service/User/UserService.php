<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Domain\Role\Role;
use App\Domain\User\User;
use App\Domain\Dinas\Dinas;
use App\Domain\User\UserRepository;
use Psr\Container\ContainerInterface;
use App\Domain\User\UserNotFoundException;
use App\Application\Database\DatabaseInterface;
use App\Domain\Permission\Permission;
use App\Domain\Permission\PermissionNotFoundException;
use App\Domain\Role\RoleNotFoundException;
use App\Domain\User\UserBadRequestException;
use App\Service\Table;
use Exception;

class UserService implements UserRepository
{
    private $h;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $this->h = $database->h();
    }

    /**
     * @param array|null $options
     * @return User[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('user')->select()->whereNull('deleteAt');
        $dataTable = new Table($table,columnOrder: ['id','name','email'], columnSearch: ['name','email']);
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
        //     $table = $table->where('name', 'like', "%$search%");
        //     $table = $table->orWhere('email', 'like', "%$search%");
        // } else {
        //     $search = "";
        // }
        // $dataUser = [];
        // $data = $table->get();
        // foreach ($data as $k) {
        //     $user = new User(
        //         id: $k['id'],
        //         name: $k['name'],
        //         email: $k['email'],
        //         createAt: $k['createAt'],
        //         updateAt: $k['updateAt'],
        //         deleteAt: $k['deleteAt'],
        //     );
        //     $dataUser[] = $user;
        // }
        return [
            'data' => $dataTable->getDatatables(),
            'totalFiltered' => $dataTable->countFiltered(),
            'totalData' => $dataTable->countAll(),
        ];
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneById(int $id): User
    {
        $user = new User();

        $data = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new UserNotFoundException();
        }

        $return = $user->fromArray($data);

        return $return;
    }

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneByEmail(string $email): User
    {
        $user = new User();

        $data = $this->h->table('user')->select()->where('email', $email)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new UserNotFoundException();
        }

        $return = $user->fromArray($data);

        return $return;
    }

    public function findOneByIdFullJoin(int $id): User
    {
        $user = new User();

        $data = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new UserNotFoundException();
        }

        $specialPermission = $this->h->table('user_permission')
            ->select('permission.id, permission.name, permission.description')
            ->join('permission', 'permission.id', '=', 'user_permission.permissionId')
            ->where('user_permission.userId', $id)
            ->whereNull('user_permission.deleteAt')
            ->get();

        $dataDinas = $this->h->table('user_dinas')
            ->select('dinas.id, dinas.name, dinas.email, dinas.noHp, dinas.address, dinas.logo')
            ->join('dinas', 'dinas.id', '=', 'user_dinas.dinasId')
            ->where('user_dinas.userId', $id)
            ->whereNull('user_dinas.deleteAt')
            ->get();

        $dataGlobalRoleUser = $this->h->table('role_user')
            ->select(['role.id', 'role.role'])
            ->join('role', 'role.id', '=', 'role_user.roleId')
            ->whereNull('role_user.deleteAt')
            ->whereNull('role.dinasId')
            ->where('role_user.userId', $id)
            ->get();

        $globalRolePermission = [];
        foreach ($dataGlobalRoleUser as $k => $v) {
            $temp = $v;
            $permission = $this->h->table('role_permission')
                ->select('permission.id, permission.name, permission.description')
                ->join('permission', 'permission.id', '=', 'role_permission.permissionId')
                ->where('role_permission.roleId', $v['id'])
                ->whereNull('role_permission.deleteAt')
                ->get();
            $temp['permission'] = $permission;
            $globalRolePermission[] = $temp;
        }


        $dinas = [];
        foreach ($dataDinas as $k => $v) {
            $temp = [
                'id' => $v['id'],
                'name' => $v['name'],
                'email' => $v['email'],
                'noHp' => $v['noHp'],
                'address' => $v['address'],
                'logo' => $v['logo'],
            ];

            $dataRoleDinas = $this->h->table('role_user')
                ->select(['role.id', 'role.role'])
                ->join('role', 'role.id', '=', 'role_user.roleId')
                ->whereNull('role_user.deleteAt')
                ->where('role_user.userId', $id)
                ->where('role.dinasId', $temp['id'])
                ->get();

            $dinasRolePermission = [];
            foreach ($dataRoleDinas as $k => $v) {
                $temp = $v;
                $permission = $this->h->table('role_permission')
                    ->select('permission.id, permission.name, permission.description')
                    ->join('permission', 'permission.id', '=', 'role_permission.permissionId')
                    ->where('role_permission.roleId', $v['id'])
                    ->whereNull('role_permission.deleteAt')
                    ->get();
                $temp['permission'] = $permission;
                $dinasRolePermission[] = $temp;
            }

            $temp['role'] = $dinasRolePermission;
            $dinas[] = $temp;
        }

        $user = new User(
            id: $data['id'],
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            status: $data['status'],
            createAt: $data['createAt'],
            updateAt: $data['updateAt'],
            deleteAt: $data['deleteAt'],
            dinas: $dinas,
            specialPermission: $specialPermission,
            globalRole: $globalRolePermission
        );
        return $user;
    }

    /**
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     */
    public function createUser(User $user): User
    {
        try {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
            $user->status = 1;
            $insert = $this->h->table('user')->insert(array_filter($user->toArray()))->execute();
            if ($insert) {
                $user->id = (int)$insert;
                return $user;
            } else {
                throw new UserBadRequestException("Failed");
            }
        } catch (Exception $e) {
            throw new UserBadRequestException("Failed: " . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws UserNotFoundException
     **/
    public function deleteUser(int $id): bool
    {
        $oldUser = $this->findOneById($id);
        if ($oldUser) {
            try {
                $delete = $this->h->table('user')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new UserBadRequestException();
            }
        } else {
            throw new UserNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     **/
    public function updateUser(int $id, User $user): User
    {
        $oldUser = $this->findOneById($id);
        if ($oldUser) {
            try {
                $update = $this->h->table('user')->update(array_filter($user->toArray()))->where('id', $id)->execute();
                if ($update) {
                    return $this->findOneById($id);
                }
            } catch (Exception $e) {
                throw new UserBadRequestException();
            }
        } else {
            throw new UserNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param int $idPermission
     * @return User
     * @throws UserNotFoundException
     * */
    public function addSpecialPermissionUser(int $id, int $idPermission): User
    {
        $user = new User();

        $dataUser = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$dataUser) {
            throw new UserNotFoundException();
        }

        $dataPermission = $this->h->table('permission')->select()->where('id', $idPermission)->whereNull('deleteAt')->get();
        if (!$dataPermission) {
            throw new PermissionNotFoundException();
        }

        $this->h->table('user_permission')->insert([
            'userId' => $id, 'permissionId' => $idPermission, 'createAt' => date('Y-m-d H:i:s'),
        ])->execute();


        $fixUser = $user->fromArray($dataUser);

        return $fixUser;
    }

    /**
     * @param int $id
     * @param int $idPermission
     * @return bool
     * @throws UserNotFoundException
     */
    public function deleteSpecialPermissionUser(int $id, int $idPermission): bool
    {
        $user = $this->findOneById($id);
        if ($user) {
            try {
                $delete = $this->h->table('user_permission')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('userId', $id)->where('permissionId', $idPermission)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new UserBadRequestException();
            }
        } else {
            throw new UserNotFoundException();
        }
    }

    /** 
     * @param int $id
     * @param int $idRole
     * @return User
     * @throws UserNotFoundException
     */
    public function addUserRole(int $id, int $idRole): User
    {
        $dataUser = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$dataUser) {
            throw new UserNotFoundException();
        }

        $dataRole = $this->h->table('role')->select()->where('id', $idRole)->whereNull('deleteAt')->get();
        if (!$dataRole) {
            throw new RoleNotFoundException();
        }

        $this->h->table('role_user')->insert([
            'userId' => $id, 'roleId' => $idRole, 'createAt' => date('Y-m-d H:i:s'),
        ])->execute();

        $user = new User(
            id: $id,
        );

        $fixUser = $user->fromArray($dataUser);

        return $fixUser;
    }

    /**
     * @param int $id
     * @param int $idRole
     * @return bool
     * @throws UserNotFoundException
     */
    public function deleteRoleUser(int $id, int $idRole): bool
    {
        $user = $this->findOneById($id);
        if ($user) {
            try {
                $delete = $this->h->table('role_user')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('userId', $id)->where('roleId', $idRole)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new UserBadRequestException();
            }
        } else {
            throw new UserNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * 
     */
    public function getAllPermissionOneUser(int $id): array
    {
        $user = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$user) {
            throw new UserNotFoundException();
        }

        $specialPermissions = $this->h->table('user_permission')
            ->select(['permission.id', 'permission.name', 'permission.description'])
            ->join('permission', 'permission.id', '=', 'user_permission.permissionId')
            ->where('user_permission.userId', $id)
            ->whereNull('user_permission.deleteAt')
            ->get();

        $permissions = [];
        foreach ($specialPermissions as $permissionData) {
            $permission = new Permission(
                id: $permissionData['id'],
                name: $permissionData['name'],
                description: $permissionData['description'],
            );
            $permissions[] = $permission;
        }
        return $permissions;
    }
    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * 
     */
    public function getAllRoleOneUser(int $id): array
    {
        $user = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$user) {
            throw new UserNotFoundException();
        }

        $roleUser = $this->h->table('role_user')
            ->select(['role.id', 'role.role'])
            ->join('role', 'role.id', '=', 'role_user.roleId')
            ->where('role_user.userId', $id)
            ->whereNull('role_user.deleteAt')
            ->get();

        $roles = [];
        foreach ($roleUser as $roleData) {
            $role = new Role(
                id: $roleData['id'],
                role: $roleData['role'],
            );
            $roles[] = $role;
        }
        return $roles;
    }


    public function getAllDinasFromUser(int $id): array
    {
        $user = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if (!$user) {
            throw new UserNotFoundException();
        }

        $dataDinas = $this->h->table('user_dinas')->select(['dinas.id as DinasId', 'dinas.name'])->join('dinas', 'user_dinas.dinasId', '=', 'dinas.id')->where('user_dinas.userId', $id)->whereNull('user_dinas.deleteAt')->get();

        $dinas = [];
        foreach ($dataDinas as $item) {
            $temp = [
                'id' => $item['DinasId'],
                'name' => $item['name']
            ];
            $dinas[] = $temp;
        }
        return $dinas;
    }

    public function getAllPermission(int $id): array
    {
        $user = $this->h->table('user')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$user) {
            throw new UserNotFoundException();
        }

        $specialPermissions = $this->h->table('user_permission')
            ->select(['permission.id', 'permission.name', 'permission.description'])
            ->join('permission', 'permission.id', '=', 'user_permission.permissionId')
            ->where('user_permission.userId', $id)
            ->whereNull('user_permission.deleteAt')
            ->get();

        $permissions = [];

        //get permission from role 



        // foreach ($specialPermissions as $permissionData) {
        //     $permission = new Permission(
        //         id: $permissionData['id'],
        //         name: $permissionData['name'],
        //         description: $permissionData['description'],
        //     );
        //     $permissions[] = $permission;
        // }
        return $permissions;
    }
}
