@extends('layouts.app')

@section('content')
<v-card>
    <li class="list-group-item active">Shipment Details</li>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Waybill Number</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Percels Sent</th>
                <th scope="col">Derivery Date</th>
                <th scope="col">Derivery Time</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope>{{ $shipments->bar_code }}</th>
                <td>{{ $shipments->from_city }}</td>
                <td>{{ $shipments->to_city }}</td>
                <td>{{ $shipments->amount_ordered }}</td>
                <td>{{ $shipments->derivery_date }}</td>
                <td>{{ $shipments->derivery_time }}</td>
                <td>{{ $shipments->status }}</td>
            </tr>
        </tbody>
      </table>
      <li class="list-group-item active">Derivery Details</li>
    <table class="table">
            <thead>
                <tr>
                    <th scope="col">Received By</th>
                    <th scope="col">Id Number</th>
                    <th scope="col">Email Number</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Date Received</th>
                    <th scope="col">Time Received</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope>{{ $shipments->client_name }}</th>
                    <td>'12343'</td>
                    <td>{{ $shipments->client_email }}</td>
                    <td>{{ $shipments->client_phone }}</td>
                    <td>{{ $shipments->derivery_date }}</td>
                    <td>{{ $shipments->derivery_time }}</td>
                </tr>
            </tbody>
          </table>
          <li class="list-group-item active">Sender Details</li>
          <table class="table">
                  <thead>
                      <tr>
                          <th scope="col">Sent By</th>
                          <th scope="col">Id Number</th>
                          <th scope="col">Email Number</th>
                          <th scope="col">Phone Number</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <th>{{ $shipments->sender_name }}</th>
                          <td>12343</td>
                          <td>{{ $shipments->sender_email }}</td>
                          <td>{{ $shipments->sender_phone }}</td>
                      </tr>
                  </tbody>
                </table>
          <li class="list-group-item active">Products Details</li>
          <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Events</th>
                        <th scope="col">Event date and time</th>
                        <th scope="col">Location</th>
                        <th scope="col">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipments->statuses as $key => $statuses)
                    <tr>
                        <th scope="row">{{ ($key)+1 }}</th>
                        <td>{{ $statuses->status }}</td>
                        <td>{{ $statuses->created_at }}</td>
                        <td>{{ $statuses->location }}</td>
                        <td>{{ $statuses->remark }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
</v-card>
@endsection