<?php

declare(strict_types=1);

namespace App\Application\Actions\SumberDana;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\SumberDana\SumberDanaRepository;
use App\Application\Validator\ValidInterface;

abstract class SumberDanaAction extends Action
{
    protected SumberDanaRepository $sumberDanaRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,SumberDanaRepository $sumberDanaRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->sumberDanaRepository = $sumberDanaRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
