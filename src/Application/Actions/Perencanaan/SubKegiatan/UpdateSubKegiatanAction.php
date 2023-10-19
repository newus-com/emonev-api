<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\SubKegiatan;

use App\Domain\Perencanaan\SubKegiatan\SubKegiatan;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSubKegiatanAction extends SubKegiatanAction
{
    private $rule = [
        'satuanId' => 'required',
        'kegiatanId' => 'required',
        'kode' => 'required',
        'nomenklatur' => 'required',
        'indikator' => 'required',
        'kinerja' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_SUB_KEGIATAN', $permission)) {
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
            $subKegiatan = new SubKegiatan();
            $putDataSubKegiatan = $subKegiatan->fromArray($body);
            $updateService = $this->subKegiatanRepository->update((int)$this->args['id'], $putDataSubKegiatan);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah sub kegiatan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
