<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Bidang;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Perencanaan\Bidang\BidangRepository;
use App\Application\Validator\ValidInterface;

abstract class BidangAction extends Action
{
    protected BidangRepository $bidangRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,BidangRepository $bidangRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->bidangRepository = $bidangRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
