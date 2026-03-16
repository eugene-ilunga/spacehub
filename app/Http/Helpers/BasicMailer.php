<?php

namespace App\Http\Helpers;

use App\Models\BasicSettings\Basic;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Throwable;

class BasicMailer
{
  public static function sendMail($data)
  {
    // Get SMTP configuration from database
    $info = Basic::select(
      'website_title',
      'smtp_status',
      'smtp_host',
      'smtp_port',
      'encryption',
      'smtp_username',
      'smtp_password',
      'from_mail',
      'from_name'
    )->first();

    // Check if SMTP is enabled but credentials are incomplete
    if ($info->smtp_status == 1 && !self::validateSmtpCredentials($info)) {
      Session::flash('warning', __('SMTP credentials are incomplete. Please configure your mail settings') . '.');
      return;
    }

    // Configure and send email
    if ($info->smtp_status == 1) {
      self::configureSmtp($info);
      self::sendEmailWithSmtp($data, $info);
    }
  }

  protected static function validateSmtpCredentials($info)
  {
    return !empty($info->smtp_host)
      && !empty($info->smtp_port)
      && !empty($info->smtp_username)
      && !empty($info->smtp_password);
  }

  protected static function configureSmtp($info)
  {
    Config::set('mail.mailers.smtp', [
      'transport' => 'smtp',
      'host' => $info->smtp_host,
      'port' => $info->smtp_port,
      'encryption' => $info->encryption,
      'username' => $info->smtp_username,
      'password' => $info->smtp_password,
      'timeout' => null,
      'auth_mode' => null,
    ]);
  }

  protected static function sendEmailWithSmtp($data, $info)
  {

    try {
      Mail::send([], [], function (Message $message) use ($data, $info) {
        $message->to($data['recipient'])
          ->subject($data['subject'])
          ->from($info->from_mail, $info->from_name)
          ->html($data['body'], 'text/html');

        //Set reply-to if customer_email is present
        if (!empty($data['customer_email'])) {
          $message->replyTo($data['customer_email']);
        }

        if (array_key_exists('invoice', $data)) {
          $message->attach($data['invoice'], [
            'as' => 'invoice.pdf',
            'mime' => 'application/pdf'
          ]);
        }
      });

      if (array_key_exists('sessionMessage', $data)) {
        Session::flash('success', $data['sessionMessage']);
      }
    } catch (Throwable $e) {

      Session::flash('warning', 'Mail could not be sent. Error: ' . self::cleanError($e->getMessage()));
    }
  }

  protected static function cleanError($error)
  {
    // Remove sensitive information from error messages
    return Str::limit(preg_replace('/password.*/i', '[credentials hidden]', $error), 120);
  }
}
