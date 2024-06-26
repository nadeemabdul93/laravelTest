<?php



namespace App\Notifications;



use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;



class FileDeletedNotification extends Notification

{

    use Queueable;

    protected $token;

    /**

     * Create a new notification instance.

     *

     * @return void

     */

    public function __construct($token)

    {

        $this->token = $token;

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
                ->subject('File Deleted')
                ->greeting('Hello',$notifiable->name)

                ->line("\n")
                ->line('Your file has been deleted because it was uploaded more than 30 days ago.')

                ->salutation(config('app.name'));

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

