<?php

namespace App\Responses;

use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse;

class CustomEmailVerificationNotificationSentResponse implements EmailVerificationNotificationSentResponse
{
    public function toResponse($request)
    {
        session(['verification_email_sent' => true]);

        return back()->with('status', 'verification-link-sent');
    }
}