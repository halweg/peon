<?php

namespace Root\Design;

class Formatter implements FormatterInterface
{
    public function formatter($content): string
    {
        return $content."\r\n";
    }
}