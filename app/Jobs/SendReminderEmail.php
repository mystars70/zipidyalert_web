<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Mail;

class SendReminderEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $biz_id;
    protected $email;
    protected $type;

    public $tries = 3;
    public $timeout = 60;
    public $sleep = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($biz_id, $email, $type)
    {
        $this->biz_id = $biz_id;
        $this->email = $email;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('user.business.mailTemplate', array('biz_id' => $this->biz_id, 'email' => $this->email, 'type' => $this->type), function ($message) {
                $message->to($this->email, 'Join Us =))')->subject('Test Queue');
        });
    }
}
