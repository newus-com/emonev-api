<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Unit;

use App\Domain\Organisasi\Unit\Unit;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUnitAction extends UnitAction
{
    private $rule = [
        'organisasiId' => 'required',
        'kode' => 'required',
        'nomenklatur' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_UNIT', $permission)) {
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
            $unit = new Unit();
            $putDataUnit = $unit->fromArray($body);
            $insertService = $this->unitRepository->create($putDataUnit);
            return $this->respondWithData(data: $insertService, message: "Berhasil menambah unit", statusCode: 201);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
