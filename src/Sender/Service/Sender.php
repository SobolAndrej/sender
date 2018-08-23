<?php
declare(strict_types = 1);

namespace Sender\Service;

use Sender\Interfaces\Sendable;

abstract class Sender implements Sendable
{
    const STATUS_AUTH_FAILED = 'auth_failed';
    const STATUS_DELIVER_FAILED = 'delivery_failed';
    const STATUS_INTERNAL_ERROR = 'internal_error';
    const STATUS_SENT = 'sent';

    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
