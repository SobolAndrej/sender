<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/config.php');

$sender = new \Sender\Service\Sms(API_KEY);

$timeFile = __DIR__ . '/time.txt';
$time = time();
$first = false;

if (!file_exists($timeFile)) {
    $file = fopen($timeFile, "w");
    fwrite($file, (string)$time);
    fclose($file);
    $first = true;
}

// one request per second
if (!$first || !($time > (int)file_get_contents($timeFile))) {
    sleep(1);
}

try {
    $message = new \Sender\Model\Sms(FROM, MESSAGE, TO);

    $result = json_encode($sender->send($message));
} catch (\Exception $e) {
    $result = json_encode(['status' => $sender::STATUS_INTERNAL_ERROR, 'message' => $e->getMessage()]);
}

echo $result;
