<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use Composite\Entity\AbstractEntity;

class RincianObjekRekening extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $objekRekeningId
     * @param string|null $kode
     * @param string|null $uraianAkun
     * @param string|null $deskripsiAkun
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $objekRekeningId = null,
        public string|null $kode = null,
        public string|null $uraianAkun = null,
        public string|null $deskripsiAkun = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
