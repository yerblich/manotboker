<?php

namespace App\Jobs;
use App\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendOrder;

class sendOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $supplier;
    protected $date;
    protected $timesSent;
      protected $attachment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Supplier $supplier, $date, $timesSent,$attachment)
    {
      $this->supplier = $supplier;
      $this->date = $date;
      $this->timesSent = $timesSent;
        $this->attachment = $attachment;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::to(   $this->supplier->email)->send(new sendOrder($this->date, $this->timesSent,$this->attachment));
    }
}
