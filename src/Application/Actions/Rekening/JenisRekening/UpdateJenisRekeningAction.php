<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\JenisRekening;

use App\Domain\Rekening\JenisRekening\JenisRekening;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateJenisRekeningAction extends JenisRekeningAction
{
    private $rule = [
        'kelompokRekeningId' => 'required',
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

        if (!in_array('U_JENIS_REKENING', $permission)) {
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
            $jenisRekening = new JenisRekening();
            $putDataJenisRekening = $jenisRekening->fromArray($body);
            $updateService = $this->jenisRekeningRepository->update((int)$this->args['id'], $putDataJenisRekening);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah jenis rekening", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
