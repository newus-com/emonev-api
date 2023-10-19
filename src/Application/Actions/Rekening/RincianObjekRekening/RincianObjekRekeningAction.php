<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\RincianObjekRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class RincianObjekRekeningAction extends Action
{
    protected RincianObjekRekeningRepository $rincianObjekRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,RincianObjekRekeningRepository $rincianObjekRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->rincianObjekRekeningRepository = $rincianObjekRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
