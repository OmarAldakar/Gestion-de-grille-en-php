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
        <div class="row">
          <div class="col-sm-12 col-md-6 col-xl-6 col-lg-6">
          <input class="form-control form-control-sm" type="text" placeholder="Rechercher" aria-label="Rechercher" id="search_non_associated">
          </div>
        </div>
        <hr class="my-4">
    
        <div class="row" style="max-height : 265px;overflow-y:auto;">
            @foreach ($nonassociated_student as $eleve)
            <div class="non_associated">
            <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                <div class="card" style="width: 20rem;">
                    <div class="card-body">
                    <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                    <p> {{$eleve->email}}<p>
                    </div>
                </div>
            </div> 
            </div>           
            @endforeach
            
        </div>

    </div>
    @endif

    @php
        $i=0;
    @endphp
    @foreach (RepartitionController::getAssociatedCorrecteur($exercice->id,$grille->id) as $user)
      <div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
        <h5> Correcteur : {{$user->name}} {{$user->prenom}} </h5>
        <p class="lead"> Liste des élèves associé à ce correcteur </p>
        <div class="col-sm-12 col-md-6 col-xl-6 col-lg-6" style="padding-left: 0px">
          <input onkeyup="search({{$i}})" class="form-control form-control-sm" type="text" placeholder="Rechercher" aria-label="Rechercher" id="search_eleve_by_correcteur{{$i}}">
        </div>
        <hr class="my-4">
      
        <div class="row" style="max-height : 265px;overflow-y:auto;">
            @foreach (RepartitionController::getCorrecteurEleve($exercice->id,$grille->id,$user->id) as $eleve)
            <div class="eleves{{$i}}">
              <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                  <div class="card" style="width: 20rem;">
                      <div class="card-body">
                      <h5 class="card-title"> {{$eleve->nom}} {{$eleve->prenom}}</h5>
                      <p> {{$eleve->email}}<p>
                      </div>
                  </div>
              </div>
            </div>
            @endforeach
        </div>
        @php
            $i++;
        @endphp

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
                <div class="modal-header" style="border-bottom: none;padding-bottom:0px">
                    <h5 class="modal-title" id="exampleModalLabel">Séléctionner un ou plusieurs élèves</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                  <div class="col-sm-12 col-md-12 col-xl-12 col-lg-12" style="padding-left: 0px">
                    <input class="form-control form-control-sm" onkeyup="search2('add_eleve')" type="text" placeholder="Rechercher" aria-label="Rechercher" id="add_eleve">
                  </div>
                </div>
                <div class="modal-body" style="max-height : 400px;overflow-y:auto;">
                    @csrf
                    @foreach($eleves as $eleve)
                    <div class="add_eleve">
                      <div class="input-group" style="padding-bottom: 5px">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input name="eleves[]" value="{{$eleve->id}}" type="checkbox" aria-label="Radio button for following text input">
                            </div>
                          </div>
                          <label class="form-control" style="text-align: left"> {{$eleve -> nom}} {{$eleve -> prenom}} </label>
                      </div>
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
                  <h5 class="modal-title" id="exampleModalLabel"> Tous les élèves ont été associé </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style="max-height : 400px;overflow-y:auto;">
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
        <div class="modal-header" style="border-bottom: none;padding-bottom:0px">
          <h5 class="modal-title" id="exampleModalLabel">Séléctionner un correcteur</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-header">
          <div class="col-sm-12 col-md-12 col-xl-12 col-lg-12" style="padding-left: 0px">
            <input class="form-control form-control-sm" onkeyup="search2('add_corr')" type="text" placeholder="Rechercher" aria-label="Rechercher" id="add_corr">
          </div>
        </div>
        <div class="modal-body" style="max-height : 400px;overflow-y:auto;">
            @csrf
            @foreach($users as $user)
            <div class="add_corr">
            <div class="input-group" style="padding-bottom: 5px">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                  <input name="correcteurs[]" value="{{$user->id}}" type="checkbox" aria-label="Radio button for following text input">
                  </div>
                </div>
                <label class="form-control"> {{$user -> name}} {{$user-> prenom}}  </label>
            </div>
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

<script type="text/javascript">
  // Search bar 1
  const search_non_associated = document.getElementById("search_non_associated");
  search_non_associated.onkeyup = function (event) {

    const filter = search_non_associated.value.toUpperCase();
    const elems = document.getElementsByClassName("non_associated");

    for (let i=0; i < elems.length; i++) {
      let element = elems[i];
      let text = element.getElementsByTagName("h5")[0].innerText;
      if (text.toUpperCase().indexOf(filter) > -1) {
        element.style.display = "";
      } else {
        element.style.display = "none";
      }
    }
  };

  // Search bar 2
  function search(i) {
    const search = document.getElementById("search_eleve_by_correcteur"+i);
    const filter = search.value.toUpperCase();
    const elems = document.getElementsByClassName("eleves"+i);
    console.log(elems);
    for (let i=0; i < elems.length; i++) {
      let element = elems[i];
      let text = element.getElementsByTagName("h5")[0].innerText;
      if (text.toUpperCase().indexOf(filter) > -1) {
        element.style.display = "";
      } else {
        element.style.display = "none";
      }
    } 
  }

  // Seach bar 3 et 4
  function search2(name) {
    const search = document.getElementById(name);
    const filter = search.value.toUpperCase();
    const elems = document.getElementsByClassName(name);
    console.log(elems);
    for (let i=0; i < elems.length; i++) {
      let element = elems[i];
      let text = element.getElementsByTagName("label")[0].innerText;
      if (text.toUpperCase().indexOf(filter) > -1) {
        element.style.display = "";
      } else {
        element.style.display = "none";
      }
    } 
  }
</script>
@endsection