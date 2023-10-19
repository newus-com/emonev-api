<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Pembangunan\Perencanaan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tenant\Pembangunan\Perencanaan\PerencanaanRepository;
use App\Application\Validator\ValidInterface;

abstract class PerencanaanAction extends Action
{
    protected PerencanaanRepository $perencanaanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,PerencanaanRepository $perencanaanRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->perencanaanRepository = $perencanaanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
