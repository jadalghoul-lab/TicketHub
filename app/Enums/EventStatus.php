<?php

namespace App\Enums;

enum EventStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SOLD_OUT = 'sold_out';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';
    case BLOCKED = 'blocked';
}
