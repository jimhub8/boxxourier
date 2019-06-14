@component('mail::message')
{{-- # Introduction --}}


@component('mail::panel')
The following shipments are schedueled today and tomorrow
@endcomponent

@component('mail::button', ['url' => url('/courier#/scheduled')])
See Shipments
@endcomponent

@component('mail::table')
| Waybill        | Client Name        | Phone         | Email  | delivery Date 	 |
| ------------------ |:-------------:| ---------:|:---------:|
@foreach  ($shipment as $value)
| {{ $value->bar_code }}   | {{ $value->client_name }}   | {{ $value->client_phone }} | {{ $value->client_email }} | {{ $value->derivery_date }} |
@endforeach
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
