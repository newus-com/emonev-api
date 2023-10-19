<?php

declare(strict_types=1);

namespace App\Application\Actions\Dashboard;

use PDO;
use Exception;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class DataLaporanAction extends DashboardAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('R_LAPORAN_PEMBANGUNAN', $permission)) {
            return $this->respondWithData(message: "Anda tidak memiliki akses");
        }

        try {
            $body = $this->request->getParsedBody();
            // $perencanaan = $this->perencanaanRepository->findAllRunning($body);
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }

        return $this->respondWithData(data: $body);
    }

    private function getConnection()
    {
        $config = [
            'host' => $_ENV['DBHOST'],
            'database' => $_ENV['DBNAME'],
            'username' => $_ENV['DBUSER'],
            'password' => $_ENV['DBPASS']
        ];

        try {
            $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception('Terjadi kesalahan saat mengambil data');
            // die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
