<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Invoice;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $shipment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $shipment)
    {
        $this->data = $data;
        $this->shipment = $shipment;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = app('dompdf.wrapper')->loadView('emails.invoice', ['shipment' => $this->data['shipment']]);
        // return $this->markdown('emails/InvoiceMail');
        return $this->from('millers@millers.com')
                    // ->view('emails.InvoiceMail')
                    ->markdown('emails/invoice')
                    ->with([
                        'subject' => 'invoice'
                    ])
                    ->attachData($pdf->output(), 'name.pdf', [
                        'mime' => 'application/pdf',
                    ])
                    ->subject( 'invoice' );
    }
}

