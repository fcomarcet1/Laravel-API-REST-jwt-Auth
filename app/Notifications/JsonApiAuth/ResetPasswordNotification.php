<?php

namespace App\Notifications\JsonApiAuth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Validation\ValidationException;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->getNotificationEndpoint($notifiable);
        return $this->buildMailMessage($url);
    }

    /**
     * Here the method guess your frontend endpoint to show a form to update the user password.
     * @param $notifiable
     * @return string
     * @throws ValidationException
     */
    public function getNotificationEndpoint($notifiable)
    {
        /*
        |--------------------------------------------------------------------------
        | Create your own mailable and notification
        |--------------------------------------------------------------------------
        |
        | You can even create your own mailable and notification,
        | just remember that the link for the notification is:
        | $link = config('json-api-auth.new_password_form_url') . "?token={$this->token}&email={$notifiable->getEmailForPasswordReset()}";
        |
        | The reason to work with a url from config file, is to set a url for a
        | reset password form,totally separated from the server app
        |
        |  The frontend app would have to grab the token and email query string params
        |  from the url, to automatically fill this inputs,
        |  this is simple with javascript using something like this:
        |
        |  const queryString = window.location.search;
        |
        |  const urlParams = new URLSearchParams(queryString);
        |
        |  const token = urlParams.get('token')
        |
        |  const email = urlParams.get('email')
        |
        */

        if(! $endpoint = config('json-api-auth.new_password_form_url')) {
            throw ValidationException::withMessages([
                'message' => 'There is no domain set in config/json-api-auth.php as new_password_form_url, please add a frontend endpoint to send email with the link.'
            ]);
        }

        return $endpoint . "?token={$this->token}&email={$notifiable->getEmailForPasswordReset()}";
    }
}
