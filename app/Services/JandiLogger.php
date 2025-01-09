<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Monolog\Formatter\LineFormatter;

class JandiLogger
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = env('JANDI_WEBHOOK_URL');
        $this->stacktrace = false;
    }

    public function sendLog($level, $message, $context = [], $record)
    {

        if (!$this->webhookUrl) {
            throw new \Exception('JANDI webhook URL is not configured.');
        }

        // Map màu sắc theo mức độ log
        $colors = [
            'emergency' => '#FF0000', // đỏ
            'alert' => '#FF4500',     // cam đậm
            'critical' => '#FF6347',  // cam
            'error' => '#FFA500',     // vàng cam
            'warning' => '#FFD700',   // vàng
            'notice' => '#ADFF2F',    // xanh lá nhạt
            'info' => '#00BFFF',      // xanh da trời
            'debug' => '#778899',     // xám
        ];

        $color = $colors[strtolower($level)] ?? '#000000'; // Mặc định màu đen nếu không có mức độ

        # Build connectInfo
        # Get message
        $message = new LineFormatter('%message%', null, true, true);
        $message = $message->format($record);
        if($message) $payload['connectInfo'][] = [
            'title' => 'Message',
            'description' => $message
        ];

        # Get extra
        $extra = new LineFormatter('%extra%', null, true, true);
        $extra = $extra->format($record);
        if($extra) $payload['connectInfo'][] = [
            'title' => 'Extra',
            'description' => $extra
        ];

        # Get context & stacktrace
        $context = new LineFormatter('%context%', null, true, true);
        if ($this->stacktrace) $context->includeStacktraces();
        $context = $context->format($record);
        if($context) $payload['connectInfo'][] = [
            'title' => 'Context & Stacktrace',
            'description' => $context
        ];

        // Chuẩn bị payload
        $payload['body'] = strtoupper($level) . ': ' . $message;
        $payload['connectColor'] = $color;


        // Gửi log đến JANDI
        Http::post($this->webhookUrl, $payload);
    }
}

