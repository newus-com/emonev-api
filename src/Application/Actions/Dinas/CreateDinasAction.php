<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use PDO;
use Exception;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateDinasAction extends DinasAction
{
    private $rule = [
        'name' => 'required',
        'email' => 'required|email',
        'noHp' => 'required|min:6',
        'address' => 'required',
        'logo' => 'required',
    ];
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $permission = $this->request->getAttribute('permission', []);

        if (!in_array('C_DINAS', $permission)) {
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
            $insert = $this->dinasRepository->create(name: $body['name'], email: $body['email'], noHp: $body['noHp'], address: $body['address'], logo: $body['logo']);
            if ($insert) {
                // $payload = $this->database($insert);
                // if ($payload['status'] == FALSE) {
                //     return $this->respondWithData(message: $payload['message'], statusCode: 400);
                // } else {
                return $this->respondWithData(message: "Berhasil menambah dinas", statusCode: 201);
                // }
            } else {
                return $this->respondWithData(message: "Gagal menambah dinas");
            }
        } catch (Exception $e) {
            return $this->respondWithData(message: $e->getMessage(), statusCode: 400);
        }
    }

    protected function database(string $id)
    {
        $config = [
            'host' => $_ENV['DBHOST'],
            'database' => $_ENV['DBNAME'],
            'username' => $_ENV['DBUSER'],
            'password' => $_ENV['DBPASS']
        ];
        $dbh = new PDO("mysql:host={$config['host']}", $config['username'], $config['password']);
        $addDB = $dbh->exec("CREATE DATABASE `dinas_$id`;");
        if ($addDB) {
            $withDB = new PDO("mysql:host={$config['host']};dbname=dinas_$id", $config['username'], $config['password']);
            try {
                $sql = "
                    CREATE VIEW `akun_rekening` AS SELECT * FROM `emonev`.`akun_rekening`;
                    CREATE VIEW `bidang` AS SELECT * FROM `emonev`.`bidang`;
                    CREATE VIEW `jenis_rekening` AS SELECT * FROM `emonev`.`jenis_rekening`;
                    CREATE VIEW `kegiatan` AS SELECT * FROM `emonev`.`kegiatan`;
                    CREATE VIEW `kelompok_rekening` AS SELECT * FROM `emonev`.`kelompok_rekening`;
                    CREATE VIEW `objek_rekening` AS SELECT * FROM `emonev`.`objek_rekening`;
                    CREATE VIEW `organisasi` AS SELECT * FROM `emonev`.`organisasi`;
                    CREATE VIEW `program` AS SELECT * FROM `emonev`.`program`;
                    CREATE VIEW `rincian_objek_rekening` AS SELECT * FROM `emonev`.`rincian_objek_rekening`;
                    CREATE VIEW `satuan` AS SELECT * FROM `emonev`.`satuan`;
                    CREATE VIEW `sub_kegiatan` AS SELECT * FROM `emonev`.`sub_kegiatan`;
                    CREATE VIEW `sub_rincian_objek_rekening` AS SELECT * FROM `emonev`.`sub_rincian_objek_rekening`;
                    CREATE VIEW `sumber_dana` AS SELECT * FROM `emonev`.`sumber_dana`;
                    CREATE VIEW `tahun` AS SELECT * FROM `emonev`.`tahun`;
                    CREATE VIEW `unit` AS SELECT * FROM `emonev`.`unit`;
                    CREATE VIEW `urusan` AS SELECT * FROM `emonev`.`urusan`;

                    CREATE TABLE `dpa` (
                        `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `noDpa` varchar(255) NOT NULL,
                        `tahunId` bigint NOT NULL,
                        `urusanId` bigint,
                        `bidangId` bigint,
                        `programId` bigint,
                        `kegiatanId` bigint,
                        `organisasiId` bigint,
                        `unitId` bigint,
                        `alokasiTahun` longtext,
                        `penggunaAnggaran` longtext,
                        `ttdDpa` longtext,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `sub_dpa` (
                        `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `dpaId` bigint NOT NULL,
                        `subKegiatanId` bigint NOT NULL,
                        `sumberDanaId` bigint NOT NULL,
                        `lokasi` text NOT NULL,
                        `target` int(11) NOT NULL,
                        `waktuPelaksanaan` varchar(255) NOT NULL,
                        `keterangan` text,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `ket_sub_dpa` (
                        `id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `subDpaId` bigint(20) NOT NULL,
                        `subRincianObjekRekeningId` bigint(20) NOT NULL,
                        `jumlahAnggaran` int(11),
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `detail_anggaran_ket_sub_dpa` (
                        `id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `ketSubDpaId` bigint(20) NOT NULL,
                        `pagu` ENUM ('1', '2', '3', '4') NOT NULL,
                        `jumlah` bigint(20) NOT NULL,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `detail_ket_sub_dpa` (
                        `id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `ketSubDpaId` bigint(20) NOT NULL,
                        `satuanId` bigint(20) NOT NULL,
                        `uraian` varchar(255) NOT NULL,
                        `spesifikasi` varchar(255) NOT NULL,
                        `koefisien` int(11) NOT NULL,
                        `harga` bigint(20) NOT NULL,
                        `ppn` bigint(20) NOT NULL,
                        `jumlah` bigint(20) NOT NULL,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `rencana_penarikan_dpa` (
                        `id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `dpaId` bigint(20) NOT NULL,
                        `pagu` ENUM ('1', '2', '3', '4') NOT NULL,
                        `bulan` varchar(255) NOT NULL,
                        `jumlah` bigint(20) NOT NULL,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      CREATE TABLE `rencana_pengambilan` (
                        `id` bigint(20) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        `subDpaId` bigint(20) NOT NULL,
                        `bulan` varchar(255) NOT NULL,
                        `pagu` ENUM ('1', '2', '3', '4') NOT NULL,
                        `realisasi` varchar(255),
                        `keteranganPermasalahan` longtext,
                        `createAt` varchar(50) NOT NULL,
                        `updateAt` varchar(50),
                        `deleteAt` varchar(50)
                      );
                      
                      ALTER TABLE `sub_dpa` ADD FOREIGN KEY (`dpaId`) REFERENCES `dpa` (`id`);
                      
                      ALTER TABLE `ket_sub_dpa` ADD FOREIGN KEY (`subDpaId`) REFERENCES `ket_sub_dpa` (`id`);
                      
                      ALTER TABLE `detail_anggaran_ket_sub_dpa` ADD FOREIGN KEY (`ketSubDpaId`) REFERENCES `ket_sub_dpa` (`id`);
                      
                      ALTER TABLE `detail_ket_sub_dpa` ADD FOREIGN KEY (`ketSubDpaId`) REFERENCES `ket_sub_dpa` (`id`);
                      
                      ALTER TABLE `rencana_penarikan_dpa` ADD FOREIGN KEY (`dpaId`) REFERENCES `dpa` (`id`);
                      
                      ALTER TABLE `rencana_pengambilan` ADD FOREIGN KEY (`subDpaId`) REFERENCES `sub_dpa` (`id`);
                      
                ";
                $withDB->exec($sql);
                $payload = [
                    "status" => TRUE,
                    "message" => "Create table on database success",
                    "data" => "",
                    "error" => ""
                ];
                return $payload;
            } catch (PDOException $e) {
                $payload = [
                    "status" => FALSE,
                    "message" => "Create table on database error",
                    "data" => "",
                    "error" => ""
                ];
                return $payload;
            }
        } else {
            $payload = [
                "status" => FALSE,
                "message" => "Create database error",
                "data" => "",
                "error" => ""
            ];
            return $payload;
        }
    }
}
