<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use App\Service\Role\RoleService;
use App\Service\User\UserService;
use App\Domain\Role\RoleRepository;
use App\Domain\User\UserRepository;
use App\Service\Dinas\DinasService;
use App\Service\Tahun\TahunService;
use App\Domain\Dinas\DinasRepository;
use App\Domain\Tahun\TahunRepository;
use App\Service\Satuan\SatuanService;
use App\Domain\Satuan\SatuanRepository;
use App\Service\Wilayah\WilayahService;
use App\Domain\Wilayah\WilayahRepository;

use App\Service\Tenant\Anggaran\DpaService;
use App\Service\Organisasi\Unit\UnitService;
use App\Service\SumberDana\SumberDanaService;
use App\Domain\Organisasi\Unit\UnitRepository;
use App\Service\Tenant\Anggaran\SubDpaService;
use App\Domain\SumberDana\SumberDanaRepository;
use App\Domain\Tenant\Anggaran\Dpa\DpaRepository;
use App\Service\Perencanaan\Bidang\BidangService;
use App\Service\Perencanaan\Urusan\UrusanService;


use App\Service\Tenant\Anggaran\KetSubDpaService;

use App\Service\Tenant\Pembangunan\RencanaService;
use App\Domain\Perencanaan\Bidang\BidangRepository;

use App\Domain\Perencanaan\Urusan\UrusanRepository;
use App\Service\Perencanaan\Program\ProgramService;
use App\Domain\Perencanaan\Program\ProgramRepository;
use App\Service\Perencanaan\Kegiatan\KegiatanService;
use App\Service\Tenant\Pembangunan\PerencanaanService;
use App\Domain\Perencanaan\Kegiatan\KegiatanRepository;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaRepository;
use App\Service\Tenant\Anggaran\DetailKetSubDpaService;
use App\Service\Organisasi\Organisasi\OrganisasiService;
use App\Domain\Organisasi\Organisasi\OrganisasiRepository;
use App\Service\Rekening\AkunRekening\AkunRekeningService;
use App\Service\Tenant\Anggaran\RencanaPengambilanService;
use App\Service\Perencanaan\SubKegiatan\SubKegiatanService;
use App\Domain\Rekening\AkunRekening\AkunRekeningRepository;
use App\Domain\Tenant\Pembangunan\Rencana\RencanaRepository;
use App\Service\Rekening\JenisRekening\JenisRekeningService;
use App\Service\Rekening\ObjekRekening\ObjekRekeningService;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanRepository;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaRepository;
use App\Domain\Rekening\JenisRekening\JenisRekeningRepository;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningRepository;
use App\Service\KomponenPembangunan\KomponenPembangunanService;
use App\Domain\KomponenPembangunan\KomponenPembangunanRepository;
use App\Service\Rekening\KelompokRekening\KelompokRekeningService;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningRepository;
use App\Domain\Tenant\Pembangunan\Perencanaan\PerencanaanRepository;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaRepository;
use App\Service\Rekening\RincianObjekRekening\RincianObjekRekeningService;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningRepository;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilanRepository;
use App\Service\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningService;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningRepository;


use App\Service\Tenant\Pembangunan\DpaService as DpaServicePembangunan;
use App\Domain\Tenant\Pembangunan\Dpa\DpaRepository as DpaRepositoryPembangunan;

use App\Service\Tenant\Pembangunan\SubDpaService as SubDpaServicePembangunan;
use App\Domain\Tenant\Pembangunan\SubDpa\SubDpaRepository as SubDpaRepositoryPembangunan;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(UserService::class),
        RoleRepository::class => \DI\autowire(RoleService::class),
        DinasRepository::class => \DI\autowire(DinasService::class),
        TahunRepository::class => \DI\autowire(TahunService::class),
        WilayahRepository::class => \DI\autowire(WilayahService::class),
        SatuanRepository::class => \DI\autowire(SatuanService::class),
        SumberDanaRepository::class => \DI\autowire(SumberDanaService::class),
        UrusanRepository::class => \DI\autowire(UrusanService::class),
        BidangRepository::class => \DI\autowire(BidangService::class),
        ProgramRepository::class => \DI\autowire(ProgramService::class),
        KegiatanRepository::class => \DI\autowire(KegiatanService::class),
        SubKegiatanRepository::class => \DI\autowire(SubKegiatanService::class),
        AkunRekeningRepository::class => \DI\autowire(AkunRekeningService::class),
        KelompokRekeningRepository::class => \DI\autowire(KelompokRekeningService::class),
        JenisRekeningRepository::class => \DI\autowire(JenisRekeningService::class),
        ObjekRekeningRepository::class => \DI\autowire(ObjekRekeningService::class),
        RincianObjekRekeningRepository::class => \DI\autowire(RincianObjekRekeningService::class),
        SubRincianObjekRekeningRepository::class => \DI\autowire(SubRincianObjekRekeningService::class),
        OrganisasiRepository::class => \DI\autowire(OrganisasiService::class),
        UnitRepository::class => \DI\autowire(UnitService::class),
        KomponenPembangunanRepository::class => \DI\autowire(KomponenPembangunanService::class),
        DpaRepository::class => \DI\autowire(DpaService::class),
        SubDpaRepository::class => \DI\autowire(SubDpaService::class),
        KetSubDpaRepository::class => \DI\autowire(KetSubDpaService::class),
        DetailKetSubDpaRepository::class => \DI\autowire(DetailKetSubDpaService::class),
        RencanaPengambilanRepository::class => \DI\autowire(RencanaPengambilanService::class),
        PerencanaanRepository::class => \DI\autowire(PerencanaanService::class),


        DpaRepositoryPembangunan::class => \DI\autowire(DpaServicePembangunan::class),
        SubDpaRepositoryPembangunan::class => \DI\autowire(SubDpaServicePembangunan::class),


    ]);
};
