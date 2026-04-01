<?php

namespace Modules\SMS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SMS\Entities\SmsLog;
use Modules\SMS\Services\WhatsAppService;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    protected $phone;
    protected $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle(WhatsAppService $whatsApp)
    {
        $sms = SmsLog::create([
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        try {

            $response = $whatsApp->send($this->phone, $this->message);


            $sms->update([
                'status' => 'sent',
                'response' => json_encode($response),
            ]);

        } catch (\Exception $e) {


            $sms->update([
                'status' => 'failed',
                'response' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
