<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Pembangunan\SubDpa;

use Composite\Entity\AbstractEntity;

class SubDpa extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $dpaPembangunanId
     * @param int|null $subKegiatanId
     * @param int|null $sumberDanaId
     * @param string|null $ppk
     * @param string|null $jumlahAnggaran
     * @param string|null $jenisPengadaan
     * @param string|null $mekanismePengadaan
     * @param string|null $swakelola
     * @param string|null $noKontrak
     * @param string|null $judulKontrak
     * @param string|null $nilaiKontrak
     * @param string|null $tanggalKontrak
     * @param string|null $tanggalMulai
     * @param string|null $tanggalSelesai
     * @param string|null $pelaksanaan
     * @param string|null $lokasi
     * @param string|null $kendala
     * @param string|null $tenagaKerja
     * @param string|null $penerapanK3
     * @param string|null $keterangan
     * @param string|null $informasi
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */

    public function __construct(
        public int|null $id = null,
        public int|null $dpaPembangunanId = null,
        public int|null $subKegiatanId = null,
        public int|null $sumberDanaId = null,
        public string|null $ppk = null,
        public string|null $jumlahAnggaran = null,
        public string|null $jenisPengadaan = null,
        public string|null $mekanismePengadaan = null,
        public string|null $swakelola = null,
        public string|null $noKontrak = null,
        public string|null $judulKontrak = null,
        public string|null $nilaiKontrak = null,
        public string|null $tanggalKontrak = null,
        public string|null $tanggalMulai = null,
        public string|null $tanggalSelesai = null,
        public string|null $pelaksanaan = null,
        public string|null $lokasi = null,
        public string|null $kendala = null,
        public string|null $tenagaKerja = null,
        public string|null $penerapanK3 = null,
        public string|null $keterangan = null,
        public string|null $informasi = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
