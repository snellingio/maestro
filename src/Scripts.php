<?php

namespace Snelling\Maestro;

use Exception;
use RuntimeException;
use Snelling\Pattern\Parse;

class Scripts
{

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function parse(): array
    {
        $scripts = $this->filesystem->listSciptsDir();

        $parser = new Parse();
        $list   = [];

        foreach ($scripts as $script) {
            try {
                $data = $this->filesystem->getScript($script);
            } catch (Exception $e) {
                continue;
            }

            $parsedDescription = $parser->process($data, '@description {description}');
            $description       = $parsedDescription['description'] ?? '';

            $list[] = [
                'name'        => $script,
                'description' => $description,
                'script'      => $data,
            ];
        }

        return $list;
    }

    public function getByName(string $name): array
    {
        $scripts = $this->parse();
        foreach ($scripts as $script) {
            if ($script['name'] === $name) {
                return $script;
            }
        }
        throw new RuntimeException('Script ' . $name . ' does not exist.');
    }
}