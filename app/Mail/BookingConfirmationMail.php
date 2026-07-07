<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your appointment at Elite Physio Clinics — '.$this->formatDate($this->booking['date']),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function formatDate(string $date): string
    {
        return \Carbon\Carbon::parse($date)->format('l j F Y');
    }

    private function buildHtml(): string
    {
        $r = $this->booking;
        $firstName = explode(' ', $r['patientName'])[0];

        return <<<HTML
<!DOCTYPE html>
<html><body style="margin:0;padding:0;background:#f4f1ea;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#0a1f13;border:1px solid #c9a042;">
        <tr><td style="padding:40px;border-bottom:1px solid rgba(201,160,66,0.2);text-align:center;">
          <div style="font-size:11px;letter-spacing:0.24em;text-transform:uppercase;color:#c9a042;font-weight:700;">Booking Confirmed</div>
          <h1 style="margin:12px 0 0;font-family:Georgia,serif;font-size:32px;font-weight:300;color:#faf6ef;">Thank you, {$firstName}</h1>
          <p style="margin:12px 0 0;color:rgba(250,246,239,0.65);font-size:14px;">Your appointment at Elite Physio Clinics has been confirmed.</p>
        </td></tr>
        <tr><td style="padding:32px 40px;color:#faf6ef;font-size:14px;line-height:1.8;">
          <p><strong>Reference:</strong> {$r['bookingReference']}</p>
          <p><strong>Treatment:</strong> {$r['conditionTitle']}</p>
          <p><strong>Date:</strong> {$this->formatDate($r['date'])}</p>
          <p><strong>Time:</strong> {$r['startTime']} – {$r['endTime']}</p>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body></html>
HTML;
    }
}
