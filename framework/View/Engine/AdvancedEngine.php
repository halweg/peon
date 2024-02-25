<?php

namespace Framework\View\Engine;

use Framework\View\Engine\HasManager;
use Framework\View\View;
use function view;
class AdvancedEngine implements Engine
{
    use HasManager;
    protected array $layouts = [];
    public function render(View $view): string
    {
        $hash = md5($view->path);
        $folder = ROOT_PATH. '/storage/framework/views';
        $cached = "{$folder}/{$hash}.php";
        if (!file_exists($hash) || filemtime($view->path) > filemtime($hash)) {
            $content = $this->compile(file_get_contents($view->path));
            file_put_contents($cached, $content);
        }
        extract($view->data);
        ob_start();
        include($cached);
        $contents = ob_get_contents();
        ob_end_clean();
        if ($layout = $this->layouts[$cached] ?? null) {
            return view($layout, array_merge(
                $view->data,
                ['contents' => $contents],
            ));
        }
        return $contents;
    }
    protected function extends(string $template): static
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->layouts[realpath($backtrace[0]['file'])] = $template;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function __call(string $name, $values)
    {
        return $this->manager->useMacro($name, ...$values);
    }

    protected function compile(string $template): string
    {
        // 用 `$this->extends` 替换 `@extends`
        $template = preg_replace_callback('#@extends\(([^)]+)\)#',
            function($matches) {
                return ' <?php $this ->extends(' . $matches[1] . '); ?>';
            }, $template);

        $template = preg_replace_callback('#@if\(([^)]+)\)#',
            function($matches) {
                return ' <?php if (' . $matches[1] . '): ?>';
            }, $template);
        // 用 `endif` 替换 `@endif`
        $template = preg_replace_callback('#@endif#', function($matches) {
            return ' <?php endif ; ?>';
        }, $template);

        $template = preg_replace_callback('#\{\{([^}]+)\}\}#', function($matches) {
            return ' <?php print $this->escape(' . $matches[1] . '); ?>';
        }, $template);

        // 用 `print ...` 替换 `{!! ... !!}`
        $template = preg_replace_callback('#\{!!([^}]+)!!\}#',
            function($matches) {
                return ' <?php print ' . $matches[1] . '; ?>';
            }, $template);

        return $template;
    }
}