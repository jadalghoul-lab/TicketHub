<?php

namespace App\Services;

use App\Models\Ticket;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TicketService
{
    /**
     * Generate a QR code SVG for a ticket.
     */
    public function generateQrCode(Ticket $ticket): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        
        return $writer->writeString($ticket->uuid);
    }

    /**
     * Generate a QR code as a base64 encoded SVG.
     */
    public function generateQrCodeBase64(Ticket $ticket): string
    {
        $svg = $this->generateQrCode($ticket);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Validate a ticket and mark it as used.
     */
    public function validateAndCheckIn(string $code, int $eventId, int $scannerUserId): array
    {
        $ticket = Ticket::where('event_id', $eventId)
            ->where(function ($query) use ($code) {
                $query->where('uuid', $code)
                      ->orWhere('ticket_number', $code);
            })
            ->first();

        if (!$ticket) {
            return ['success' => false, 'message' => 'Invalid ticket code.'];
        }

        if ($ticket->status === 'used') {
            return [
                'success' => false, 
                'message' => 'Ticket already used at ' . ($ticket->scanned_at ? $ticket->scanned_at->format('H:i:s') : 'unknown time'),
                'ticket' => $ticket
            ];
        }

        if ($ticket->status !== 'valid') {
            return ['success' => false, 'message' => 'Ticket is ' . $ticket->status . '.'];
        }

        $ticket->update([
            'status' => 'used',
            'scanned_at' => now(),
            'scanned_by' => $scannerUserId
        ]);

        return [
            'success' => true,
            'message' => 'Access Granted!',
            'ticket' => $ticket->load(['user', 'ticketType'])
        ];
    }
}
