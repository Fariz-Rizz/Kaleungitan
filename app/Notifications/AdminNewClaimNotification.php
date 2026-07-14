<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNewClaimNotification extends Notification
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
        $claimantName = $this->claim->user->name ?? 'Seorang user';

        return [
            'message' => "{$claimantName} mengajukan klaim atas barang temuan \"{$itemName}\". Perlu verifikasi.",
            'item_id' => $this->claim->item_id,
            'url' => route('admin.items.show', $this->claim->item_id),
        ];
    }
}
