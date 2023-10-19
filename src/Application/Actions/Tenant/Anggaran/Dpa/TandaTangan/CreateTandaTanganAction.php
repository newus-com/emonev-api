<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\TandaTangan;

use Exception;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class CreateTandaTanganAction extends DpaAction
{
    private $rule = [
        "ttdDpa" => "required",
        "ttdDpa.headerTtd.kota" => "required",
        "ttdDpa.headerTtd.tanggal" => "required",
        "ttdDpa.bodyTtd.*.nama" => "required",
        "ttdDpa.bodyTtd.*.jabatanPejabat" => "required",
        "ttdDpa.bodyTtd.*.nip" => "required",
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
                "ttdDpa" => (string)json_encode($body['ttdDpa'], JSON_PRETTY_PRINT),
                "updateAt" => (string)date('Y-m-d H:i:s')
            ];

            $putDataDpa = $Dpa->fromArray($newBody);

            $insertService = $this->dpaRepository->update((int)$this->args['id'], $putDataDpa);

            return $this->respondWithData(data: json_decode($insertService['ttdDpa'],TRUE), message: "Berhasil mengubah data tanda tangan", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
