@extends('casserver::layout')
@section('content')
    <div class="card">
        @if ($serviceObject)
            <div class="service">
                <h1 class="title">Connexion à {{ $serviceObject['name'] }}</h1>
                <p>{{ $serviceObject['description'] }}</p>
            </div>
        @else
            <div class="service">
                <h1 class="title">Connexion</h1>
            </div>
        @endif
        @if (!$secure)
            <div class="error">
                <h2 class="title">Connexion non sécurisée</h2>
                <p>
                    Vu que votre connexion n'est pas sécurisée, le SSO a été désactivé.
                    Merci d'utiliser une connexion HTTPS.
                </p>
            </div>
        @endif

        <div id="cookiesDisabled" class="error" style="display:none;">
            <h2 class="title">Les cookies sont désactivés</h2>
            <p>Si votre navigateur n'accepte pas les cookies, le SSO ne sera pas fonctionnel.</p>
        </div>
        <form method="POST" action="{{ url('login') }}" accept-charset="UTF-8" autocomplete="off">
            {{ csrf_field() }}
            @if ($service)
                <input type="hidden" name="service" value="{{$service}}">
            @endif

            @if ($error)
                <div class="error">
                    <h2 class="title">{{ $error }}</h2>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="error">
                    <h2 class="title">{{ Session::get('error') }}</h2>
                </div>
            @endif
            <div class="input-container">
                <input required="required" tabindex="1" accesskey="u" size="25" autocomplete="off" name="username" type="text" value="">
                <label for="username">Nom d'utilisateur</label>
                <div class="bar"></div>
            </div>

            <div class="input-container no-bottom-margin">
                <input required="required" tabindex="2" accesskey="p" size="25" autocomplete="off" name="password" type="password" value="">
                <label for="password">Mot de passe</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <p id="capsOn" style="display:none;">Attention : La touche "Verouillage Majuscule" est activée !</p>
            </div>
            <div class="button-container">
                <button type="submit"><span>Connexion</span></button>
            </div>
        </form>
        <div class="footer2">Pour votre sécurité, merci de vous déconnecter et fermer votre navigateur lorsque vous avez fini d'utiliser ce service.</div>
    </div>
@endsection