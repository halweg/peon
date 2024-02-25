<?php

namespace Framework\View\Engine;

class PhpEngine implements Engine
{
    protected string $path;
    public function render(string $path, array $data = []): string
    {
        $this->path = $path;
        extract($data);
        ob_start();
        include($this->path);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    protected function escape(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES);
    }
}