
@extends('layouts.app')

@section('content')
<div class="container">
    <passport-clients></passport-clients>
    <passport-authorizedclients></passport-authorizedclients>
    <passport-personalaccesstokens></passport-personalaccesstokens>
    {{-- passportAuthorizedclients, passportClients, passportPersonalAccessTokens --}}
</div>
@endsection