<?php

declare(strict_types=1);

namespace App\Application\Actions\Rekening\KelompokRekening;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningRepository;
use App\Application\Validator\ValidInterface;

abstract class KelompokRekeningAction extends Action
{
    protected KelompokRekeningRepository $kelompokRekeningRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,KelompokRekeningRepository $kelompokRekeningRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->kelompokRekeningRepository = $kelompokRekeningRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
