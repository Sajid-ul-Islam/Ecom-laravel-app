<?php

namespace App\Enums;

enum WooSyncStatus: string
{
    case Pending = 'pending';
    case Synced = 'synced';
    case Failed = 'failed';
    case Archived = 'archived';
}
