<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Bidang;

use App\Domain\Perencanaan\Bidang\Bidang;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateBidangAction extends BidangAction
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

        if (!in_array('U_BIDANG', $permission)) {
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
            $bidang = new Bidang();
            $putDataBidang = $bidang->fromArray($body);
            $updateService = $this->bidangRepository->update((int)$this->args['id'], $putDataBidang);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah bidang", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
