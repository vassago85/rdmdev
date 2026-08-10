<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Services\NtfyService;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreEnquiryRequest $request, NtfyService $ntfy)
    {
        $data = $request->safe()->except('website', 'photos');
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = substr((string) $request->userAgent(), 0, 500);
        $data['photos']     = $this->storePhotos($request);

        $enquiry = Enquiry::create($data);

        $to = config('rdm.enquiry_to');
        if (! empty($to)) {
            try {
                Mail::to($to)->send(new EnquiryReceived($enquiry));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Fire a push notification to the owner's phone. Silent if ntfy isn't
        // enabled in the admin panel; failures don't break the form response.
        try {
            $ntfy->notify(
                title: 'New enquiry — ' . $enquiry->name,
                message: $this->buildNtfyMessage($enquiry),
                opts: [
                    'tags'  => ['incoming_envelope'],
                    'click' => url('/admin/enquiries/' . $enquiry->id . '/edit'),
                ],
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->to(url()->previous() . '#enquiry')
            ->with('enquiry.success', "Thanks {$enquiry->name} — we've received your message and will be in touch shortly.");
    }

    /**
     * Persist any uploaded job photos to the public disk and return their
     * relative paths. Returns null when nothing was attached.
     *
     * @return array<int, string>|null
     */
    protected function storePhotos(StoreEnquiryRequest $request): ?array
    {
        if (! $request->hasFile('photos')) {
            return null;
        }

        $paths = [];
        foreach ($request->file('photos') as $photo) {
            if ($photo && $photo->isValid()) {
                $paths[] = $photo->store('enquiries/' . now()->format('Y/m'), 'public');
            }
        }

        return $paths ?: null;
    }

    protected function buildNtfyMessage(Enquiry $enquiry): string
    {
        $lines = [
            'Phone: ' . $enquiry->phone,
        ];

        if ($enquiry->email) {
            $lines[] = 'Email: ' . $enquiry->email;
        }
        if ($enquiry->service_type) {
            $lines[] = 'Service: ' . $enquiry->service_type;
        }
        if ($enquiry->suburb) {
            $lines[] = 'Suburb: ' . $enquiry->suburb;
        }
        if ($count = count($enquiry->photos ?? [])) {
            $lines[] = 'Photos: ' . $count . ' attached';
        }

        $lines[] = '';
        $lines[] = $enquiry->message;

        return implode("\n", $lines);
    }
}
