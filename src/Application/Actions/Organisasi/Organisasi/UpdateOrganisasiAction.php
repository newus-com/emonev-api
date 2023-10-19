<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Organisasi;

use App\Domain\Organisasi\Organisasi\Organisasi;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateOrganisasiAction extends OrganisasiAction
{
    private $rule = [
        'bidangId' => 'required',
        'kode' => 'required',
        'nomenklatur' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_ORGANISASI', $permission)) {
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
            $organisasi = new Organisasi();
            $putDataOrganisasi = $organisasi->fromArray($body);
            $updateService = $this->organisasiRepository->update((int)$this->args['id'], $putDataOrganisasi);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah organisasi", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
