<?php
declare(strict_types = 1);

namespace Sender\Tests\Service;

use Sender\Model\Sms as SmsModel;
use Sender\Service\Sms as SmsService;
use Sender\Tests\Base;

class SmsTest extends Base
{
    /** @var SmsService */
    private $service;
    /** @var SmsService */
    private $mock;

    public function setUp()
    {
        parent::setUp();
        $this->mock = new SmsService('');
        $this->service = new SmsService(self::TEST_API_KEY);
    }

    public function testIncorrectKey()
    {
        $message = new SmsModel('MessageBird', 'This is a test message.', [31612345678]);

        $this->assertArraySubset(['status' => SmsService::STATUS_AUTH_FAILED], $this->mock->send($message));
    }

    public function testSend()
    {
        $message = new SmsModel('MessageBird', 'This is a test message.', [31612345678]);

        $this->assertArraySubset(['status' => SmsService::STATUS_SENT], $this->service->send($message));
    }

    public function testSendLongMessage()
    {
        $message = new SmsModel(
            'MessageBird',
            'This is a test message. This is a test message. This is a test message. This is a test message. ' .
            'This is a test message. This is a test message. This is a test message.',
            [31612345678]
        );

        $this->assertArraySubset(['status' => SmsService::STATUS_SENT], $this->service->send($message));
    }
}
