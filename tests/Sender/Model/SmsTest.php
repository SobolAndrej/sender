<?php
declare(strict_types = 1);

namespace Sender\Tests\Model;

use Sender\Exception\IncorrectMessage;
use Sender\Model\Sms as SmsModel;
use PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
    public function testCorrect()
    {
        $this->assertInstanceOf(SmsModel::class, $this->createMessage('2222222', ['2222222'], 'test message'));
    }

    public function testIncorrect()
    {
        $this->expectException(IncorrectMessage::class);
        $this->createMessage('', [], '');
    }

    public function testIncorrectDataType()
    {
        $this->expectException(\TypeError::class);
        $this->createMessage('', '', 0);
    }

    private function createMessage($from, $recipients, $message)
    {
        return new SmsModel($from, $message, $recipients);
    }
}
