<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
class Notify
{
    use Notifiable;
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_URL');
    }
}