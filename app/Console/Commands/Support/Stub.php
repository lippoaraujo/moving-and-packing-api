<?php

namespace App\Console\Commands\Support;

use Nwidart\Modules\Support\Stub as SupportStub;

class Stub extends SupportStub
{
    /**
     * Set base path.
     *
     * @param string $path
     */
    public static function setBasePath($path)
    {
        static::$basePath = $path;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath()
    {
        $basePath = __DIR__;
        $this->setBasePath($basePath);
        $path = static::getBasePath() . $this->path;

        return file_exists($path) ? $path : $basePath . '/stubs' . $this->path;
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }
}
