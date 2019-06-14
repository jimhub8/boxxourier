@component('mail::message')
# Introduction

{{--  The body of your message.  --}}

{{--  @component('mail::button', ['url' => url('/courier#/scheduled')])
Button Text
@endcomponent  --}}

@component('mail::panel')
Attached is your monthly shipment report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
