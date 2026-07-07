<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New booking: {$this->booking['patientName']} — {$this->formatDate($this->booking['date'])} {$this->booking['startTime']}",
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

        return <<<HTML
<!DOCTYPE html>
<html><body style="margin:0;padding:0;background:#f4f1ea;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#0a1f13;border:1px solid #c9a042;">
        <tr><td style="padding:32px 40px;border-bottom:1px solid rgba(201,160,66,0.2);">
          <div style="font-size:11px;letter-spacing:0.24em;text-transform:uppercase;color:#c9a042;font-weight:700;">New Booking Received</div>
          <h1 style="margin:8px 0 0;font-family:Georgia,serif;font-size:28px;font-weight:300;color:#faf6ef;">{$r['patientName']}</h1>
        </td></tr>
        <tr><td style="padding:28px 40px;color:#faf6ef;font-size:14px;line-height:1.8;">
          <p><strong>Reference:</strong> {$r['bookingReference']}</p>
          <p><strong>Condition:</strong> {$r['conditionTitle']}</p>
          <p><strong>Date:</strong> {$this->formatDate($r['date'])}</p>
          <p><strong>Time:</strong> {$r['startTime']} – {$r['endTime']}</p>
          <p><strong>Phone:</strong> {$r['patientPhone']}</p>
          <p><strong>Email:</strong> {$r['patientEmail']}</p>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body></html>
HTML;
    }
}
