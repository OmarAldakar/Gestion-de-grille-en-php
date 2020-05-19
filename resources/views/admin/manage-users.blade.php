@php
use App\Http\Controllers\UserController;
$users = UserController::getConfirmed();
@endphp
@extends('layouts.app')

@section('content')
<div class="container">
    
    @if ($users->isEmpty())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Gestion des utilisateurs') }}</div>

                <div class="card-body">
                    {{ __('Il n\'y a pas d\'utilisateurs pour le moment') }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row">
            @foreach ($users as $user)
            <div class="col-4" style="margin-bottom: 20px">
                <div class="card" style="width: 20rem;">
                    <div class="card-body">
                    <h5 class="card-title">{{$user->prenom}} {{ $user->name}} </h5>
                    <p class="card-text">{{$user->email}}</p>

                    @if (UserController::isAdmin($user))
                        <p class="card-text"> Administrateur </p>
                    @endif

                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Promouvoir administrateur </a>
                        <a class="dropdown-item" href="#">Désigner responsable d'UE</a>
                        <a class="dropdown-item" href="#">Supprimer </a>
                      </div>
                    </div>
                </div>
            </div>
            @endforeach
        <div>
    </div>
    @endif
    
</div>
@endsection