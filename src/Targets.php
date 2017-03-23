<?php

namespace Snelling\Maestro;

use OutOfBoundsException;
use RuntimeException;

class Targets
{

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function parse(): array
    {
        $configuration = $this->filesystem->parseConfigFile();
        $targets       = $this->getTargets($configuration);

        $list = [];
        foreach ($targets as $name => $host) {
            list($user, $ip) = strpos($host, '@') ? explode('@', $host) : [null, $host];
            $list[] = [
                'name' => $name,
                'host' => $host,
                'user' => $user,
                'ip'   => $ip,
            ];
        }

        return $list;
    }

    public function getByName(string $name): array
    {
        $targets = $this->parse();
        foreach ($targets as $target) {
            if ($target['name'] === $name) {
                return $target;
            }
        }
        throw new RuntimeException('Target ' . $name . '  does not exist.');
    }

    public function getTargets(array $configuration): array
    {
        $targets = $configuration['targets'];

        if (count($targets) < 1) {
            throw new OutOfBoundsException('There are no targets available.');
        }

        return $targets;
    }
}