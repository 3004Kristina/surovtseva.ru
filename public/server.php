<?php

declare(strict_types=1);

if (isset($_POST['name'], $_POST['email'], $_POST['phone']) === false) {
    return;
}

require_once \dirname(__DIR__) . '/env.php';

function logMessage(string $type, $payload): void
{
    $dir = \dirname(__DIR__) . '/var/log';
    $date = \date('Y-m-d H:i:s');
    $payload = \is_array($payload) ? \json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $payload;

    \file_put_contents("{$dir}/{$type}.txt", "[{$date}]: {$payload}" . PHP_EOL, FILE_APPEND);
}

logMessage('request', [
    '_GET' => $_GET,
    '_POST' => $_POST,
    '_SERVER' => $_SERVER,
]);

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$ch = \curl_init();
$chatId = TELEGRAM_CHAT_ID;
$token = TELEGRAM_BOT_TOKEN;

\curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.telegram.org/bot{$token}/sendMessage",
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => \json_encode([
        'chat_id' => $chatId,
        'text' => "<b>Новая заявка с сайта</b>\n\nИмя: <b>{$name}</b>\nE-mail: <b>{$email}</b>\nТелефон: <b>{$phone}</b>",
        'parse_mode' => 'HTML',
    ]),
]);

$response = \curl_exec($ch);
$date = \date('Y-m-d H:i:s');

logMessage('telegram-response', $response);

\header('Content-Type: application/json');

echo \json_encode(['status' => 'ok']);
