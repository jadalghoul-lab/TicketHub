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
}
