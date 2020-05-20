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

                    @foreach($user->UEs()->get() as $ue)
                        <p class="card-text"> Responsable de l'UE {{$ue->nom}} {{$ue->annee}} </p>
                    @endforeach

                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if (!UserController::isAdmin($user))
                            <form method="POST" action="{{url('/admin/promote-admin',[$user->id])}}">
                                @csrf
                                <input type="submit" class="dropdown-item" value="Promouvoir administrateur"> 
                            </form>
                        @endif
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#exampleModal{{$user->id}}">
                            Désigner comme responsable d'une UE
                        </button>
                        @if (!UserController::isAdmin($user))
                            <form method="POST" action="{{url('/admin/delete-user',[$user->id])}}">
                            @csrf
                            <input type="submit" class="dropdown-item" value="Supprimer"> 
                            </form>
                        @endif
                      </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  @if (!$user->nonResponsable() -> isEmpty())
                  <form method="POST" action="{{url('/admin/add-ue',[$user->id])}}">

                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Séléctionner une UE</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $ues = $user->nonResponsable(); 
                        @endphp
                        @csrf
                        @foreach($ues as $ue)
                        <div class="input-group" style="padding-bottom: 5px">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                              <input name="ue" value="{{$ue->id}}" type="radio" aria-label="Radio button for following text input">
                              </div>
                            </div>
                            <label class="form-control"> {{$ue -> nom}} {{$ue -> annee}} </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                      <input type="submit" class="btn btn-primary" value="Valider">
                    </div>
                  </div>
                </form>
                  @else
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> Il n'y a pas d'UE disponible pour cette utilisateur </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>
                  @endif

                </div>
            </div>
            @endforeach
        <div>
    </div>
    @endif
    
</div>

@endsection