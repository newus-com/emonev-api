<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\TimAnggaran;

use Exception;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class CreateTimAnggaranAction extends DpaAction
{
    private $rule = [
        "timAnggaran" => "required",
        "timAnggaran.*.nama" => "required",
        "timAnggaran.*.nip" => "required",
        "timAnggaran.*.jabatan" => "required",
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_TIM_ANGGARAN_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
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
                "timAnggaran" => (string)json_encode($body['timAnggaran'], JSON_PRETTY_PRINT),
                "updateAt" => (string)date('Y-m-d H:i:s')
            ];

            $putDataDpa = $Dpa->fromArray($newBody);

            $insertService = $this->dpaRepository->update((int)$this->args['id'], $putDataDpa);

            return $this->respondWithData(data: json_decode($insertService['timAnggaran'],TRUE), message: "Berhasil mengubah data tim anggaran", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
