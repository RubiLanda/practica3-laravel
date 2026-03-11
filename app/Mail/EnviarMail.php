<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EnviarMail extends Mailable
{
    use Queueable, SerializesModels;
    public User $user;
    public string $link;
    public string $codigo;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $link, string $codigo)
    {
        $this->user=$user;
        $this->link=$link;
        $this->codigo=$codigo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factor de Autenticación',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contenido',
            with:[
                'name'=>$this->user->name,
                'link' => $this->link,
                'codigo' => $this->codigo
            ]
        );
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
