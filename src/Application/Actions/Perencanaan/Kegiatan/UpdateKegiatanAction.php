<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Kegiatan;

use App\Domain\Perencanaan\Kegiatan\Kegiatan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateKegiatanAction extends KegiatanAction
{
    private $rule = [
        'programId' => 'required',
        'kode' => 'required',
        'nomenklatur' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_KEGIATAN', $permission)) {
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
            $kegiatan = new Kegiatan();
            $putDataKegiatan = $kegiatan->fromArray($body);
            $updateService = $this->kegiatanRepository->update((int)$this->args['id'], $putDataKegiatan);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
