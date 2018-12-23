<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendEmail;


class sendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $contactInfo;
    protected $view;
    public $subject;
    protected $attachment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contactInfo,$view,$subject,$attachment)
    {
      $this->contactInfo = $contactInfo;
      $this->view = $view;
      $this->subject = $subject;
      $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
          Mail::to(   $this->contactInfo->email)->send(new sendEmail($this->contactInfo, $this->view,$this->subject, $this->attachment));
    }
}
