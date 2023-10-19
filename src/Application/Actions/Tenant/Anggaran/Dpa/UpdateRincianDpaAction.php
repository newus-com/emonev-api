<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa;

use Exception;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class UpdateRincianDpaAction extends DpaAction
{
    private $rule = [
        "kegiatanId" => "required",
        "unitId" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_INFORMASI_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }

        try {
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $Dpa = new Dpa();
            $newBody = [
                "kegiatanId" => $body['kegiatanId'],
                "unitId" => $body['unitId'],
                "updateAt" => (string)date('Y-m-d H:i:s')
            ];
            $putDataDpa = $Dpa->fromArray($newBody);
            $updateService = $this->dpaRepository->updateRincian((int)$this->args['id'], $putDataDpa);
            
            return $this->respondWithData(data: $updateService, message: "Berhasil menyimpan Rincian DPA", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
