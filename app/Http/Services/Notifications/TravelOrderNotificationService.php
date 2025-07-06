<?php

namespace App\Http\Services\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TravelOrderNotificationService extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TravelOrder  $travelOrder,
        public string $status // 'aprovado' ou 'cancelado'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        
        return (new MailMessage)
            ->subject('Ordem de Viagem - ' . $this->status)
            ->view('emails.travel-status', [
                'user' => $notifiable,
                'travelOrder' => $this->travelOrder,
                'status' => $this->status,
                'url' => config('app.frontend_url') . '/travel-orders/' . $this->travelOrder->id,

            ]);
    }
}
