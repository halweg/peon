<?php
namespace Root\Design;

class FileLogger implements LoggerInterface
{
    protected string $logPath;

    protected FormatterInterface $formatter;

    public function __construct(string $logPath, FormatterInterface $formatter)
    {
        $this->logPath   = $logPath;
        $this->formatter = $formatter;
    }

    public function log(string $message): void
    {
        file_put_contents($this->logPath, $this->formatter->formatter($message));
    }
}