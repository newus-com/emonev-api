<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Urusan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Perencanaan\Urusan\UrusanRepository;
use App\Application\Validator\ValidInterface;

abstract class UrusanAction extends Action
{
    protected UrusanRepository $urusanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,UrusanRepository $urusanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->urusanRepository = $urusanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
