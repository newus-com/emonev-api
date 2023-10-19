<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Program;

use App\Domain\Perencanaan\Program\Program;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateProgramAction extends ProgramAction
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

        if (!in_array('U_PROGRAM', $permission)) {
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
            $program = new Program();
            $putDataProgram = $program->fromArray($body);
            $updateService = $this->programRepository->update((int)$this->args['id'], $putDataProgram);
            return $this->respondWithData(data: $updateService, message: "Berhasil mengubah program", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
