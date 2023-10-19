<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Bidang;

use App\Domain\Perencanaan\Bidang\Bidang;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateBidangAction extends BidangAction
{
    private $rule = [
        'urusanId' => 'required',
        'kode' => 'required',
        'nomenklatur' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_BIDANG', $permission)) {
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
            $bidang = new Bidang();
            $putDataBidang = $bidang->fromArray($body);
            $insertService = $this->bidangRepository->create($putDataBidang);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah bidang", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
