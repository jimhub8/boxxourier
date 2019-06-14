@extends('layouts.app')

@section('content')
<table>
    <thead>
        <th>Sender</th>
        <th>Phone</th>
        <th>email</th>
        <th>Address</th>
        <th>name</th>
    </thead>
    <tbody>
        <tr>
            @foreach ($query as  $value)
                <td>{{ $value->sender_name }}</td>
                <td>{{ $value->sender_email }}</td>
                <td>{{ $value->sender_phone }}</td>
                <td>{{ $value->sender_city }}</td>
                <td>{{ $value->client_city }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
@endsection