<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Dpa;

use App\Domain\Tenant\Pembangunan\Dpa\Dpa;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateDpaAction extends DpaAction
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

        if (!in_array('C_DPA_PEMBANGUNAN', $permission)) {
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
            $Dpa = new Dpa();
            $newBody = [
                "noDpa" => $body['noDpa'],
                "tahunId" => $body['tahunId'],
                "dinasId" => $body['dinasId'],
                "jumlahAlokasi" => $body['jumlahAlokasi'],
                "createAt" => (string)date('Y-m-d H:i:s')
            ];


            $putDataDpa = $Dpa->fromArray($newBody);

            $insertService = $this->dpaRepository->create($putDataDpa);

            $return = $insertService;

            return $this->respondWithData(data: $return, message: "Berhasil menambah Informasi DPA", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
