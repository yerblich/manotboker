<?php

namespace App\Mail;
use App\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendOrder extends Mailable
{
    use Queueable, SerializesModels;
    protected $date;
    protected $timesSent;
    protected $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $timesSent,$attachment)
    {
      $this->date = $date;
      $this->timesSent = $timesSent;
      $this->attachment = $attachment;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      if( $this->timesSent > 0){
        $subject = 'order '. $this->date.'('. $this->timesSent.')';

      }else{
        $subject =  'order '. $this->date;
      }


        return $this->from('sales@manotboker.com')
        ->subject($subject)
        ->attach( $this->attachment )
        ->view('orders.supplierEmail');

    }
}
