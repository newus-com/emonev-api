<?php

declare(strict_types=1);

namespace App\Service\Role;

use App\Application\Database\DatabaseInterface;
use App\Domain\Dinas\Dinas;
use App\Domain\Dinas\DinasNotFoundException;
use App\Domain\Permission\Permission;
use App\Domain\Permission\PermissionNotFoundException;
use App\Domain\Role\PermissionBadRequestException;
use App\Domain\Role\Role;
use App\Domain\Role\RoleBadRequestException;
use App\Domain\Role\RoleNotFoundException;
use App\Domain\Role\RoleRepository;
use App\Service\Dinas\DinasService;
use Exception;
use Psr\Container\ContainerInterface;


class RoleService implements RoleRepository
{
    private $h;
    private DinasService $dinasService;

    public function __construct(ContainerInterface $c)
    {
        $database = $c->get(DatabaseInterface::class);
        $dinasService = $c->get(DinasService::class);
        $this->h = $database->h();
        $this->dinasService = $dinasService;
    }

    /**
     * @param array|null $options
     * @return Role[]
     */
    public function findAll(array|null $options): array
    {
        $table = $this->h->table('role')->select()->whereNull('deleteAt');
        if (isset($options['limit']) && $options['limit'] > 0) {
            $limit = $options['limit'];
            $table = $table->limit($limit);
        } else {
            $limit = 10;
            $table = $table->limit(10);
        }
        if (isset($options['page']) && $options['page'] > 0) {
            $offset = $options['page'];
            $table = $table->offset($offset - 1);
        } else {
            $offset = 1;
            $table = $table->offset(0);
        }
        if (isset($options['search'])) {
            $search = $options['search'];
            $table = $table->where('role', 'like', "%$search%");
        } else {
            $search = "";
        }
        $dataRole = [];
        $data = $table->get();
        foreach ($data as $k) {
            $role = new Role(
                id: $k['id'],
                role: $k['role'],
                createAt: $k['createAt'],
                updateAt: $k['updateAt'],
                deleteAt: $k['deleteAt']
            );
            $dataRole[] = $role;
        }
        return [
            'role' => $dataRole,
            'page' => $offset,
            'limit' => $limit,
            'search' => $search
        ];
    }

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneById(int $id): Role
    {
        $role = new Role();

        $data = $this->h->table('role')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new RoleNotFoundException();
        }

        $return = $role->fromArray($data);

        return $return;
    }

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithModule(int $id): Role
    {
        $role = new Role();

        $data = $this->h->table('role')->select()->where('id', $id)->whereNull('deleteAt')->one();
        if ($data == NULL) {
            throw new RoleNotFoundException();
        }

        $role->id = $data['id'];
        $role->role = $data['role'];
        $role->createAt = $data['createAt'];
        $role->updateAt = $data['updateAt'];
        $role->deleteAt = $data['deleteAt'];

        return $role;
    }


    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithModuleAndPermission(int $id): Role
    {
        $role = new Role();
        return $role;
    }

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithPermission(int $id): Role
    {
        $role = new Role();
        return $role;
    }

    /**
     * @param Role $role
     * @return Role
     * @throws RoleBadRequestException
     * @throws DinasNotFoundException
     */
    public function createRole(Role $role): Role
    {
        $dinasId = $this->dinasService->findOneById($role->dinasId);
        if ($dinasId) {
            try {
                $insert = $this->h->table('role')->insert(array_filter($role->toArray()))->execute();
                if ($insert) {
                    $role->id = (int)$insert;
                    return $role;
                }
            } catch (Exception $e) {
                throw new RoleBadRequestException('failed' . $e->getMessage());
            }
        } else {
            throw new DinasNotFoundException();
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws RoleNotFoundException
     */
    public function deleteRole(int $id): bool
    {
        $oldRole = $this->findOneById($id);
        if ($oldRole) {
            try {
                $delete = $this->h->table('role')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('id', $id)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new RoleBadRequestException();
            }
        } else {
            throw new RoleNotFoundException();
        }
    }

    /** 
     * @param int $id
     * @param Role $role
     * @return Role
     * @throws RoleNotFoundException
     */
    public function updateRole(int $id, Role $role): Role
    {
        $dinasId = $this->dinasService->findOneById($role->dinasId);
        if ($dinasId) {
            $oldRole = $this->findOneById($id);
            if ($oldRole) {
                try {
                    $update = $this->h->table('role')->update(array_filter($role->toArray()))->where('id', $id)->execute();
                    if ($update) {
                        return $this->findOneById($id);
                    }
                } catch (Exception $e) {
                    throw new RoleBadRequestException();
                }
            } else {
                throw new RoleNotFoundException();
            }
        } else {
            throw new DinasNotFoundException();
        }
    }

    /**
     * @param int $id
     * @param int $idPermission
     * @return Role
     * @throws RoleNotFoundException
     */
    public function addRolePermission(int $id, int $idPermission): Role
    {
        $role = new Role();

        $dataRole = $this->h->table('role')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$dataRole) {
            throw new RoleNotFoundException();
        }

        $dataPermission = $this->h->table('permission')->select()->where('id', $idPermission)->whereNull('deleteAt')->get();
        if (!$dataPermission) {
            throw new PermissionNotFoundException();
        }

        $this->h->table('role_permission')->insert([
            'roleId' => $id, 'permissionId' => $idPermission, 'createAt' => date('Y-m-d H:i:s'),
        ])->execute();

        $fixRole = $role->fromArray($dataRole);

        return $fixRole;
    }

    /**
     * @param int $id
     * @param int $idPermission
     * @return bool
     * @throws RoleNotFoundException
     * */
    public function deleteRolePermission(int $id, int $idPermission): bool
    {
        $role = $this->findOneById($id);
        if ($role) {
            try {
                $delete = $this->h->table('role_permission')->update(['deleteAt' => date('Y-m-d H:i:s')])->where('roleId', $id)->where('permissionId', $idPermission)->execute();
                if ($delete) {
                    return true;
                }
            } catch (Exception $e) {
                throw new PermissionBadRequestException();
            }
        } else {
            throw new RoleNotFoundException();
        }
    }
    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * 
     */
    public function getAllPermissionOneRole(int $id): array
    {
        $role = $this->h->table('role')->select()->where('id', $id)->whereNull('deleteAt')->get();
        if (!$role) {
            throw new RoleNotFoundException();
        }

        $rolePermission = $this->h->table('role_permission')
            ->select(['permission.id', 'permission.name', 'permission.description'])
            ->join('permission', 'permission.id', '=', 'role_permission.permissionId')
            ->where('role_permission.roleId', $id)
            ->whereNull('role_permission.deleteAt')
            ->get();

        $permissions = [];
        foreach ($rolePermission as $permissionData) {
            $permission = new Permission(
                id: $permissionData['id'],
                name: $permissionData['name'],
                description: $permissionData['description']
            );
            $permissions[] = $permission;
        }
        return $permissions;
    }
}
