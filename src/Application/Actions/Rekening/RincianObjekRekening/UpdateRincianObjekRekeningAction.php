<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\RincianObjekRekening;

use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekening;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateRincianObjekRekeningAction extends RincianObjekRekeningAction
{
    private $rule = [
        'objekRekeningId' => 'required',
        'kode' => 'required',
        'uraianAkun' => 'required',
        'deskripsiAkun' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RINCIAN_OBJEK_REKENING', $permission)) {
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
            $rincianObjekRekening = new RincianObjekRekening();
            $putDataRincianObjekRekening = $rincianObjekRekening->fromArray($body);
            $updateService = $this->rincianObjekRekeningRepository->update((int)$this->args['id'], $putDataRincianObjekRekening);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah rincian objek rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
