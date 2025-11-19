<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorArrivalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;

    /**
     * Create a new message instance.
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Visitor Arrival Notification')
                    ->view('emails.visitor_arrival')
                    ->with([
                        'name'         => $this->visitor->full_name,
                        'department'   => $this->visitor->department,
                        'purpose'      => $this->visitor->purpose,
                        'gatepass_no'  => $this->visitor->gatepass_no,
                        'time_in'      => $this->visitor->time_in,
                    ]);
    }
}
    