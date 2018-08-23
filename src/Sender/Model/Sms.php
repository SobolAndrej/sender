<?php
declare(strict_types = 1);

namespace Sender\Model;

class Sms extends Message
{
    const MAX_LENGTH = 160;
    const MAX_LENGTH_UNICODE = 70;
    const SPLIT_LENGTH = 153;

    protected $type = self::TYPE_SMS;

    public function isValid(): bool
    {
        return !empty($this->body) && !empty($this->originator) && !empty($this->recipients);
    }
}
