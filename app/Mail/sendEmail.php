<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $contactInfo;
    public $view;
    public $subject;
    public $attachment;
    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view($this->view)
      ->from('sales@manotboker.com')
      ->subject($this->subject )
      ->attach('http://manotboker.local/storage/pdfInvoices/test/invoice262.pdf' , [

                        'mime' => 'application/pdf'
                    ]);
    }
}
