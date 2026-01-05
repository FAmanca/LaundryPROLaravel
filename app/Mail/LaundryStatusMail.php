<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class LaundryStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customerName;
    public $transactionCode;
    public $status;

    /**
     * Buat instance baru dari pesan email.
     */
    public function __construct($customerName, $transactionCode, $status)
    {
        $this->customerName = $customerName;
        $this->transactionCode = $transactionCode;
        $this->status = $status;
    }

    /**
     * Tentukan subject email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Laundry Anda: ' . ucfirst($this->status),
        );
    }

    /**
     * Tentukan konten email.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.laundry-status',
            with: [
                'customerName' => $this->customerName,
                'transactionCode' => $this->transactionCode,
                'status' => $this->status,
            ],
        );
    }

    /**
     * Attachment (jika ada).
     */
    public function attachments(): array
    {
        return [];
    }
}
