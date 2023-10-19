<?php

declare(strict_types=1);

namespace App\Application\Settings;

interface SettingsInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');

    /**
     * @param string $key
     * @param void $value
     * @return mixed
     */
    public function set(string $key = '', $value = '');
}
