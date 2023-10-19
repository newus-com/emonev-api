<?php

declare(strict_types=1);

use Slim\App;
use Fruitcake\Cors\CorsService;
use Slim\Exception\HttpNotFoundException;

// use Slim\Handlers\Strategies\RequestHandler;
use App\Application\Middleware\DinasMiddleware;


use App\Application\Middleware\TokenMiddleware;
use App\Application\Actions\User\EditUserAction;
use App\Application\Actions\Auth\LoginAuthAction;
use App\Application\Actions\Tahun\OneTahunAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\Dinas\ListDinasAction;
use App\Application\Actions\Role\CreateRoleAction;
use App\Application\Actions\Role\DeleteRoleAction;
use App\Application\Actions\Role\UpdateRoleAction;
use App\Application\Actions\Tahun\ListTahunAction;
use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\DeleteUserAction;
use App\Application\Actions\Satuan\OneSatuanAction;
use App\Application\Actions\User\AddRoleUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Dinas\CreateDinasAction;
use App\Application\Actions\Dinas\DeleteDinasAction;
use App\Application\Actions\Dinas\UpdateDinasAction;
use App\Application\Actions\Satuan\ListSatuanAction;
use App\Application\Actions\Tahun\ActiveTahunAction;
use App\Application\Actions\Tahun\CreateTahunAction;
use App\Application\Actions\Tahun\DeleteTahunAction;
use App\Application\Actions\Tahun\UpdateTahunAction;
use App\Application\Middleware\PermissionMiddleware;
use App\Application\Actions\Wilayah\OneWilayahAction;
use App\Application\Actions\Satuan\CreateSatuanAction;
use App\Application\Actions\Satuan\DeleteSatuanAction;
use App\Application\Actions\Satuan\UpdateSatuanAction;
use App\Application\Actions\User\DeleteRoleUserAction;
use App\Application\Actions\User\GetAllRoleUserAction;
use App\Application\Actions\Wilayah\ListWilayahAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Dinas\GetAllDinasUserAction;
use App\Application\Actions\Dinas\GetAllRoleDinasAction;
use App\Application\Actions\Dinas\GetAllUserDinasAction;
use App\Application\Actions\Wilayah\CreateWilayahAction;
use App\Application\Actions\Wilayah\DeleteWilayahAction;
use App\Application\Actions\Wilayah\UpdateWilayahAction;
use App\Application\Actions\Role\AddPermissionRoleAction;
use App\Application\Actions\User\AddPermissionUserAction;
use App\Application\Actions\Organisasi\Unit\OneUnitAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Organisasi\Unit\ListUnitAction;
use App\Application\Actions\SumberDana\OneSumberDanaAction;
use App\Application\Actions\Role\DeletePermissionRoleAction;
use App\Application\Actions\Role\GetAllPermissionRoleAction;
use App\Application\Actions\SumberDana\ListSumberDanaAction;
use App\Application\Actions\User\DeletePermissionUserAction;
use App\Application\Actions\User\GetAllPermissionUserAction;
use App\Application\Actions\Organisasi\Unit\CreateUnitAction;
use App\Application\Actions\Organisasi\Unit\DeleteUnitAction;
use App\Application\Actions\Organisasi\Unit\UpdateUnitAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\OneDpaAction;
use App\Application\Actions\SumberDana\CreateSumberDanaAction;
use App\Application\Actions\SumberDana\DeleteSumberDanaAction;
use App\Application\Actions\SumberDana\UpdateSumberDanaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\ListDpaAction;
use App\Application\Actions\Perencanaan\Bidang\OneBidangAction;
use App\Application\Actions\Perencanaan\Urusan\OneUrusanAction;
use App\Application\Actions\Perencanaan\Bidang\ListBidangAction;
use App\Application\Actions\Perencanaan\Urusan\ListUrusanAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\CreateDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\DeleteDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\DetailDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\UpdateDpaAction;
use App\Application\Actions\Perencanaan\Program\OneProgramAction;
use App\Application\Actions\Perencanaan\Bidang\CreateBidangAction;
use App\Application\Actions\Perencanaan\Bidang\DeleteBidangAction;
use App\Application\Actions\Perencanaan\Bidang\UpdateBidangAction;
use App\Application\Actions\Perencanaan\Program\ListProgramAction;
use App\Application\Actions\Perencanaan\Urusan\CreateUrusanAction;
use App\Application\Actions\Perencanaan\Urusan\DeleteUrusanAction;
use App\Application\Actions\Perencanaan\Urusan\UpdateUrusanAction;
use App\Application\Actions\Perencanaan\Kegiatan\OneKegiatanAction;
use App\Application\Actions\Perencanaan\Kegiatan\ListKegiatanAction;
use App\Application\Actions\Perencanaan\Program\CreateProgramAction;
use App\Application\Actions\Perencanaan\Program\DeleteProgramAction;
use App\Application\Actions\Perencanaan\Program\UpdateProgramAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\DetailPaguDpaAction;
use App\Application\Actions\Organisasi\Organisasi\OneOrganisasiAction;
use App\Application\Actions\Perencanaan\Kegiatan\CreateKegiatanAction;
use App\Application\Actions\Perencanaan\Kegiatan\DeleteKegiatanAction;
use App\Application\Actions\Perencanaan\Kegiatan\UpdateKegiatanAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\ListTandaTanganAction;
use App\Application\Actions\Tenant\Anggaran\Laporan\ListLaporanAction;
use App\Application\Actions\Organisasi\Organisasi\ListOrganisasiAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\OneSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\UpdateRincianDpaAction;
use App\Application\Actions\Rekening\AkunRekening\OneAkunRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\ListSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\UpdateTandaTanganAction;
use App\Application\Actions\Organisasi\Organisasi\CreateOrganisasiAction;
use App\Application\Actions\Organisasi\Organisasi\DeleteOrganisasiAction;
use App\Application\Actions\Organisasi\Organisasi\UpdateOrganisasiAction;
use App\Application\Actions\Perencanaan\SubKegiatan\OneSubKegiatanAction;
use App\Application\Actions\Rekening\AkunRekening\ListAkunRekeningAction;
use App\Application\Actions\Perencanaan\SubKegiatan\ListSubKegiatanAction;
use App\Application\Actions\Rekening\JenisRekening\OneJenisRekeningAction;
use App\Application\Actions\Rekening\ObjekRekening\OneObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\CreateSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\DeleteSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\UpdateSubDpaAction;
use App\Application\Actions\Rekening\AkunRekening\CreateAkunRekeningAction;
use App\Application\Actions\Rekening\AkunRekening\DeleteAkunRekeningAction;
use App\Application\Actions\Rekening\AkunRekening\UpdateAkunRekeningAction;
use App\Application\Actions\Rekening\JenisRekening\ListJenisRekeningAction;
use App\Application\Actions\Rekening\ObjekRekening\ListObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\ListPenggunaAnggaranAction;
use App\Application\Actions\Perencanaan\SubKegiatan\CreateSubKegiatanAction;
use App\Application\Actions\Perencanaan\SubKegiatan\DeleteSubKegiatanAction;
use App\Application\Actions\Perencanaan\SubKegiatan\UpdateSubKegiatanAction;
use App\Application\Actions\KomponenPembangunan\OneKomponenPembangunanAction;
use App\Application\Actions\Rekening\JenisRekening\CreateJenisRekeningAction;
use App\Application\Actions\Rekening\JenisRekening\DeleteJenisRekeningAction;
use App\Application\Actions\Rekening\JenisRekening\UpdateJenisRekeningAction;
use App\Application\Actions\Rekening\ObjekRekening\CreateObjekRekeningAction;
use App\Application\Actions\Rekening\ObjekRekening\DeleteObjekRekeningAction;
use App\Application\Actions\Rekening\ObjekRekening\UpdateObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\UpdatePenggunaAnggaranAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\OnePengambilanAction;
use App\Application\Actions\KomponenPembangunan\ListKomponenPembangunanAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\ListPengambilanAction;
use App\Application\Actions\Tenant\Pembangunan\Monitoring\OneMonitoringAction;
use App\Application\Actions\User\GetAllDinasUserAction as GetAllDinasFromUser;
use App\Application\Actions\Tenant\Pembangunan\Monitoring\ListMonitoringAction;
use App\Application\Actions\KomponenPembangunan\CreateKomponenPembangunanAction;
use App\Application\Actions\KomponenPembangunan\DeleteKomponenPembangunanAction;
use App\Application\Actions\KomponenPembangunan\UpdateKomponenPembangunanAction;
use App\Application\Actions\Rekening\KelompokRekening\OneKelompokRekeningAction;
use App\Application\Actions\Tenant\Pembangunan\Perencanaan\OnePerencanaanAction;
use App\Application\Actions\Rekening\KelompokRekening\ListKelompokRekeningAction;
use App\Application\Actions\Tenant\Pembangunan\Perencanaan\ListPerencanaanAction;
use App\Application\Actions\Rekening\KelompokRekening\CreateKelompokRekeningAction;
use App\Application\Actions\Rekening\KelompokRekening\DeleteKelompokRekeningAction;
use App\Application\Actions\Rekening\KelompokRekening\UpdateKelompokRekeningAction;
use App\Application\Actions\Tenant\Pembangunan\Perencanaan\UpdatePerencanaanAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\OneKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\TandaTangan\CreateTandaTanganAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\TimAnggaran\CreateTimAnggaranAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\ListKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\CreateKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DeleteKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\UpdateKetSubDpaAction;
use App\Application\Actions\Tenant\Pembangunan\Monitoring\UpdateBlankoMonitoringAction;
use App\Application\Actions\Rekening\RincianObjekRekening\OneRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Pembangunan\Perencanaan\AddKomponenPerencanaanAction;
use App\Application\Actions\Rekening\RincianObjekRekening\ListRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Pembangunan\Monitoring\UpdateDataUmumMonitoringAction;
use App\Application\Actions\Tenant\Pembangunan\Monitoring\UpdateInformasiMonitoringAction;
use App\Application\Actions\Rekening\RincianObjekRekening\CreateRincianObjekRekeningAction;
use App\Application\Actions\Rekening\RincianObjekRekening\DeleteRincianObjekRekeningAction;
use App\Application\Actions\Rekening\RincianObjekRekening\UpdateRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\RencanaPenarikan\ListRencanaPenarikanAction;
use App\Application\Actions\Rekening\SubRincianObjekRekening\OneSubRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\RencanaPenarikan\CreateRencanaPenarikanAction;
use App\Application\Actions\Rekening\SubRincianObjekRekening\ListSubRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi\OneRealisasiPengambilanAction;
use App\Application\Actions\Rekening\SubRincianObjekRekening\CreateSubRincianObjekRekeningAction;
use App\Application\Actions\Rekening\SubRincianObjekRekening\DeleteSubRincianObjekRekeningAction;

use App\Application\Actions\Rekening\SubRincianObjekRekening\UpdateSubRincianObjekRekeningAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi\ListRealisasiPengambilanAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi\CreateRealisasiPengambilanAction;
use App\Application\Actions\Tenant\Anggaran\Pengambilan\Realisasi\UpdateRealisasiPengambilanAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa\OneDetailKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa\ListDetailKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa\CreateDetailKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa\DeleteDetailKetSubDpaAction;
use App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa\UpdateDetailKetSubDpaAction;



use App\Application\Actions\Tenant\Pembangunan\Dpa\ListDpaAction as ListDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\CreateDpaAction as CreateDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\OneDpaAction as OneDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\UpdateDpaAction as UpdateDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\UpdateRincianDpaAction as UpdateRincianDpaActionPembangunan;

use App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa\ListSubDpaAction as ListSubDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa\OneSubDpaAction as OneSubDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa\CreateSubDpaAction as CreateSubDpaActionPembangunan;
use App\Application\Actions\Tenant\Pembangunan\Dpa\SubDpa\DeleteSubDpaAction as DeleteSubDpaActionPembangunan;


return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/api', function (Group $api) {
        $api->group('/v1', function (Group $v1) {
            $v1->group('/auth', function (Group $auth) {
                $auth->post('/login', LoginAuthAction::class);
            });
            $v1->group('', function (Group $in) {
                $in->group('/user', function (Group $user) {
                    $user->get('', function (Request $request, Response $response) {
                        $response->getBody()->write('Hello world!');
                        return $response;
                    });
                    $user->post('/list', ListUsersAction::class);
                    $user->post('/add', CreateUserAction::class);
                    $user->delete('/delete/{id}', DeleteUserAction::class);
                    $user->put('/edit/{id}', EditUserAction::class);
                    $user->post('/{id}/addPermission', AddPermissionUserAction::class);
                    $user->post('/{id}/addRole', AddRoleUserAction::class);
                    $user->delete('/{id}/delPermission', DeletePermissionUserAction::class);
                    $user->delete('/{id}/delRole', DeleteRoleUserAction::class);
                    $user->get('/{id}/allPermission', GetAllPermissionUserAction::class);
                    $user->get('/{id}/allRole', GetAllRoleUserAction::class);
                    $user->get('/allDinasFromUser', GetAllDinasFromUser::class);
                });
                $in->group('/role', function (Group $role) {
                    $role->post('/create', CreateRoleAction::class);
                    $role->delete('/delete/{id}', DeleteRoleAction::class);
                    $role->put('/update/{id}', UpdateRoleAction::class);
                    $role->post('/addPermission/{id}', AddPermissionRoleAction::class);
                    $role->delete('/deletePermission/{id}', DeletePermissionRoleAction::class);
                    $role->get('/{id}/allPermission', GetAllPermissionRoleAction::class);
                });
                $in->group('/dinas', function (Group $dinas) {
                    $dinas->post('/list', ListDinasAction::class);
                    $dinas->post('/create', CreateDinasAction::class);
                    $dinas->put('/edit/{id}', UpdateDinasAction::class);
                    $dinas->delete('/delete/{id}', DeleteDinasAction::class);
                    $dinas->get('/role/{id}', GetAllRoleDinasAction::class);
                    $dinas->get('/user/{id}', GetAllUserDinasAction::class);
                    $dinas->get('/dinasUser/{id}', GetAllDinasUserAction::class);
                });

                $in->group('/tahun', function (Group $tahun) {
                    $tahun->post('/list', ListTahunAction::class);
                    $tahun->get('/one/{id}', OneTahunAction::class);
                    $tahun->post('/create', CreateTahunAction::class);
                    $tahun->put('/update/{id}', UpdateTahunAction::class);
                    $tahun->get('/active/{id}', ActiveTahunAction::class);
                    $tahun->delete('/delete/{id}', DeleteTahunAction::class);
                });

                $in->group('/wilayah', function (Group $wilayah) {
                    $wilayah->post('/list', ListWilayahAction::class);
                    $wilayah->get('/one/{id}', OneWilayahAction::class);
                    $wilayah->post('/create', CreateWilayahAction::class);
                    $wilayah->put('/update/{id}', UpdateWilayahAction::class);
                    $wilayah->delete('/delete/{id}', DeleteWilayahAction::class);
                });

                $in->group('/satuan', function (Group $satuan) {
                    $satuan->post('/list', ListSatuanAction::class);
                    $satuan->get('/one/{id}', OneSatuanAction::class);
                    $satuan->post('/create', CreateSatuanAction::class);
                    $satuan->put('/update/{id}', UpdateSatuanAction::class);
                    $satuan->delete('/delete/{id}', DeleteSatuanAction::class);
                });

                $in->group('/sumberDana', function (Group $sumberDana) {
                    $sumberDana->post('/list', ListSumberDanaAction::class);
                    $sumberDana->get('/one/{id}', OneSumberDanaAction::class);
                    $sumberDana->post('/create', CreateSumberDanaAction::class);
                    $sumberDana->put('/update/{id}', UpdateSumberDanaAction::class);
                    $sumberDana->delete('/delete/{id}', DeleteSumberDanaAction::class);
                });

                $in->group('/perencanaan', function (Group $perencanaan) {
                    $perencanaan->group('/urusan', function (Group $urusan) {
                        $urusan->post('/list', ListUrusanAction::class);
                        $urusan->get('/one/{id}', OneUrusanAction::class);
                        $urusan->post('/create', CreateUrusanAction::class);
                        $urusan->put('/update/{id}', UpdateUrusanAction::class);
                        $urusan->delete('/delete/{id}', DeleteUrusanAction::class);
                    });

                    $perencanaan->group('/bidang', function (Group $bidang) {
                        $bidang->post('/list', ListBidangAction::class);
                        $bidang->get('/one/{id}', OneBidangAction::class);
                        $bidang->post('/create', CreateBidangAction::class);
                        $bidang->put('/update/{id}', UpdateBidangAction::class);
                        $bidang->delete('/delete/{id}', DeleteBidangAction::class);
                    });

                    $perencanaan->group('/program', function (Group $program) {
                        $program->post('/list', ListProgramAction::class);
                        $program->get('/one/{id}', OneProgramAction::class);
                        $program->post('/create', CreateProgramAction::class);
                        $program->put('/update/{id}', UpdateProgramAction::class);
                        $program->delete('/delete/{id}', DeleteProgramAction::class);
                    });

                    $perencanaan->group('/kegiatan', function (Group $kegiatan) {
                        $kegiatan->post('/list', ListKegiatanAction::class);
                        $kegiatan->get('/one/{id}', OneKegiatanAction::class);
                        $kegiatan->post('/create', CreateKegiatanAction::class);
                        $kegiatan->put('/update/{id}', UpdateKegiatanAction::class);
                        $kegiatan->delete('/delete/{id}', DeleteKegiatanAction::class);
                    });

                    $perencanaan->group('/subKegiatan', function (Group $subKegiatan) {
                        $subKegiatan->post('/list', ListSubKegiatanAction::class);
                        $subKegiatan->get('/one/{id}', OneSubKegiatanAction::class);
                        $subKegiatan->post('/create', CreateSubKegiatanAction::class);
                        $subKegiatan->put('/update/{id}', UpdateSubKegiatanAction::class);
                        $subKegiatan->delete('/delete/{id}', DeleteSubKegiatanAction::class);
                    });
                });

                $in->group('/rekening', function (Group $rekening) {
                    $rekening->group('/akunRekening', function (Group $akunRekening) {
                        $akunRekening->post('/list', ListAkunRekeningAction::class);
                        $akunRekening->get('/one/{id}', OneAkunRekeningAction::class);
                        $akunRekening->post('/create', CreateAkunRekeningAction::class);
                        $akunRekening->put('/update/{id}', UpdateAkunRekeningAction::class);
                        $akunRekening->delete('/delete/{id}', DeleteAkunRekeningAction::class);
                    });

                    $rekening->group('/kelompokRekening', function (Group $kelompokRekening) {
                        $kelompokRekening->post('/list', ListKelompokRekeningAction::class);
                        $kelompokRekening->get('/one/{id}', OneKelompokRekeningAction::class);
                        $kelompokRekening->post('/create', CreateKelompokRekeningAction::class);
                        $kelompokRekening->put('/update/{id}', UpdateKelompokRekeningAction::class);
                        $kelompokRekening->delete('/delete/{id}', DeleteKelompokRekeningAction::class);
                    });

                    $rekening->group('/jenisRekening', function (Group $jenisRekening) {
                        $jenisRekening->post('/list', ListJenisRekeningAction::class);
                        $jenisRekening->get('/one/{id}', OneJenisRekeningAction::class);
                        $jenisRekening->post('/create', CreateJenisRekeningAction::class);
                        $jenisRekening->put('/update/{id}', UpdateJenisRekeningAction::class);
                        $jenisRekening->delete('/delete/{id}', DeleteJenisRekeningAction::class);
                    });

                    $rekening->group('/objekRekening', function (Group $objekRekening) {
                        $objekRekening->post('/list', ListObjekRekeningAction::class);
                        $objekRekening->get('/one/{id}', OneObjekRekeningAction::class);
                        $objekRekening->post('/create', CreateObjekRekeningAction::class);
                        $objekRekening->put('/update/{id}', UpdateObjekRekeningAction::class);
                        $objekRekening->delete('/delete/{id}', DeleteObjekRekeningAction::class);
                    });

                    $rekening->group('/rincianObjekRekening', function (Group $rincianObjekRekening) {
                        $rincianObjekRekening->post('/list', ListRincianObjekRekeningAction::class);
                        $rincianObjekRekening->get('/one/{id}', OneRincianObjekRekeningAction::class);
                        $rincianObjekRekening->post('/create', CreateRincianObjekRekeningAction::class);
                        $rincianObjekRekening->put('/update/{id}', UpdateRincianObjekRekeningAction::class);
                        $rincianObjekRekening->delete('/delete/{id}', DeleteRincianObjekRekeningAction::class);
                    });

                    $rekening->group('/subRincianObjekRekening', function (Group $subRincianObjekRekening) {
                        $subRincianObjekRekening->post('/list', ListSubRincianObjekRekeningAction::class);
                        $subRincianObjekRekening->get('/one/{id}', OneSubRincianObjekRekeningAction::class);
                        $subRincianObjekRekening->post('/create', CreateSubRincianObjekRekeningAction::class);
                        $subRincianObjekRekening->put('/update/{id}', UpdateSubRincianObjekRekeningAction::class);
                        $subRincianObjekRekening->delete('/delete/{id}', DeleteSubRincianObjekRekeningAction::class);
                    });
                });

                $in->group('/organisasi', function (Group $organisasi) {
                    $organisasi->group('/organisasi', function (Group $organisasi) {
                        $organisasi->post('/list', ListOrganisasiAction::class);
                        $organisasi->get('/one/{id}', OneOrganisasiAction::class);
                        $organisasi->post('/create', CreateOrganisasiAction::class);
                        $organisasi->put('/update/{id}', UpdateOrganisasiAction::class);
                        $organisasi->delete('/delete/{id}', DeleteOrganisasiAction::class);
                    });

                    $organisasi->group('/unit', function (Group $unit) {
                        $unit->post('/list', ListUnitAction::class);
                        $unit->get('/one/{id}', OneUnitAction::class);
                        $unit->post('/create', CreateUnitAction::class);
                        $unit->put('/update/{id}', UpdateUnitAction::class);
                        $unit->delete('/delete/{id}', DeleteUnitAction::class);
                    });
                });

                $in->group('/komponenPembangunan', function (Group $komponenPebangunan) {
                    $komponenPebangunan->get('/list', ListKomponenPembangunanAction::class);
                    $komponenPebangunan->get('/one/{id}', OneKomponenPembangunanAction::class);
                    $komponenPebangunan->post('/create', CreateKomponenPembangunanAction::class);
                    $komponenPebangunan->put('/update/{id}', UpdateKomponenPembangunanAction::class);
                    $komponenPebangunan->delete('/delete/{id}', DeleteKomponenPembangunanAction::class);
                });

                $in->group('/tenant', function (Group $tenant) {
                    //enpoint tenant
                    $tenant->group('', function (Group $in) {
                        $in->group('/anggaran', function (Group $anggaran) {
                            $anggaran->group('/dpa', function (Group $dpa) {
                                $dpa->group('/rincian', function (Group $rincian) {
                                    $rincian->put('/update/{id}', UpdateRincianDpaAction::class);
                                });
                                $dpa->post('/list', ListDpaAction::class);
                                $dpa->post('/listPenggunaAnggaran', ListPenggunaAnggaranAction::class);
                                $dpa->post('/updatePenggunaAnggaran', UpdatePenggunaAnggaranAction::class);

                                $dpa->post('/listTandaTangan', ListTandaTanganAction::class);
                                $dpa->post('/updateTandaTangan', UpdateTandaTanganAction::class);

                                $dpa->post('/create', CreateDpaAction::class);
                                // $dpa->get('/detail/{id}', DetailDpaAction::class);
                                // $dpa->delete('/delete/{id}', DeleteDpaAction::class);
                                $dpa->put('/update/{id}', UpdateDpaAction::class);
                                $dpa->post('/one/{id}', OneDpaAction::class);
                            });
                            $anggaran->group('/subDpa', function (Group $subDpa) {
                                $subDpa->post('/list', ListSubDpaAction::class);
                                $subDpa->post('/one/{id}', OneSubDpaAction::class);
                                $subDpa->delete('/delete/{id}', DeleteSubDpaAction::class);
                                $subDpa->put('/update/{id}', UpdateSubDpaAction::class);
                                $subDpa->post('/create', CreateSubDpaAction::class);
                                $subDpa->group('/ketSubDpa', function (Group $ketSubDpa) {
                                    $ketSubDpa->post('/list', ListKetSubDpaAction::class);
                                    $ketSubDpa->post('/one/{id}', OneKetSubDpaAction::class);
                                    $ketSubDpa->put('/update/{id}', UpdateKetSubDpaAction::class);
                                    $ketSubDpa->delete('/delete/{id}', DeleteKetSubDpaAction::class);
                                    $ketSubDpa->post('/create', CreateKetSubDpaAction::class);
                                    //         $ketSubDpa->group('/detailKetSubDpa', function (Group $detailKetSubDpa) {
                                    //             $detailKetSubDpa->get('/list', ListDetailKetSubDpaAction::class);
                                    //             $detailKetSubDpa->get('/one/{id}', OneDetailKetSubDpaAction::class);
                                    //             $detailKetSubDpa->put('/update/{id}', UpdateDetailKetSubDpaAction::class);
                                    //             $detailKetSubDpa->delete('/delete/{id}', DeleteDetailKetSubDpaAction::class);
                                    //             $detailKetSubDpa->post('/create', CreateDetailKetSubDpaAction::class);
                                    //         });
                                });
                            });
                            $anggaran->group('/rencanaPenarikan', function (Group $rencanaPenarikan) {
                                $rencanaPenarikan->post('/list', ListRencanaPenarikanAction::class);
                                $rencanaPenarikan->post('/create', CreateRencanaPenarikanAction::class);
                                $rencanaPenarikan->post('/detailPagu/{id}', DetailPaguDpaAction::class);
                            });
                            // $anggaran->group('/timAnggaran', function (Group $timAnggaran) {
                            //     $timAnggaran->post('/create/{id}', CreateTimAnggaranAction::class);
                            // });
                            // $anggaran->group('/tandaTangan', function (Group $tandaTangan) {
                            //     $tandaTangan->post('/create/{id}', CreateTandaTanganAction::class);
                            // });
                            $anggaran->group('/pengambilan', function (Group $pengambilan) {
                                //     $pengambilan->get('/list', ListPengambilanAction::class);
                                //     $pengambilan->get('/one/{id}', OnePengambilanAction::class);
                                $pengambilan->group('/realisasi', function (Group $realisasi) {
                                    $realisasi->post('/one', OneRealisasiPengambilanAction::class);
                                    // $realisasi->get('/list/{id}/{bulan}', ListRealisasiPengambilanAction::class);
                                    $realisasi->post('/create', CreateRealisasiPengambilanAction::class);
                                    // $realisasi->put('/update/{id}', UpdateRealisasiPengambilanAction::class);
                                });
                            });
                            // $anggaran->group('/laporan', function (Group $laporan) {
                            //     $laporan->get('/list', ListLaporanAction::class);
                            // });
                        });
                        $in->group('/pembangunan', function (Group $pembangunan) {
                            $pembangunan->group('/dpa', function (Group $dpa) {
                                $dpa->post('/list', ListDpaActionPembangunan::class);
                                $dpa->post('/create', CreateDpaActionPembangunan::class);
                                $dpa->post('/one/{id}', OneDpaActionPembangunan::class);
                                $dpa->put('/update/{id}', UpdateDpaActionPembangunan::class);
                                $dpa->put('/updateRincian/{id}', UpdateRincianDpaActionPembangunan::class);
                            });
                            $pembangunan->group('/subDpa', function (Group $subDpa) {
                                $subDpa->post('/list', ListSubDpaActionPembangunan::class);
                                $subDpa->post('/one/{id}', OneSubDpaActionPembangunan::class);
                                $subDpa->delete('/delete/{id}', DeleteSubDpaActionPembangunan::class);
                                $subDpa->post('/create', CreateSubDpaActionPembangunan::class);
                            });
                            //     $pembangunan->group('/perencanaan', function (Group $perencanaan) {
                            //         $perencanaan->get('/one/{id}', OnePerencanaanAction::class);
                            //         $perencanaan->get('/list', ListPerencanaanAction::class);
                            //         $perencanaan->post('/update', UpdatePerencanaanAction::class);
                            //         $perencanaan->post('/addKomponen', AddKomponenPerencanaanAction::class);
                            //     });
                            $pembangunan->group('/monitoring', function (Group $monitoring) {
                                //         $monitoring->get('/one/{id}', OneMonitoringAction::class);
                                //         $monitoring->get('/list', ListMonitoringAction::class);
                                $monitoring->post('/updateDataUmum/{id}', UpdateDataUmumMonitoringAction::class);
                                //         $monitoring->post('/updateInformasi/{id}', UpdateInformasiMonitoringAction::class);
                                //         $monitoring->post('/updateBlanko/{id}', UpdateBlankoMonitoringAction::class);
                                //         $monitoring->post('/addKomponen', AddKomponenPerencanaanAction::class);
                            });
                        });
                    });
                })->add(DinasMiddleware::class);
            })->add(PermissionMiddleware::class)->add(TokenMiddleware::class);
        });
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};
