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
            $manager->addPath(__DIR__ . '/../resources/views');

            $manager->addEngine('basic.php', new View\Engine\BasicEngine());
            $manager->addEngine('advanced.php', new View\Engine\AdvancedEngine());
            $manager->addEngine('php', new View\Engine\PhpEngine());

            // 宏怎么样？ 现在让我们在这里添加它们
            $manager->addMacro('escape', fn($value) =>
            htmlspecialchars($value));
            $manager->addMacro('includes', fn(...$params) => print
                view(...$params));
        }
        return $manager->resolve($template, $data);
    }
}