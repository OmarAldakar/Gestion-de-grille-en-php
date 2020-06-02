@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="{{url('/resp/importEleve',[$ue->id])}}" enctype="multipart/form-data">
      @csrf
    <div class="row pl-3">
      <div class="input-group mb-3">
        <div class="col-xl-6 col-md-6 col-lg-6 col-sm-12 pl-0">
          <div class="custom-file">
            <input name="file" type="file" class="custom-file-input" id="inputGroupFile03" accept=".csv">
            <label class="custom-file-label" id="custom-label" for="inputGroupFile03">Importer une liste d'élèves</label>
          </div>
        </div>
        <div class="col-xl-6 col-md-6 col-lg-6 col-sm-12">
          <button type="submit" class="btn btn-primary"> Importer </button>
        </div>
      </div>
    </div>
    </form>

    <div class="row">
      @foreach ($ue->eleves()->get() as $eleve)
      <div class="col-sm-12 col-md-6 col-xl-4 col-lg-6" style="margin-bottom: 20px">
        <div class="card" style="width: 20rem;">
          <div class="card-body">
            <h5 class="card-title">{{$eleve->nom}} {{$eleve->prenom}}</h5>
          <p class="card-text">{{$eleve->email}}</p>

            <div class="dropdown">
              <form method="POST" action="{{url('/resp/delete',[$ue->id,$eleve->id])}}">
                @csrf
                <button type="submit" class="btn btn-danger">Supprimer</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="row pl-3">
      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModal">
      Ajouter un élève
    </button>
    </div>

</div>
<form method="POST" action="{{url('/resp/create-eleve',[$ue->id])}}">
  @csrf
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un élève</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label>Nom </label>
            <input name="nom" type="text" class="form-control">
            <label>Prenom </label>
            <input name="prenom" type="text" class="form-control">
            <label>Adresse email </label>
            <input name="email" type="email" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary">Ajouter</button>
      </div>
    </div>
  </div>
</div>
</form>

<script>
  const fileInput = document.getElementById('inputGroupFile03');
  fileInput.onchange = function (e) {
    const fileName = e.target.files[0].name;
    const label = document.getElementById('custom-label');
    label.innerText = fileName;
  }
</script>

@endsection