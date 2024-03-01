<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $name, public ?string $body = null, public ?string $phone_number = null) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Wish ' . $this->name . ' a happy birthday!')
            ->view('mail/birthday-mail');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
