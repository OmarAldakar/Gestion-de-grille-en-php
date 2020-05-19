@php
use App\Http\Controllers\UserController;  
$users = UserController::getNotConfirmed();
@endphp
@extends('layouts.app')

@section('content')
<div class="container">
    
    @if (count($users) == 0)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Demandes d\'inscription') }}</div>

                <div class="card-body">
                    {{ __('Il n\'y a pas de nouvelle demande d\'inscription') }}
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
    @endif
    
</div>
@endsection