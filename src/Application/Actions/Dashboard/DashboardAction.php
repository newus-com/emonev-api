<?php

declare(strict_types=1);

namespace App\Application\Actions\Dashboard;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Application\Validator\ValidInterface;

abstract class DashboardAction extends Action
{

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->valid = $valid;
        $this->token = $token;
    }
}
