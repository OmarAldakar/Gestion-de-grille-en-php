@extends('layouts.app')

@section('content')
@php
    use App\Http\Controllers\RepartitionController;
@endphp
<div class="container">

<h4 class="pb-3"> UE {{$ue->nom}} {{$ue->annee}}</h4>

@foreach (RepartitionController::getExercices(Auth::user()->id) as $exercice)
    @foreach (RepartitionController::getGrilles(Auth::user()->id,$exercice->id) as $grille)
    <div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
    <h5> {{$exercice->titre}} {{$grille->titre}}</h5>
        <p class="lead">Cliquez sur le bouton corriger pour débuter ou modifier une correction </p>
        <hr class="my-4">
    
        <div class="row">
            @foreach (RepartitionController::getEleves(Auth::user()->id,$exercice->id,$grille->id) as $eleve)
                
            <div class="col-sm-10 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                <div class="card" style="width: 19rem;">
                    <div class="card-body">
                    <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                    <p> {{$eleve->email}}</p>
                    <div class="row pl-3">
                        @php
                            $grille_corr_id = RepartitionController::getGrilleCorr(Auth::user()->id,$exercice->id,$grille->id,$eleve->id);
                        @endphp
                        <a href="{{url('/correcteur',[$ue->id,$grille_corr_id,$exercice->id])}}" class="btn btn-primary mr-2">Corriger</a>
                    </div>
                    </div>
                </div>
            </div>
            @endforeach            
            
        </div>
    
        <div class="row">
            <a class="btn btn-primary  ml-3 mt-2"  href="{{url('/grille',[$grille->id])}}">Visualiser la grille </a>
        </div>
    </div>         
    @endforeach
@endforeach

@endsection