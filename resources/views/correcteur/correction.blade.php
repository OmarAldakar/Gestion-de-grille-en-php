@extends('layouts.app')

@section('content')
@php
    use App\Http\Controllers\RepartitionController;
    $grille = $correction->grille()->first();
@endphp

    <div class="container" style="background-color: #f7f7f7;padding : 25px;border-radius : 20px">
        <div class="jumbotron" style="padding-top : 20px; padding-bottom : 20px">
            <h5> Éleve(s) concerné(es) par le correction</h5>
                <hr class="my-4">
            
                <div class="row">
                    @php
                        $eleves = RepartitionController::getEleveByCorrection($correction->id);
                    @endphp
                    @foreach ($eleves as $eleve)
                    <div class="col-sm-10 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
                        <div class="card" style="width: 19rem;">
                            <div class="card-body">
                            <h5 class="card-title">  {{$eleve->nom}} {{$eleve->prenom}}</h5>
                            <p> {{$eleve->email}} </p>
                            
                            @if (sizeof($eleves) != 1)
                            <div class="row pl-3">
                                <form method="POST" action="{{url('/correcteur/dissociate',[$ue->id,$correction->id,$exercice->id,$eleve->id])}}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger mr-2">Retirer</button>
                                </form>
                            </div>
                            @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            
                <div class="row">
                    <button class="btn btn-primary  ml-3 mt-2"  data-target="#exampleModal"  data-toggle="modal" >Ajouter </button>
                </div>
            </div>
        <form method="POST" action="{{url('/correcteur',[$ue->id,$correction->id,$exercice->id])}}">
        @csrf
        <h3> {{$exercice->titre}}</h3>
        @if ($exercice ->description != null)
            <label for="description" style="font-size: larger">Description de l'exercice </label>
            <p> {{$exercice ->description}}</p>            
        @endif


        <h3> {{$grille->titre}}</h3>

        @if ($grille->precision != null)
        <div class="form-group">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12">
                <h4>Précisions </h4>
                <p> {{$grille->precision}} </p>
            </div>
    
        </div>            
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

        <div class="table-responsive" style="padding-left : 20px;padding-right : 20px;padding-top:10px">
            <table class="table" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th style="width: 200px;font-weight: normal;font-size: larger;">Critères</th>
                        <th style="text-align: center;width: 230px;font-size: larger;">-</th>
                        <th style="text-align: center;width: 230px;font-size: larger;">=</th>
                        <th style="text-align: center;width: 230px;font-size: larger;">+</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($correction->criteres()->get() as $key => $critere_corr)
                    @php
                        $critere = $critere_corr->critere()->first();
                    @endphp
                    <tr>
                        <td style="font-size: larger">
                            <p> {{$critere->libelle}} </p>
                        </td>
                        <td style="text-align: center">
                            {{$critere->niveau1}}
                        </td>
                        <td style="text-align: center">
                            {{$critere->niveau2}}
                        </td>
                        <td style="text-align: center">
                            {{$critere->niveau3}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>  </p>
                        </td>
                        <td style="text-align: center">
                            <input type="radio" name="critere{{$key}}" value="1" style="width: 100%;height:100%" 
                            @if ($critere_corr->niveau == 1)
                                checked="checked"                                
                            @endif>
                        </td>
                        <td style="text-align: center" >
                            <input type="radio" name="critere{{$key}}" value="2" style="width: 100%;height:100%"
                            @if ($critere_corr->niveau == 2)
                                checked="checked"                                
                            @endif>
                        </td>
                        <td style="text-align: center">
                            <input type="radio" name="critere{{$key}}" value="3" style="width: 100%;height:100%" 
                            @if ($critere_corr->niveau == 3)
                                checked="checked"                                
                            @endif>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <label for="exampleFormControlTextarea1" style="font-size: larger">Commentaire</label>
                        </td>
                        <td colspan="3">
                            <div class="form-group">
                                <textarea name="commentaire{{$key}}" class="form-control" id="exampleFormControlTextarea1" rows="3">@if ($critere_corr->commentaire != null){{$critere_corr->commentaire}}@endif</textarea>
                            </div>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="form-group">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12">
                    <label style="font-size: larger">Commentaire globale </label>
                    <textarea name="globale" class="form-control" id="exampleFormControlTextarea1" rows="5" >@if ($correction->commentaire != null){{$correction->commentaire}}@endif</textarea>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12" style="text-align: right">
                        <button class="btn btn-primary" type="submit" style="height : 45px;width:100px;margin-right:15px;margin-top : 20px">Valider</button>
                    </div>
                </div>
        
            </div>    
        </div>
    </form>

    </div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @php
            $eleves = RepartitionController::getEleveNonAssociatedByCorrection($correction->id); 
        @endphp
      @if (!$eleves->isEmpty())
      <form method="POST" action="{{url('/correcteur/associate',[$ue->id,$correction->id,$exercice->id])}}">

      <div class="modal-content">
        <div class="modal-header" style="border-bottom: none;padding-bottom:0px">
          <h5 class="modal-title" id="exampleModalLabel">Séléctionner les élèves a associés </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-header">
            <div class="col-sm-12 col-md-12 col-xl-12 col-lg-12" style="padding-left: 0px">
              <input onkeyup="search('add_eleve')" class="form-control form-control-sm" id="add_eleve" type="text" placeholder="Rechercher" aria-label="Rechercher">
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
                <label class="form-control"> {{$eleve -> nom}} {{$eleve->prenom}}  </label>
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
          <h5 class="modal-title" id="exampleModalLabel"> Aucun nouvelle élève à associer </h5>
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
    // Seach bar 3 et 4
    function search(name) {
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