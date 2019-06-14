@component('mail::message')
{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
{{-- {{ $subject }} --}}

@component('mail::panel')
<div class="col-sm-4">
    <div class="form-group">
        <label>Title</label>
        <p>{{$invoice->title}}</p>
    </div><hr>
    <div class="row">
        <div class="col-sm-6">
            <label>Receipt Date</label>
            <p>{{$invoice->receipt_date}}</p>
        </div>
        <div class="col-sm-6">
            <label>Due Date</label>
            <p>{{$invoice->due_date}}</p>
        </div>
    </div>
</div><hr>
<div class="col-sm-4">
    <div class="form-group">
        <label>Receipt No.</label>
        <p>{{$invoice->receipt_no}}</p>
    </div>
    {{-- <div class="form-group">
        <label>Grand Total</label>
        <p>Ksh{{$invoice->grand_total}}</p>
    </div> --}}
</div><hr>
<div class="col-sm-4">
    <div class="form-group">
        <label>Client Id</label>
        <p>{{$invoice->client}}</p>
    </div>
    {{-- <div class="form-group">
        <label>Client Address</label>
        <pre class="pre">{{$invoice->client_address}}</pre>
    </div> --}}
</div>
@endcomponent

@component('mail::table')
| Product name       | Price         | Quantity  | Total 	 |
| ------------------ |:-------------:| ---------:|:---------:|
@foreach  ($invoice->products as $product)
| {{$product->name}}   | {{$product->price}} | {{$product->qty}} | {{$product->qty * $product->price}} |
@endforeach
|   |  |  Grand Total | {{$invoice->grand_total}} |
|   |  |  VAT | {{$invoice->vat}} |
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent

{{-- @endsection() --}}
