<?php
declare(strict_types = 1);

namespace Sender\Service;

use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;
use MessageBird\Objects\Message as SmsMessage;
use Sender\Model\Message;
use Sender\Model\Sms as SmsModel;

class Sms extends Sender
{
    private $isUnicode;

    public function send(Message $message)
    {
        $client = new Client($this->apiKey);
        $messages = $this->createMessage($message);

        try {
            foreach ($messages as $sms) {
                $client->messages->create($sms);
            }

            $result = ['status' => self::STATUS_SENT, 'message' => 'Message was sent successfully'];
        } catch (AuthenticateException $e) {
            $result = ['status' => self::STATUS_AUTH_FAILED, 'message' => $e->getMessage()];
        } catch (BalanceException $e) {
            $result = ['status' => self::STATUS_DELIVER_FAILED, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            $result = ['status' => self::STATUS_INTERNAL_ERROR, 'message' => $e->getMessage()];
        }

        return $result;
    }

    private function createMessage(Message $message): array
    {
        $this->isUnicode = false;
        $messages = [];

        $body = $message->getBody();

        if (mb_strlen($body) != strlen($body)) {
            $this->isUnicode = true;
        }

        $length = $this->isUnicode ? SmsModel::MAX_LENGTH_UNICODE : SmsModel::MAX_LENGTH;

        if (strlen($body) > $length) {
            $parts = str_split($body, SmsModel::SPLIT_LENGTH);
            $total = count($messages);
            $process = mt_rand(0, 255);

            foreach ($parts as $key => $part) {
                $messages[] = $this->messageInstance(
                    $message->getOriginator(),
                    $message->getRecipients(),
                    $part,
                    $this->createUdh($key + 1, $total, $process)
                );
            }
        } else {
            $messages[] = $this->messageInstance($message->getOriginator(), $message->getRecipients(), $body);
        }

        return $messages;
    }

    private function messageInstance(string $from, array $recipients, string $body, string $header = null): SmsMessage
    {
        $sms = new SmsMessage();
        $sms->originator = $from;
        $sms->recipients = $recipients;
        $sms->body = $body;
        $sms->datacoding = $this->isUnicode ? SmsMessage::DATACODING_UNICODE : SmsMessage::DATACODING_PLAIN;
        if (!is_null($header)) {
            $sms->setBinarySms($header, $body);
        }

        return $sms;
    }

    private function createUdh(int $index, int $total, int $process): string
    {
        // Length of User Data Header, in this case 05
        $one = '05';
        // Information Element Identifier, equal to 00 (Concatenated short messages, 8-bit reference number)
        $two = '00';
        // Length of the header, excluding the first two fields; equal to 03
        $three = '03';
        // CSMS reference number, must be same for all the SMS parts in the CSMS
        $four = str_pad(dechex($process), 2, '0', STR_PAD_LEFT);
        // Total number of parts
        $five = str_pad(dechex($total), 2, '0', STR_PAD_LEFT);
        // Part sequence
        $six = str_pad(dechex($index), 2, '0', STR_PAD_LEFT);

        $udh = implode('', [$one, $two, $three, $four, $five, $six]);

        return strtoupper($udh);
    }
}
