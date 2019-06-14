<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Shipment;
use App\User;

class scheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $shipment)
    {
        $this->user = $user;
        $this->shipment = $shipment;
        // $this->email = $email;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // var_dump($this->pdf);die;
        // return (new MailMessage)
        // ->subject('Scheduled Shipment')
        // ->markdown('mail.scheduleMail', ['url' => $url]);
        // return $this->markdown('emails/InvoiceMail');
        // $mail = $this->email;

        return $this->from('jimlaravel@gmail.com')
                    // ->view('emails.InvoiceMail')
            ->markdown('mail/scheduleNotification')
            ->to('jimkiarie8@gmail.com')
                    // ->to($mail)
                    // ->attachData($this->pdf, 'name.csv', [
                    //     'mime' => 'application/csv',
                    // ])
                    // ->attach('http://courier.dev/storage/profile/document.pdf', [
                    //     'as' => 'name.pdf',
                    //     'mime' => 'application/pdf',
                    // ])
                    // ->attach($this->pdf, ['as' => 'Your_Invoice.pdf', 'mime' => 'application/pdf'])
            ->subject('Schedule derivery');
    }
}


