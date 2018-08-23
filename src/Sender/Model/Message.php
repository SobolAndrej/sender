<?php
declare(strict_types = 1);

namespace Sender\Model;

use Sender\Exception\IncorrectMessage;

abstract class Message
{
    const TYPE_SMS = 'sms';
    const TYPE_BINARY = 'binary';
    const TYPE_FLASH = 'flash';

    protected $type;
    protected $originator;
    protected $body;
    protected $recipients;

    public function __construct(string $originator, string $body, array $recipients)
    {
        $this->originator = $originator;
        $this->body = $body;
        $this->recipients = $recipients;

        if (!$this->isValid()) {
            throw new IncorrectMessage();
        }
    }

    public function getOriginator(): string
    {
        return $this->originator;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getType(): string
    {
        return $this->type;
    }

    abstract public function isValid(): bool;
}
