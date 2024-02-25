<?php
namespace Framework\View;
use Exception;
use Framework\View\Engine\Engine;
class Manager
{
    protected array $paths = [];
    protected array $engines = [];
    public function addPath(string $path): static
    {
        $this->paths[] = $path;
        return $this;
    }
    public function addEngine(string $extension, Engine $engine): static
    {
        $this->engines[$extension] = $engine;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function render(string $template, array $data = []): string
    {
        foreach ($this->engines as $extension => $engine) {
            foreach ($this->paths as $path) {
                $file = "{$path}/{$template}.{$extension}";
                if (is_file($file)) {
                    return $engine->render($file, $data);
                }
            }
        }
        throw new Exception("Could not render '{$template}'");
    }
}