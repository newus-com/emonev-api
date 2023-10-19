<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Organisasi;

use App\Domain\Organisasi\Organisasi\Organisasi;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateOrganisasiAction extends OrganisasiAction
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

        if (!in_array('C_ORGANISASI', $permission)) {
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
            $organisasi = new Organisasi();
            $putDataOrganisasi = $organisasi->fromArray($body);
            $insertService = $this->organisasiRepository->create($putDataOrganisasi);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah organisasi", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
