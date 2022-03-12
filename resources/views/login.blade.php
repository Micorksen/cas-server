@extends("cas-server::layout")
@section("content")
    <div class="card">
        @if ($serviceObject)
            <div class="service">
                <h1 class="title">Connect to {{ $serviceObject["name"] }}</h1>
                <p>{{ $serviceObject["description"] }}</p>
            </div>
        @else
            <div class="service">
                <h1 class="title">Connection</h1>
            </div>
        @endif
        @if (!$secure)
            <div class="error">
                <h2 class="title">Not secured connection</h2>
                <p>
                    Since your connection is not secure, SSO has been disabled.
                    Please use an HTTPS connection.
                </p>
            </div>
        @endif

        <div id="cookiesDisabled" class="error" style="display: none;">
            <h2 class="title">Cookies are disabled</h2>
            <p>If your browser does not accept cookies, SSO will not work.</p>
        </div>
        <form method="POST" action="{{ url('login') }}" accept-charset="UTF-8" autocomplete="off">
            {{ csrf_field() }}
            @if ($service)
                <input type="hidden" name="service" value="{{ $service }}">
            @endif

            @if ($error)
                <div class="error">
                    <h2 class="title">{{ $error }}</h2>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="error">
                    <h2 class="title">{{ Session::get("error") }}</h2>
                </div>
            @endif
            <div class="input-container">
                <input required="required" tabindex="1" accesskey="u" size="25" autocomplete="off" name="username" type="text" value="">
                <label for="username">Username</label>
                <div class="bar"></div>
            </div>

            <div class="input-container no-bottom-margin">
                <input required="required" tabindex="2" accesskey="p" size="25" autocomplete="off" name="password" type="password" value="">
                <label for="password">Password</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <p id="capsOn" style="display:none;">Attention: The "Shift Lock" key is activated!</p>
            </div>
            <div class="button-container">
                <button type="submit"><span>Connect</span></button>
            </div>
        </form>
        <div class="footer2">For your security, please log out and close your browser when you are finished using this service.</div>
    </div>
@endsection
