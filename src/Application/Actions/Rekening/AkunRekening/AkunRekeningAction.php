<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\AkunRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\AkunRekening\AkunRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class AkunRekeningAction extends Action
{
    protected AkunRekeningRepository $akunRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,AkunRekeningRepository $akunRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->akunRekeningRepository = $akunRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
