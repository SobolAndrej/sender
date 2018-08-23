<?php
declare(strict_types = 1);

namespace Sender\Exception;

use RuntimeException;

class IncorrectMessage extends RuntimeException
{
    public $message = 'Message is incorrect. One or several fields are empty';
}
