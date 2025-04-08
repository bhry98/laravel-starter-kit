<?php

namespace Bhry98\LaravelStarterKit\Console\mails\auth;

use Bhry98\LaravelStarterKit\Models\users\UsersVerifyCodesModel;
use Illuminate\Mail\Mailable;

class SendResetPasswordOtpViaEmail extends Mailable
{
    public function __construct(public UsersVerifyCodesModel $otp)
    {
    }

    public function build(): SendResetPasswordOtpViaEmail
    {
        return $this->from(address: config(key: "bhry98-starter.config.mail.smtp.username"))
            ->to(address: $this->otp->user?->email)
            ->subject(subject: __(key: "bhry98::notifications.reset-password-message.title"))
            ->view(view: 'Bhry98::mails.reset-password-otp')
            ->with(['code' => $this->otp->verify_code]);
    }
}
