<?php

declare(strict_types=1);

namespace App\Application\Actions\KomponenPembangunan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\KomponenPembangunan\KomponenPembangunanRepository;
use App\Application\Validator\ValidInterface;

abstract class KomponenPembangunanAction extends Action
{
    protected KomponenPembangunanRepository $komponenPembangunanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,KomponenPembangunanRepository $komponenPembangunanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->komponenPembangunanRepository = $komponenPembangunanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
