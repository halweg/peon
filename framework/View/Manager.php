<?php
namespace Framework\View;
use Exception;
use Framework\View\Engine\Engine;
class Manager
{
    protected array $paths = [];
    protected array $engines = [];
    protected array $macros = [];
    public function addPath(string $path): static
    {
        $this->paths[] = $path;
        return $this;
    }

    public function addEngine(string $extension, Engine $engine): static
    {
        $this->engines[$extension] = $engine;
        $this->engines[$extension]->setManager($this);
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

    /**
     * @throws @Exception
     */
    public function resolve(string $template, array $data = []): View
    {
        foreach ($this->engines as $extension => $engine) {
            foreach ($this->paths as $path) {
                $file = "{$path}/{$template}.{$extension}";
                if (is_file($file)) {
                    return new View($engine, realpath($file), $data);
                }
            }
        }
        throw new Exception("无法解析'{$template}'");
    }

    public function addMacro(string $name, \Closure $closure): static
    {
        $this->macros[$name] = $closure;
        return $this;
    }
    public function useMacro(string $name, ...$values)
    {
        if (isset($this->macros[$name])) {
            $bound = $this->macros[$name]->bindTo($this);
            return $bound(...$values);
        }
        throw new Exception("Macro isn't defined: '{$name}'");
    }

}