<?php

namespace App\Enums;

enum FeedbackStatuses: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case RESOLVED = 'resolved';
    case REOPENED = 'reopened';
    case CANCELLED = 'cancelled';
}
