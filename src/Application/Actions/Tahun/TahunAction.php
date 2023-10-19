<?php

declare(strict_types=1);

namespace App\Application\Actions\Tahun;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tahun\TahunRepository;
use App\Application\Validator\ValidInterface;

abstract class TahunAction extends Action
{
    protected TahunRepository $tahunRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,TahunRepository $tahunRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->tahunRepository = $tahunRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
