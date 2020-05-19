@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Création d\'une UE') }}</div>

                <div class="card-body">
                    <form method="POST">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nom de l\'UE') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong> Le champ nom de l'UE est invalide </strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="annee" class="col-md-4 col-form-label text-md-right">{{ __('Année') }}</label>

                            <div class="col-md-6">
                                <input id="annee" type="text" class="form-control @error('annee') is-invalid @enderror" name="annee" required  autofocus>
                                @error('annee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Le champ année est invalide </strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Ajouter l\'UE') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection