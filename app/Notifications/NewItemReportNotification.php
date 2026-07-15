<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewItemReportNotification extends Notification
{
    use Queueable;

    public function __construct(public Item $item) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $reporterName = $this->item->user->name ?? 'Seorang user';
        $typeLabel = $this->item->type === 'hilang' ? 'kehilangan' : 'temuan';

        return [
            'message' => "{$reporterName} membuat laporan {$typeLabel} baru untuk barang \"{$this->item->name}\". Perlu verifikasi.",
            'item_id' => $this->item->id,
            'url' => route('admin.items.show', $this->item->id),
        ];
    }
}
