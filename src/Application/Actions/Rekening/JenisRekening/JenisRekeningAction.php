<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\JenisRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\JenisRekening\JenisRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class JenisRekeningAction extends Action
{
    protected JenisRekeningRepository $jenisRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,JenisRekeningRepository $jenisRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->jenisRekeningRepository = $jenisRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
