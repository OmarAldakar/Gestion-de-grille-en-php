@extends('layouts.app')

@section('content')
@php
    use App\Http\Controllers\UEController;
@endphp
<div class="container">

<h4 class="pb-3"> UE {{$ue->nom}} {{$ue->annee}}</h4>

@error('description')
    <div class="alert alert-danger"> La description est trop grande </div>
@enderror
@error('titre')
    <div class="alert alert-danger"> Le champ titre est requis </div>
@enderror
<div class="row mb-4 ml-1">
    <a  class="btn btn-primary mr-3 mb-2" style="width: 200px" href="{{url('/resp/generate-bilan',[$ue->id])}}"> Générer le bilan de l'UE </a>
    <button type="submit" class="btn btn-primary mr-3 mb-2"  data-toggle="modal" data-target="#exampleModal" style="width: 200px"> Ajouter un exerice </button>
    <a class="btn btn-primary mr-3 mb-2"  style="width: 200px" href="{{url('/resp/manage-eleves',[$ue->id])}}"> Gérer la liste des élèves </a>
</div>

<div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
    <h5> Grille(s) associée(s) à cette UE </h5>
    <p class="lead">Ici se trouve les grilles critériée qui serviront à évaluer les différents exercices de l'UE, cliquez sur le bouton ajouter une grille pour en créer une nouvelle </p>
    <hr class="my-4">

    <div class="row">
        @foreach ($ue->grilles()->get() as $grille)
            
        <div class="col-sm-10 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
            <div class="card" style="width: 19rem;">
                <div class="card-body">
                <h5 class="card-title" style="margin-bottom: 30px"> {{$grille->titre}}</h5>
                <div class="row pl-3">
                <a href="{{url('/grille',[$grille->id])}}" class="btn btn-primary mr-2">Visualiser</a>
                <form method="POST" action="{{url('/resp/disociate',[$ue->id,$grille->id])}}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                </div>
                </div>
            </div>
        </div>
        @endforeach            
        
    </div>

    <div class="row">
        <a class="btn btn-primary  ml-3 mt-2"  href="{{url('/resp/new-grille',[$ue->id])}}">Ajouter une grille</a>
    </div>
</div> 

@foreach ($ue->exercices()->get() as $exercice)
<div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
    <h5> {{$exercice->titre}} </h5>
    <p class="lead"> Dans cette rubrique vous pouvez visualiser l'avancée de la correction des différentes grilles associé à l'exercice  {{$exercice->titre}} </p>
    <hr class="my-4">

    <div class="row">
        @foreach ($exercice->grilles()->get() as $grille)
        <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
            <div class="card" style="width: 20rem;">
                <div class="card-body">
                <h5 class="card-title"> {{$grille->titre}}</h5>
                    <a type="submit" href="{{url('/resp/detail-grille',[$ue->id,$exercice->id,$grille->id])}}" class="btn btn-success" name="btn-accept"> En savoir plus </a>
                </div>
            </div>
        </div>            
        @endforeach
        
    </div>
    <div class="row" style="text-align : right;">
        <button type="button" class="btn btn-primary" style="font-size : medium;margin-top : 10px;margin-left : 15px;" data-toggle="modal" data-target="#exampleModal{{$exercice->id}}">
            Associer une grille
        </button>
        <form method="POST" action="{{url('/resp/delete-exercice',[$ue->id,$exercice->id])}}">
        @csrf
        <button class="btn btn-danger" href="#" type="submit" style="font-size : medium;margin-top : 10px;margin-left : 15px">Supprimer l'exercice</button>
        </form>
    </div>
</div>
<div class="modal fade" id="exampleModal{{$exercice->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @php
            $grilles = UEController::getNonAssociatedGrille($ue,$exercice); 
        @endphp
      @if (!$grilles->isEmpty())
      <form method="POST" action="{{url('/resp/associate',[$ue->id,$exercice->id])}}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Séléctionner une grille</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            @csrf
            @foreach($grilles as $grille)
            <div class="input-group" style="padding-bottom: 5px">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                  <input name="grille" value="{{$grille->id}}" type="radio" aria-label="Radio button for following text input">
                  </div>
                </div>
                <label class="form-control"> {{$grille -> titre}}  </label>
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
          <h5 class="modal-title" id="exampleModalLabel"> Aucune nouvelle grille a été trouvée </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
      @endif

    </div>
</div> 
@endforeach

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{url('/resp/new-exercice',[$ue->id])}}">
    @csrf
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="font-size : larger">
                Ajouter un exercice
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label> Titre  </label>
                    <input class="form-control" name="titre"> 
                </div>
                <div class="form-group">
                    <label> Description  </label>
                    <textarea class="form-control" style="height: 180px" name="description" value=""> </textarea> 
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
              <input type="submit" class="btn btn-primary" value="Valider">
            </div>
        </div>
    </div>
    <form>

</div>
@endsection