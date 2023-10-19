<?php

declare(strict_types=1);

namespace App\Application\Actions\Wilayah;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Wilayah\WilayahRepository;
use App\Application\Validator\ValidInterface;

abstract class WilayahAction extends Action
{
    protected WilayahRepository $wilayahRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,WilayahRepository $wilayahRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->wilayahRepository = $wilayahRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
