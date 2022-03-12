@extends('casserver::layout')
@section('content')
    <div class="card success">
        <div class="service">
            <h2 class="title">Connexion réussie</h2>
            <p>Vous êtes connecté en tant que {{$user}}.</p>
            <p>Malheureusement, nous n'avons pas pu trouver le service auquel vous rediriger.</p>
            <p>Pour votre sécurité, merci de vous <a href="/logout">déconnecter</a> et fermer votre navigateur lorsque vous avez fini d'utiliser ce service.</p>
        </div>
    </div>
@endsection