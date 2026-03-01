<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailTestController extends Controller
{
    // GET /dev/send-test-email?to=you@gmail.com
    public function send(Request $request)
    {
        $to = $request->query('to', config('mail.from.address'));

        try {
            Mail::raw('Nexora test email. If you do not receive this, check SMTP settings and provider.', function ($message) use ($to) {
                $message->to($to)->subject('Nexora — Test Email');
            });
        } catch (\Exception $e) {
            Log::error('Mail send failed: '.$e->getMessage());

            // Build safe mail config snapshot for debugging
            $mailer = config('mail.default');
            $mailerConfig = config("mail.mailers.{$mailer}", []);
            // Mask sensitive fields
            if (isset($mailerConfig['username'])) $mailerConfig['username'] = '***';
            if (isset($mailerConfig['password'])) $mailerConfig['password'] = '***';

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'mail_config' => $mailerConfig,
                'hint' => 'Verify host/port, encryption and credentials; check firewall/DNS and Mailtrap dashboard.'
            ], 500);
        }

        return response()->json(['success' => true, 'message' => 'Mail sent (or queued). Check inbox and logs.']);
    }
}