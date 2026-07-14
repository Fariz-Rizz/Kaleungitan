<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewClaimNotification extends Notification
{
    use Queueable;

    public function __construct(public Claim $claim) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $itemName = $this->claim->item->name ?? 'barang';

        return [
            'message' => "Ada pengajuan klaim baru untuk barang temuan \"{$itemName}\" kamu.",
            'item_id' => $this->claim->item_id,
            'url' => route('items.show', $this->claim->item_id),
        ];
    }
}
