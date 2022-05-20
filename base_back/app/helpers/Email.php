<?php

use Illuminate\Support\Facades\Mail;

function sendMail($email, $subject, $template, $data)
{
    $to = $email;
    $fromEmail = ENV('MAIL_FROM_ADDRESS');
    $fromName = ENV('MAIL_FROM_NAME');

    Mail::send('emails.'.$template, $data, function($message) use ($to, $subject, $fromEmail, $fromName) {
        $message->from($fromEmail, $fromName);
        $message->subject($subject);
        $message->to($to);
    });
}