<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class LoginAuthAction extends AuthAction
{
    private $rule = [
        'email'                 => 'required|email',
        'password'              => 'required|min:6',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        
        $this->request->getAttribute('user',['id']);
        
        $body = $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithData(error: $errors->firstOfAll(), statusCode: 400);
        }
        try {
            $user = $this->userRepository->findOneByEmail($body['email']);
            $checkPassword = password_verify($body['password'], $user->password);
            if (!$checkPassword) {
                return $this->respondWithData(message: "Email/Password salah!", statusCode: 400);
            }
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }


        $userFull = $this->userRepository->findOneByIdFullJoin($user->id);
        // echo json_encode($userFull->specialPermission);
        // die;
        $permission = [];

        // if ($userFull['dinas'] != NULL) {
        //     foreach (json_decode($userFull['dinas'], TRUE) as $k => $v) {
        //         foreach ($v['role'] as $r => $l) {
        //             foreach ($v['permission'] as $p => $t) {
        //                 $permission[] = $t['name'];
        //             }
        //         }
        //     }
        // }

        if ($userFull->globalRole != NULL) {
            foreach ($userFull->globalRole as $k => $v) {
                foreach ($v['permission'] as $p => $t) {
                    $permission[] = $t['name'];
                }
            }
        }

        if ($userFull->specialPermission != NULL) {
            foreach ($userFull->specialPermission as $s => $p) {
                $permission[] = $p['name'];
            }
        }

        $access = $this->token->encode($user->id);
        $refresh = $this->token->encode(id: $user->id, type: 'refresh');

        $payload = [
            'accessToken' => $access,
            'refreshToken' => $refresh,
            'status' => $user->status,
            'name' => $user->name,
            'permission' => $permission
        ];

        // $this->logger->info("Users list was viewed.");

        return $this->respondWithData(data: $payload, message: "Email dan password benar");
    }
}
