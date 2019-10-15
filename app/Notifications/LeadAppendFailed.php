<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAppendFailed extends Notification
{

    use Queueable;

    protected $lead;
    protected $exception;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, \Exception $exception)
    {
        $this->lead = $lead;
        $this->exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
                    ->line('Lead ' . $this->lead->id . ' failed append ' . $this->lead->failed_append_attempts . ' time(s), and is now in append_failed status.')
                    ->line('The exception was:')
                    ->line($this->exception->getMessage())
                    ->line('Please log into Conductor and troubleshoot.')
                    ->action('View Lead', route('admin.client.mapping.lead.show', [$this->lead->mapping->client, $this->lead->mapping, $this->lead], true));

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
