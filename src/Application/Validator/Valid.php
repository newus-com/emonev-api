<?php

declare(strict_types=1);

namespace App\Application\Validator;

use Rakit\Validation\Validator;


class Valid implements ValidInterface
{
    public $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }
    /**
     * @return mixed
     */
    public function validator()
    {
        return $this->validator;
    }
}
