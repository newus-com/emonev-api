<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Monitoring;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class ListMonitoringAction extends MonitoringAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_MONITORING_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $body = $this->request->getParsedBody();
            $perencanaan = $this->perencanaanRepository->findAllRunning($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $perencanaan);
    }
}
