<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Program;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteProgramAction extends ProgramAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('D_PROGRAM', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        try {
            $this->programRepository->delete((int)$this->args['id']);
            return $this->respondWithData(message: "Berhasil menghapus program", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
