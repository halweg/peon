<?php
use Framework\View;

if (!function_exists('view')) {
    /**
     * @throws Exception
     */
    function view(string $template, array $data = []): string
    {
        static $manager;
        if (!$manager) {
            $manager = new View\Manager();
            // 为视图文件夹添加一个路径
            // 这样管理器就知道在哪里查找视图
            $manager->addPath(__DIR__ . '/../resources/views');
            // 还将开始添加新的引擎类
            // 以及它们预期的扩展名，以便能够选适合模板的合适引擎
            $manager->addEngine('basic.php', new View\Engine\BasicEngine());
            $manager->addEngine('php', new View\Engine\PhpEngine());
        }
        return $manager->render($template, $data);
    }
}