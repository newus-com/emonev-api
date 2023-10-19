<?php

declare(strict_types=1);

namespace App\Application\Actions\Satuan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Satuan\SatuanRepository;
use App\Application\Validator\ValidInterface;

abstract class SatuanAction extends Action
{
    protected SatuanRepository $satuanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,SatuanRepository $satuanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->satuanRepository = $satuanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
