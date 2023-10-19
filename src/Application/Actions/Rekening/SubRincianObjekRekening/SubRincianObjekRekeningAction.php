<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\SubRincianObjekRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class SubRincianObjekRekeningAction extends Action
{
    protected SubRincianObjekRekeningRepository $subRincianObjekRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,SubRincianObjekRekeningRepository $subRincianObjekRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->subRincianObjekRekeningRepository = $subRincianObjekRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
