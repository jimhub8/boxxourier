<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('millers@millers.com')
                    // ->view('emails.InvoiceMail')
                    ->markdown('emails/ReceiptMail')
                    ->with([
                        'subject' => $this->subject
                    ])
                    ->to($this->email_to)
                    ->subject( $this->subject );
    }
}
