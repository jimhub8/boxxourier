@extends('layouts.app')

@section('content')
<head-view :user="{{ Auth::user() }}"></head-view>
<a class="btn btn-primary" href="http://courier.dev/courier#/">Back</a>
<v-content>
 <v-container fluid fill-height>
  <v-layout justify-center align-center>
   <v-layout row wrap>
    <v-flex xs12 sm8>
       <v-layout row wrap>
           <v-flex xs4 sm3>
            <v-card>
              <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
              <v-card-title primary-title>
                  <div>
                   <p>Shipments.xls</p>                     
                   <small></small>                     
               </div>
           </v-card-title>
           <v-card-actions>
            <form action="{{ route("shipmentExpo") }}" method="post">
                {{csrf_field()}}
                <v-btn flat type="submit" color="blue">Export</v-btn>
            </form>
        </v-card-actions>
    </v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Bookings.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("bookingExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Approved.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("approvedExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Pending.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("pendingExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Cancelled.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("cancledExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Customers.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("customersExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Deriverd.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("deriverdExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Users.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("userExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

<v-flex xs4 sm3>
    <v-card>
      <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">               
      <v-card-title primary-title>
          <div>
           <p>Branches.xls</p>                     
           <small></small>                     
       </div>
   </v-card-title>
   <v-card-actions>
    <form action="{{ route("branchesExpo") }}" method="post">
        {{csrf_field()}}
        <v-btn flat type="submit" color="blue">Export</v-btn>
    </form>
</v-card-actions>
</v-card>
</v-flex>

</v-layout>
</v-flex>
<v-flex xs12 sm3 offset-sm1>
<v-card>
<v-divider></v-divider>
<h1>Clients Reports</h1><hr>
<form action="{{ route("userDateExpo") }}" method="post">
    {{ csrf_field() }}
    <select class="custom-select custom-select-md col-md-12 col-md-12" name="name" style="font-size: 13px;">
        @foreach ($customers as $customer)
            <option value="{{ $customer->name }}">{{ $customer->name }}</option>
        @endforeach
    </select>
    Between <hr>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="start_date"
        label="start date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="end_date"
        label="End Date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-btn flat type="submit" success color="black">Download</v-btn>
    {{-- <div class="input-group col-md-12">
        <label for="">Start Date:</label>
        <input type="date" name="start_date"
        label="start date" class="input-control">
    </div>
    <div class="input-group col-md-12">
        <label for="">End Date:</label>
        <input type="date" name="end_date"
        label="End Date" class="input-control">
    </div> --}}
</form>

<v-divider></v-divider>
<h1>Status Reports</h1><hr>
<form action="{{ route("displayReport") }}" method="post">
    {{ csrf_field() }}
    <select class="custom-select custom-select-md col-md-12" name="status">
  
        <option value="Awaiting Approval">Awaiting Approval</option>

        <option value="Approved">Approved</option>

        <option value="Cancelled">Cancelled</option>

        <option value="Shipment Collected">Shipment Collected</option>

        <option value="Waiting for Scan">Waiting for scan</option>

        <option value="Ready For Depart">Ready For Depart</option>

        <option value="Despatched">Despatched</option>

        <option value="Arrived">Arrived</option>

        <option value="Cleared">Cleared</option>

        <option value="Transit">Transit</option>

        <option value="Out For Destination">Out For Destination</option>

        <option value="Out For Delivery">Out For Delivery</option>

        <option value="Delivered">Delivered</option>

        <option value="Returned">Returned</option>

        <option value="Hold">Hold</option>

      </select>
    Between <hr>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="start_date"
        label="start date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="end_date"
        label="End Date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-btn flat type="submit" success color="black">Download</v-btn>
</form>

<v-divider></v-divider>
<h1>Rider Reports</h1><hr>
<form action="{{ route("DriverReport") }}" method="post">
    {{ csrf_field() }}
    <select class="custom-select custom-select-md col-md-12" name="status">
        @foreach ($drivers as $driver)
            <option value="{{ $driver->name }}">{{ $driver->name }}</option>
        @endforeach
      </select>
    Between <hr>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="start_date"
        label="start date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-flex xs10 sm9  offset-sm1>
        <v-text-field
        name="end_date"
        label="End Date"
        type="date"
        color="blue darken-2"
        required
        ></v-text-field>
      </v-flex>
    <v-btn flat type="submit" success color="black">Download</v-btn>
</form>
</v-card>
</v-flex>
</v-layout>
</v-layout>
</v-container>
</v-content>
@endsection
