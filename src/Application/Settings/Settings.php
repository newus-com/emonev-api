<?php

declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return mixed
     */
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }

    /**
     * @return mixed
     */
    public function set(string $key = '', $value = '')
    {
        if (empty($key)) {
            return false;
        } else {
            $this->settings[$key] = $value;
        }
    }
}
