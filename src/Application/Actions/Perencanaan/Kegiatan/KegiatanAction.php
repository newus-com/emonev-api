<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Kegiatan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Perencanaan\Kegiatan\KegiatanRepository;
use App\Application\Validator\ValidInterface;

abstract class KegiatanAction extends Action
{
    protected KegiatanRepository $kegiatanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,KegiatanRepository $kegiatanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->kegiatanRepository = $kegiatanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
