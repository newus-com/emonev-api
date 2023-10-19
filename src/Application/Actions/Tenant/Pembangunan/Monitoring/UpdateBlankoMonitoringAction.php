<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Monitoring;

use App\Domain\Tenant\Pembangunan\Perencanaan\Perencanaan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateBlankoMonitoringAction extends MonitoringAction
{
    private $rule = [
        "volume" => "required|numeric",
        "satuan" => "required",
        "harga" => "required|numeric",
        "persentase" => "required|numeric|max:100",
        "riil" => "required|in:sudah,belum",
        "keterangan" => "required"
    ];

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_MONITORING_PEMBANGUNAN', $permission)) {
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
            $body = $this->request->getParsedBody();
            $perencanaan = $this->perencanaanRepository->updateBlanko((int)$this->args['id'], $body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan, message: "Berhasil memperbarui data");
    }
}
