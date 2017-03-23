<?php

namespace Snelling\Maestro;

use RuntimeException;
use UnexpectedValueException;

class Filesystem
{

    private $dir;
    private $configFile;
    private $basePath;
    private $configPath;
    private $scriptsPath;

    public function __construct(string $cwd)
    {
        $this->dir         = __DIR__;
        $this->configFile  = 'configuration.json';
        $this->basePath    = $cwd . '/maestro';
        $this->configPath  = $this->basePath . '/' . $this->configFile;
        $this->scriptsPath = $this->basePath . '/scripts';
    }

    public function assertPathExists($path)
    {
        if (!is_dir($path) && !file_exists($path)) {
            throw new RuntimeException($path . ' does not exist.');
        }

        return true;
    }


    public function assertPathDoesNotExist($path)
    {
        if (is_dir($path) && file_exists($path)) {
            throw new RuntimeException($path . ' exists.');
        }

        return true;
    }

    public function createPath($path)
    {
        if (!@mkdir($path) && !is_dir($path)) {
            throw new RuntimeException('Could not create path ' . $path);
        }

        return true;
    }

    public function initBaseDir(): bool
    {
        $this->assertPathDoesNotExist($this->basePath);
        $this->createPath($this->basePath);

        return true;
    }

    public function initConfigFile(): bool
    {
        $this->assertPathExists($this->basePath);
        $this->assertPathDoesNotExist($this->configPath);
        $sampleConfig = file_get_contents($this->dir . '/sample/configuration.json');
        file_put_contents($this->basePath . '/' . $this->configFile, $sampleConfig);

        return true;
    }

    public function initScriptsDir(): bool
    {
        $this->assertPathExists($this->basePath);
        $this->assertPathDoesNotExist($this->scriptsPath);
        $this->createPath($this->scriptsPath);

        $scripts = array_diff(scandir($this->dir . '/sample/scripts'), ['.', '..']);

        foreach ($scripts as $script) {
            $contents = file_get_contents($this->dir . '/sample/scripts/' . $script);
            file_put_contents($this->scriptsPath . '/' . $script, $contents);
        }

        return true;
    }

    public function listSciptsDir(): array
    {
        $this->assertPathExists($this->scriptsPath);

        return array_diff(scandir($this->scriptsPath), ['.', '..']);
    }

    public function getScript(string $script)
    {
        $scriptPath = $this->scriptsPath . '/' . $script;
        $this->assertPathExists($scriptPath);

        return file_get_contents($scriptPath);
    }

    public function parseConfigFile(): array
    {
        $this->assertPathExists($this->configPath);
        $configuration = file_get_contents($this->configPath);
        $parsed        = json_decode($configuration, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnexpectedValueException('The ' . $this->configFile . ' file does not look like valid JSON.');
        }

        return $parsed;
    }
}