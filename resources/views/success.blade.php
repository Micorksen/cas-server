@extends("cas-server::layout")
@section("content")
    <div class="card success">
        <div class="service">
            <h2 class="title">Successful login</h2>
            <p>You are logged in as {{ $user }}.</p>
            <p>Unfortunately, we were unable to find the service to redirect you to.</p>
            <p>For your safety, please <a href="/logout">log out</a> and close your browser when you are finished using this service.</p>
        </div>
    </div>
@endsection
