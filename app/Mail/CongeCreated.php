<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CongeCreated extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $conge;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $user, $conge)
    {
        $this->conge=$conge;
        $this->user=$user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Acceptation de votre demande de congé',
           
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.emailCreated',
            with: [
                'userName' => $this->user->first_name.' '.$this->user->last_name,
                'conge' => $this->conge,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
