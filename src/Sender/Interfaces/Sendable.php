<?php
declare(strict_types = 1);

namespace Sender\Interfaces;

use Sender\Model\Message;

interface Sendable
{
    public function send(Message $message);
}
