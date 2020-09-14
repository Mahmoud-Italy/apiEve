<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingCenterMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $row;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($row, $data)
    {
        $this->row = $row;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.trainingCenterApplication')
                    ->from('info@aips.com', 'AIPS')
                    ->subject('Your application sent successfully.');
    }
}
