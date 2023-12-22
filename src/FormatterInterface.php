<?php

namespace Root\Design;

interface FormatterInterface
{
    public function formatter($content) : string;
}