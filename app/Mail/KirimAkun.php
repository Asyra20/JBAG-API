<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KirimAkun extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $nama_penjual;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $subject, $nama_penjual)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->nama_penjual = $nama_penjual;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.kirimakun',
            with: [
                'data' => $this->data,
                'nama_penjual' => $this->nama_penjual,
            ],
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
