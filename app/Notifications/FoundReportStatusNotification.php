<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FoundReportStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Claim $claim,
        public string $status // 'approved', 'rejected'
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $itemName = $this->claim->item->name ?? 'barang';

        return [
            'message' => $this->status === 'approved'
                ? "Laporan penemuanmu atas \"{$itemName}\" dikonfirmasi pemilik! Silakan koordinasikan pengembalian barang."
                : "Laporan penemuanmu atas \"{$itemName}\" ternyata bukan barang yang dimaksud pemilik.",
            'item_id' => $this->claim->item_id,
            'url' => route('items.show', $this->claim->item_id),
        ];
    }
}
