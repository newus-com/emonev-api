<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\SubKegiatan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Perencanaan\SubKegiatan\SubKegiatanRepository;
use App\Application\Validator\ValidInterface;

abstract class SubKegiatanAction extends Action
{
    protected SubKegiatanRepository $subKegiatanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,SubKegiatanRepository $subKegiatanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->subKegiatanRepository = $subKegiatanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
