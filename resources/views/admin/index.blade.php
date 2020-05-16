@php
use App\Http\Controllers\UserController;    
@endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <h2> Demandes d'inscription </h2>

    <div class="container">
        <div class="row">
            @foreach (UserController::getNotConfirmed() as $user)
            <div class="col-4" style="margin-bottom: 20px">
                <div class="card" style="width: 20rem;">
                    <div class="card-body">
                    <h5 class="card-title">{{$user->prenom}} {{ $user->name}} </h5>
                    <p class="card-text">{{$user->email}}</p>

                    <form method="POST" action="{{url('/admin/accept-users',[$user->id])}}">
                        @csrf
                        <input type="submit" class="btn btn-success" name="btn-accept" value="Accepter">
                        <input type="submit" class="btn btn-danger"  name="btn-decline" value="Décliner"> 
                    </form>
                    </div>
                </div>
            </div>
            @endforeach
        <div>
    </div>
    
</div>
@endsection()