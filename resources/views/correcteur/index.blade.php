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
        @php
            $pourcent_total = RepartitionController::getAvancerGrilleExercice(Auth::user(),$exercice,$grille);
        @endphp 
        <div class="progress" style="margin-bottom: 20px">
            <div class="progress-bar" role="progressbar" style="width: {{$pourcent_total}}%;" aria-valuenow="{{$pourcent_total}}" aria-valuemin="0" aria-valuemax="100">{{$pourcent_total}}%</div>
        </div>      
        <hr class="my-4">
    
        <div class="row">
            @foreach (RepartitionController::getEleves(Auth::user()->id,$exercice->id,$grille->id) as $eleve)
            @php
                $grille_corr_id = RepartitionController::getGrilleCorr(Auth::user()->id,$exercice->id,$grille->id,$eleve->id);
            @endphp
            <div class="col-sm-10 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                <div class="card" style="width: 19rem;">
                    
                    <div class="card-body">
                    <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                    <p> {{$eleve->email}}</p>
                    @php
                        $pourcent = RepartitionController::getPourcentageCorrection($grille_corr_id); 
                    @endphp
                    <div class="progress" style="margin-bottom: 20px">
                        <div class="progress-bar" role="progressbar" style="width: {{$pourcent}}%;" aria-valuenow="{{$pourcent}}" aria-valuemin="0" aria-valuemax="100">{{$pourcent}}%</div>
                    </div>
                    <div class="row pl-3">
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