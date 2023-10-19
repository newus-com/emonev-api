<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa;

use Exception;
use App\Domain\Tenant\Pembangunan\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Pembangunan\Dpa\DpaAction;

class UpdateDpaAction extends DpaAction
{
    private $rule = [
        "noDpa" => "required",
        "tahunId" => "required",
        "dinasId" => "required",
        "jumlahAlokasi" => "required|numeric",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_DPA_PEMBANGUNAN', $permission)) {
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
                "noDpa" => $body['noDpa'],
                "tahunId" => $body['tahunId'],
                "dinasId" => $body['dinasId'],
                "jumlahAlokasi" => $body['jumlahAlokasi'],
                "updateAt" => (string)date('Y-m-d H:i:s')
            ];
            $putDataDpa = $Dpa->fromArray($newBody);
            $updateService = $this->dpaRepository->update((int)$this->args['id'], $putDataDpa);
            
            return $this->respondWithData(data: $updateService, message: "Berhasil menyimpan Rincian DPA", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
