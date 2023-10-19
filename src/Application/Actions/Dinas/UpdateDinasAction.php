<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use App\Domain\Dinas\Dinas;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateDinasAction extends DinasAction
{
    private $rule = [
        'name' => 'required',
        'email' => 'required|email',
        'noHp' => 'required',
        'address' => 'required',
        'logo' => 'required',
    ];

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_DINAS', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401); 
        }

        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if($validation->fails()){
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }
        try{
            $body['updateAt'] = (string)date('Y-m-d H:i:s');
            $dinas = new Dinas();
            $putDataDinas = $dinas->fromArray($body);
            $dinasEdit = $this->dinasRepository->updateDinas((int)$this->args['id'], $putDataDinas);
            return $this->respondWithData(data: $dinasEdit, message:"Success", statusCode: 200);
        }catch(Exception $e){
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
