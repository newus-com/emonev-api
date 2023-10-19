<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\ObjekRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class ObjekRekeningAction extends Action
{
    protected ObjekRekeningRepository $objekRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,ObjekRekeningRepository $objekRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->objekRekeningRepository = $objekRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
