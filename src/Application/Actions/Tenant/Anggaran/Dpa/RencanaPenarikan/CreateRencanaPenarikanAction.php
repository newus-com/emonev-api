<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\RencanaPenarikan;

use Exception;
use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Tenant\Anggaran\Dpa\DpaAction;

class CreateRencanaPenarikanAction extends DpaAction
{
    private $rule = [
        "dpaId" => "required"
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        //belanjaOperasiJanuari
        $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Agustus", "September", "Oktober", "November", "Desember"];
        $tipe = ["belanjaOperasi", "belanjaModal", "belanjaTidakTerduga", "belanjaTransfer"];

        foreach ($bulan as $k => $v) {
            $this->rule["rencanaPenarikan." . $v] = "required";
            foreach ($tipe as $r => $q) {
                $this->rule["rencanaPenarikan." . $v . "." . $q] = "required|numeric";
            }
        }
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('U_RENCANA_PENARIKAN_DPA', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses", statusCode: 401);
        }

        $body = $this->request->getParsedBody() == null ? [] : $this->request->getParsedBody();
        $validation = $this->valid->validator()->make($body, $this->rule);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            $newError = [];
            if (isset($errors->firstOfAll()['rencanaPenarikan'])) {
                foreach ($errors->firstOfAll()['rencanaPenarikan'] as $k => $v) {
                    if (in_array($k, $bulan)) {
                        foreach ($v as $r => $q) {
                            if (in_array($r, $tipe)) {
                                $newError[$r . $k] = $q;
                            }
                        }
                    }
                }
                if(isset($errors->firstOfAll()['dpaId'])){
                    $newError['dpaId'] = $errors->firstOfAll()['dpaId'];
                }
            } else {
                $newError = $errors->firstOfAll();
            }
            return $this->respondWithData(error: $newError, statusCode: 400);
        }

        try {
            $this->dpaRepository->createOrUpdateRencanaPenarikan($body['dpaId'], $body['rencanaPenarikan']);

            return $this->respondWithData(message: "Berhasil memperbarui data rencana penarikan", statusCode: 200);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }
}
