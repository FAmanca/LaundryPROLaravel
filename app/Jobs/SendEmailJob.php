<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\LaundryStatusMail;
use Resend\Laravel\Facades\Resend;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\RateLimited;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $customerName;
    protected $email;
    protected $transactionCode;
    protected $status;

    public function __construct($customerName, $email, $transactionCode, $status)
    {
        $this->customerName = $customerName;
        $this->email =  "fadlyamanca@gmail.com"; //$email;
        $this->transactionCode = $transactionCode;
        $this->status = $status;
    }

    public function middleware()
    {
        return [new RateLimited('send-email')];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Resend::emails()->send(
        //     [
        //         'from' => 'Laundry PRO',
        //         'to' => 'fadlyamanca@gmail.com',
        //         'subject' => 'Status Laundry Anda: ' . ucfirst($this->status),
        //         'html' => view('emails.laundry-status', [
        //             'customerName' => $this->customerName,
        //             'transactionCode' => $this->transactionCode,
        //             'status' => $this->status,
        //         ])->render(),
        //     ]
        // );

        Mail::to($this->email)->send(
            new LaundryStatusMail($this->customerName, $this->transactionCode, $this->status)
        );
    }
}
