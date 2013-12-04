<?php

namespace Way\Generators\Generators;

use Way\Generators\Cache;
use Illuminate\Filesystem\Filesystem as File;

class RequestedCacheNotFound extends \Exception {}

abstract class Generator {

    /**
     * File path to generate
     *
     * @var string
     */
    public $path;

    /**
     * Perform scaffolding
     *
     * @var bool
     */
    public $scaffold;

    /**
     * File system instance
     * @var File
     */
    protected $file;

    /**
     * Cache
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param \Illuminate\Filesystem\Filesystem $file
     * @param \Way\Generators\Cache $cache
     */
    public function __construct(File $file, Cache $cache)
    {
        $this->scaffold = false;

        $this->file = $file;
        $this->cache = $cache;
    }

    /**
     * Compile template and generate
     *
     * @param  string $path
     * @param  string $template Path to template
     * @return boolean
     */
    public function make($path, $template)
    {
        $this->name = basename($path, '.php');
        $this->path = $this->getPath($path);
        $template = $this->getTemplate($template, $this->name);

        if (! $this->file->exists($this->path))
        {
            return $this->file->put($this->path, $template) !== false;
        }

        return false;
    }

    /**
     * Get the path to the file
     * that should be generated
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path)
    {
        // By default, we won't do anything, but
        // it can be overridden from a child class
        return $path;
    }

    /**
     * Get compiled template
     *
     * @param  string $template
     * @param  string $name Name of file
     * @return string
     */
    abstract protected function getTemplate($template, $name);
}