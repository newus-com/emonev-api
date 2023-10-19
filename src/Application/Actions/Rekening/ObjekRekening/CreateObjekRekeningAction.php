<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\ObjekRekening;

use App\Domain\Rekening\ObjekRekening\ObjekRekening;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateObjekRekeningAction extends ObjekRekeningAction
{
    private $rule = [
        'jenisRekeningId' => 'required',
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

        if (!in_array('C_OBJEK_REKENING', $permission)) {
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
            $body['createAt'] = (string)date('Y-m-d H:i:s');
            $objekRekening = new ObjekRekening();
            $putDataObjekRekening = $objekRekening->fromArray($body);
            $insertService = $this->objekRekeningRepository->create($putDataObjekRekening);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah objek rekening", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
