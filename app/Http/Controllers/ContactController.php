<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreEnquiryRequest $request)
    {
        $data = $request->safe()->except('website');
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = substr((string) $request->userAgent(), 0, 500);

        $enquiry = Enquiry::create($data);

        $to = config('rdm.enquiry_to');
        if (! empty($to)) {
            try {
                Mail::to($to)->send(new EnquiryReceived($enquiry));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()
            ->to(url()->previous() . '#enquiry')
            ->with('enquiry.success', "Thanks {$enquiry->name} — we've received your message and will be in touch shortly.");
    }
}
