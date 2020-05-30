@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row pl-3">
      <div class="input-group mb-3">
        <div class="col-xl-6 col-md-6 col-lg-6 col-sm-12 pl-0">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="inputGroupFile03" accept=".csv">
            <label class="custom-file-label" for="inputGroupFile03">Importer une liste d'élèves</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-4" style="margin-bottom: 20px">
        <div class="card" style="width: 20rem;">
          <div class="card-body">
            <h5 class="card-title">Omar Aldakar</h5>
            <p class="card-text">omaraldakar@gmail.com</p>

            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Supprimer</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row pl-3">
      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModal">
      Ajouter un élève
    </button>
    </div>

</div>

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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
  @endsection