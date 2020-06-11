@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row mb-4 ml-1">
        <button type="submit" class="btn btn-primary mr-3 mb-2"  data-toggle="modal" data-target="#exampleModal" style="width: 200px"> Désigner un correcteur </button>
    </div>
    @php
        use App\Http\Controllers\RepartitionController;
        $nonassociated_student = RepartitionController::getNonAssociatedEleve($exercice->id,$grille->id,$ue->id);
    @endphp

    @if (!$nonassociated_student->isEmpty())
    <div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
        <h5> Eleves non associé(e)s </h5>
        <p class="lead"> Liste des élèves n'ayant pas été affecté à un correcteur </p>
        <hr class="my-4">
    
        <div class="row">
            @foreach ($nonassociated_student as $eleve)
            <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                <div class="card" style="width: 20rem;">
                    <div class="card-body">
                    <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                    <p> {{$eleve->email}}<p>
                    </div>
                </div>
            </div>            
            @endforeach
            
        </div>

    </div>
    @endif

    @foreach (RepartitionController::getAssociatedCorrecteur($exercice->id,$grille->id) as $user)
    <div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
      <h5> Correcteur : {{$user->name}} {{$user->prenom}} </h5>
      <p class="lead"> Liste des élèves associé à ce correcteur </p>
      <hr class="my-4">
    
      <div class="row">
          @foreach (RepartitionController::getCorrecteurEleve($exercice->id,$grille->id,$user->id) as $eleve)
          <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
              <div class="card" style="width: 20rem;">
                  <div class="card-body">
                  <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                  <p> {{$eleve->email}}<p>
                  </div>
              </div>
          </div>          
          @endforeach
      </div>

      <div class="row" style="text-align : right;">
        <button type="button" class="btn btn-primary" style="font-size : medium;margin-top : 10px;margin-left : 15px;" data-toggle="modal" data-target="#exampleModal{{$user->id}}">

            Ajouter un élève
        </button>
        <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              @php
                $eleves = RepartitionController::getNonAssociatedEleve($exercice->id,$grille->id,$ue->id)
              @endphp
            @if (!$eleves->isEmpty())
            <form method="POST" action="{{url('/resp/associate-student',[$ue->id,$exercice->id,$grille->id,$user->id])}}">
              @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Séléctionner un ou plusieurs élèves</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  @csrf
                  @foreach($eleves as $eleve)
                  <div class="input-group" style="padding-bottom: 5px">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                        <input name="eleves[]" value="{{$eleve->id}}" type="checkbox" aria-label="Radio button for following text input">
                        </div>
                      </div>
                      <label class="form-control" style="text-align: left"> {{$eleve -> nom}} {{$eleve -> prenom}} </label>
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
    </div>
    
    </div>
    @endforeach

  </div>




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @php
            $users = RepartitionController::getNonAssociatedCorrecteur($exercice->id,$grille->id); 
        @endphp
      @if (!$users->isEmpty())
      <form method="POST" action="{{url('/resp/associate-correcteur',[$ue->id,$exercice->id,$grille->id])}}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Séléctionner un correcteur</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            @csrf
            @foreach($users as $user)
            <div class="input-group" style="padding-bottom: 5px">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                  <input name="correcteurs[]" value="{{$user->id}}" type="checkbox" aria-label="Radio button for following text input">
                  </div>
                </div>
                <label class="form-control"> {{$user -> name}} {{$user-> prenom}}  </label>
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
          <h5 class="modal-title" id="exampleModalLabel"> Aucun correcteur n'a été trouvé </h5>
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
@endsection