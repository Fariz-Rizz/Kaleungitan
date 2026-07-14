<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ItemStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Item $item,
        public string $status, // 'verified', 'rejected', 'resolved'
        public ?string $reason = null
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => $this->buildMessage(),
            'item_id' => $this->item->id,
            'url' => route('items.show', $this->item->id),
        ];
    }

    protected function buildMessage(): string
    {
        return match ($this->status) {
            'verified' => "Laporan \"{$this->item->name}\" kamu telah diverifikasi admin.",
            'rejected' => "Laporan \"{$this->item->name}\" kamu ditolak admin."
                . ($this->reason ? " Alasan: {$this->reason}" : ''),
            'resolved' => "Laporan \"{$this->item->name}\" kamu ditandai selesai.",
            default => "Status laporan \"{$this->item->name}\" kamu diperbarui.",
        };
    }
}
