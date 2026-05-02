<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\TicketService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderTicketsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $eventTitle = $this->order->event?->title ?? 'Your Event';
        
        return new Envelope(
            subject: 'Your Tickets for ' . $eventTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $ticketService = app(TicketService::class);
        
        $ticketsData = $this->order->tickets->map(function ($ticket) use ($ticketService) {
            return [
                'number' => $ticket->ticket_number,
                'type' => $ticket->ticketType->name,
                'qr_base64' => $ticketService->generateQrCodeBase64($ticket),
                'uuid' => $ticket->uuid,
            ];
        });

        return new Content(
            markdown: 'emails.orders.tickets',
            with: [
                'order' => $this->order,
                'event' => $this->order->event,
                'tickets' => $ticketsData,
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
