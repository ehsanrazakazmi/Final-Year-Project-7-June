<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Sent to administrators when a technician accepts an order.
 *
 * Previously this carried only the order id, so the admin bell could say no
 * more than "Technician Has Accepted the order id: 3" - it named neither the
 * technician nor the resident.
 */
class TechOrderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Order $order,
        protected User $technician
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * The resident who placed the order. Falls back to the name captured at
     * checkout when the order is not linked to a user account.
     */
    protected function residentName(): string
    {
        return $this->order->user->name
            ?? $this->order->name
            ?? 'a resident';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'order_accepted',
            'order_id' => $this->order->id,
            'technician_id' => $this->technician->id,
            'technician_name' => $this->technician->name,
            'resident_name' => $this->residentName(),
            'message' => sprintf(
                '%s accepted %s\'s order #%d.',
                $this->technician->name,
                $this->residentName(),
                $this->order->id
            ),
        ];
    }
}
