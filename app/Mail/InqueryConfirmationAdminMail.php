<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Storage;

class InqueryConfirmationAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->content['to_email'],   // e.g. "jane@example.com"
                    $this->content['to_name']     // e.g. "Jane Doe"
                ),
            ],
            subject: 'New Inquiry!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $stepsForEmail = [];
        foreach ($this->content['steps'] as $i => $s) {
            $stepsForEmail[] = [
                'i'       => $i + 1,
                'step'    => $s['label'] ?? ucfirst(str_replace('-', ' ', $s['step_key'] ?? 'Step')),
                'step'    => __($s['step_key']),
                'details' => $this->stepText($s, $this->content['inquiry']),
            ];
        }

        return new Content(
            markdown: 'emails.inquiry_confirmation_admin',
            with: [
                'link'          => $this->content['link'],
                'name'          => $this->content['to_name'],
                'inquiry'       => $this->content['inquiry'],
                'address'       => $this->formatAddress($this->content['inquiry'], $this->content['steps']),
                'steps'         => $stepsForEmail,
                'photos'        => $this->content['photos']
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // return [];
        $out    = [];
        $photos = array_slice($this->content['photos'] ?? [], 0, 3); // limit to 3 if you want

        foreach ($photos as $p) {
            // 1) normalize path and name
            $rel  = ltrim($p['path'] ?? '', '/'); // "inquiries/137/xxx.jpg"
            $name = $p['original_name'] ?? basename($rel);

            if ($rel === '') continue;

            // 2) prefer the public disk (storage/app/public/...)
            if (Storage::disk('public')->exists($rel)) {
                $out[] = Attachment::fromStorageDisk('public', $rel)
                    ->as($name)
                    ->withMime($this->mimeFromName($name));
                continue;
            }

            // 3) fallback: public/storage symlink (public/storage/...)
            $publicPath = public_path('storage/'.$rel);
            if (is_file($publicPath)) {
                $out[] = Attachment::fromPath($publicPath)
                    ->as($name)
                    ->withMime($this->mimeFromName($name));
                continue;
            }

            // 4) last fallback: directly under public/ (public/inquiries/...)
            $publicDirect = public_path($rel);
            if (is_file($publicDirect)) {
                $out[] = Attachment::fromPath($publicDirect)
                    ->as($name)
                    ->withMime($this->mimeFromName($name));
            }
        }

        return $out;
    }

    private function mimeFromName(string $name): string
    {
        return match (strtolower(pathinfo($name, PATHINFO_EXTENSION))) {
            'jpg','jpeg' => 'image/jpeg',
            'png'        => 'image/png',
            'gif'        => 'image/gif',
            'webp'       => 'image/webp',
            'svg'        => 'image/svg+xml',
            default      => 'application/octet-stream',
        };
    }

    protected function formatAddress(array $inquiry, $steps): string
    {
        // 1) summary_location_address
        $summary = trim((string)($inquiry['summary_location_address'] ?? ''));
        if ($summary !== '') {
            return $summary;
        }

        // normalize $steps to plain array of arrays
        if ($steps instanceof \Illuminate\Support\Collection) {
            $steps = $steps->toArray();
        } elseif (is_string($steps)) {
            $decoded = json_decode($steps, true);
            if (is_array($decoded)) {
                $steps = $decoded;
            }
        }
        if (!is_array($steps)) $steps = [];

        // helper: get step->data by key
        $stepData = function (string $key) use ($steps): array {
            foreach ($steps as $s) {
                if (($s['step_key'] ?? null) === $key) {
                    return (array)($s['data'] ?? []);
                }
            }
            return [];
        };

        // helper: "street, POSTCODE CITY"
        $line = function (?string $left, ?string $postcode, ?string $city): string {
            $right = trim(implode(' ', array_filter([trim((string)$postcode), trim((string)$city)])));
            return trim(implode(', ', array_filter([trim((string)$left), $right])));
        };

        // 2) from contact step
        $c = $stepData('contact');
        $line1 = $line($c['postcode'] ?? null, $c['street'] ?? null, $c['city'] ?? null);
        if ($line1 !== '') {
            return $line1;
        }

        // 3) from provide-place step
        $p = $stepData('provide-place');
        $line2 = $line($p['postcode'] ?? null, $p['street'] ?? null, $p['city'] ?? null);
        if ($line2 !== '') {
            return $line2;
        }

        // 4) fallback
        return '—';
    }


    private function stepText(array $step, array $inquiry): string
    {
        // dd($inquiry);
        $k = (string)($step['step_key'] ?? '');
        $d = $step['data'] ?? null;               // can be array|string|null
        $a = is_array($d) ? $d : [];              // safe array view

        // helpers
        $cityLine = fn($left, $pc, $city) => self::joinAddress($left, $pc, $city);

        // determine-location
        if ($k === 'determine-location') {
            // Vue uses inquiry.summary_location; your data also has parent/child
            $text = (string)($inquiry['summary_location'] ?? '');
            if ($text === '' && ($a['parent'] ?? '') || ($a['child'] ?? '')) {
                $text = trim(implode(' > ', array_filter([$a['parent'] ?? '', $a['child'] ?? ''])));
            }
            return $text !== '' ? e($text) : '—';
        }

        // determine-location-other
        if ($k === 'determine-location-other') {
            // In your dump it's an array sometimes; fallback to first non-empty value
            $val = '';
            if (is_string($d) || is_numeric($d)) {
                $val = (string)$d;
            } elseif (is_array($d)) {
                foreach ($a as $v) {
                    if ($v !== '' && $v !== null) { $val = (string)$v; break; }
                }
            }
            return $val !== '' ? e($val) : '—';
        }

        // determine-damage
        if ($k === 'determine-damage') {
            $base  = (string)($inquiry['summary_damage'] ?? '');
            $other = (string)($a['otherText'] ?? '');
            // Fallback: join pathIds if base is missing
            if ($base === '' && !empty($a['pathIds']) && is_array($a['pathIds'])) {
                $base = implode(' > ', array_map('strval', $a['pathIds']));
            }
            $text = $other !== '' ? ($base ? ($base.' > '.$other) : $other) : ($base ?: '—');
            return e($text);
        }

        // provide-place
        if ($k === 'provide-place') {
            $rows = [];
            $addr = $cityLine($a['postcode'] ?? null, $a['street'] ?? null, $a['city'] ?? null);
            if ($addr !== '')               $rows[] = '<strong>Adresse:</strong> '.e($addr);
            if (!empty($a['floor']))        $rows[] = '<strong>Stockwerk:</strong> '.e($a['floor']);
            if (!empty($a['elevator']))     $rows[] = '<strong>Lift:</strong> '.e($a['elevator']);
            if (!empty($a['rooms']))        $rows[] = '<strong>Zimmer:</strong> '.e($a['rooms']);
            if (!empty($a['bell']))         $rows[] = '<strong>Klingelschild:</strong> '.e($a['bell']);
            return $rows ? implode('<br>', $rows) : '—';
        }

        // contact
        if ($k === 'contact') {
            $rows = [];
            $name = trim(implode(' ', array_filter([$a['firstName'] ?? null, $a['lastName'] ?? null])));
            $company = trim((string)($a['company'] ?? ''));
            if ($name || $company) {
                $rows[] = '<strong>Name:</strong> '.e(trim($name.($company ? " ($company)" : '')));
            }
            $addr = $cityLine($a['street'] ?? null, $a['postcode'] ?? null, $a['city'] ?? null);
            if ($addr !== '')               $rows[] = '<strong>Adresse:</strong> '.e($addr);
            if (!empty($a['phone']))        $rows[] = '<strong>Telefon:</strong> '.e($a['phone']);
            if (!empty($a['email']))        $rows[] = '<strong>E-Mail:</strong> '.e($a['email']);
            return $rows ? implode('<br>', $rows) : '—';
        }

        // add-comment / pictures-skip
        if ($k === 'add-comment' || $k === 'pictures-skip') {
            $note = (string)($a['note'] ?? '');
            return $note !== '' ? e($note) : '—';
        }

        return '—';
    }



    private static function joinAddress(?string $left, ?string $postcode, ?string $city): string
    {
        $pcCity = trim(implode(' ', array_filter([trim((string)$postcode), trim((string)$city)])));
        return trim(implode(', ', array_filter([trim((string)$left), $pcCity])));
    }


}
