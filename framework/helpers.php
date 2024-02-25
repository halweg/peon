<?php
use Framework\View;

if (!function_exists('view')) {

    /**
     * @throws Exception
     */
    function view(string $template, array $data = [])
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

    if (!function_exists('redirect')) {
        function redirect(string $url) : int
        {
            header("Location: {$url}");
            return 1;
        }
    }

    if (!function_exists('validate')) {
        function validate(array $data, array $rules)
        {
            static $manager;
            if (!$manager) {
                $manager = new Framework\Validation\Manager();
                //添加框架附带的规则
                $manager->addRule('required', new Framework\Validation\Rule\RequiredRule());
                $manager->addRule('email', new Framework\Validation\Rule\EmailRule());
                $manager->addRule('min', new Framework\Validation\Rule\MinRule());
            }
            return $manager->validate($data, $rules);
        }
    }

    if (!function_exists('csrf')) {
        function csrf(): string
        {
            $_SESSION['token'] = bin2hex(random_bytes(32));
            return $_SESSION['token'];
        }
    }


    if (!function_exists('secure')) {

        /**
         * @throws Exception
         */
        function secure(): void
        {
            if (!isset($_POST['csrf']) || !isset($_SESSION['token']) || !hash_equals($_SESSION['token'], trim($_POST['csrf']))) {
                throw new Exception('CSRF token mismatch');
            }
        }
    }

}