<?php

use Framework\Validation;
use Framework\View;

if (!function_exists('view')) {
    function view(string $template, array $data = []): View\View
    {
        static $manager;
        if (!$manager) {
            $manager = new View\Manager();
            $manager->addPath(__DIR__ . '/../resources/views');
            $manager->addPath(__DIR__ . '/../resources/images');

            $manager->addEngine('basic.php', new View\Engine\BasicEngine());
            $manager->addEngine('advanced.php', new View\Engine\AdvancedEngine());
            $manager->addEngine('php', new View\Engine\PhpEngine());
            $manager->addEngine('svg', new View\Engine\LiteralEngine());

            $manager->addMacro('escape', fn($value) => htmlspecialchars($value, ENT_QUOTES));
            $manager->addMacro('includes', fn(...$params) => print view(...$params));
        }

        return $manager->resolve($template, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $rules, string $sessionName = 'errors')
    {
        static $manager;

        if (!$manager) {
            $manager = new Validation\Manager();

            $manager->addRule('required', new Validation\Rule\RequiredRule());
            $manager->addRule('email', new Validation\Rule\EmailRule());
            $manager->addRule('min', new Validation\Rule\MinRule());
        }

        return $manager->validate($data, $rules, $sessionName);
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        return $_SESSION['token'];
    }
}

if (!function_exists('secure')) {
    function secure()
    {
        if (!isset($_POST['csrf']) || !isset($_SESSION['token']) ||  !hash_equals($_SESSION['token'], trim($_POST['csrf']))) {
            throw new Exception('CSRF token mismatch');
        }
    }
}

if (!function_exists('dd')) {
    function dd(...$params)
    {
        var_dump(...$params);
        die;
    }
}
