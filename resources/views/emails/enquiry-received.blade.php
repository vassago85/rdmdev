@component('mail::message')
# New enquiry received

You have a new enquiry from the RDM Developments website.

**Name:** {{ $enquiry->name }}
**Phone:** {{ $enquiry->phone }}
@if ($enquiry->email)**Email:** {{ $enquiry->email }}
@endif
@if ($enquiry->service_type)**Service:** {{ $enquiry->service_type }}
@endif
@if ($enquiry->suburb)**Suburb:** {{ $enquiry->suburb }}
@endif
@if ($enquiry->source)**Source:** {{ $enquiry->source }}
@endif

**Message:**

> {!! nl2br(e($enquiry->message)) !!}

@component('mail::button', ['url' => url('/admin/enquiries')])
Open in admin
@endcomponent

Sent {{ $enquiry->created_at->format('d M Y H:i') }} · {{ config('app.name') }}
@endcomponent
