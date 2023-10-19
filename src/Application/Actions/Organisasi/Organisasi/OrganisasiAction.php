<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Organisasi;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Organisasi\Organisasi\OrganisasiRepository;
use App\Application\Validator\ValidInterface;

abstract class OrganisasiAction extends Action
{
    protected OrganisasiRepository $organisasiRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,OrganisasiRepository $organisasiRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->organisasiRepository = $organisasiRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
