@extends("cas-server::layout")
@section("content")
<div class="card error">
    <div class="error">
        <h2 class="title">{{ $title }}</h2>
        <p>{{ $description }}</p>
    </div>
</div>
@endsection
