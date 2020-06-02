@extends('layouts.app')

@section('content')

    <div class="container" style="background-color: #f7f7f7;padding : 10px;border-radius : 20px">
        
        <h3> {{$grille->titre}}</h3>
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
                    @foreach ($grille->criteres()->get() as $critere)
                    <tr>
                        <td>
                            <p> {{$critere->libelle}} </p>
                        </td>
                        <td>
                            {{$critere->niveau1}}
                        </td>
                        <td>
                            {{$critere->niveau2}}
                        </td>
                        <td>
                            {{$critere->niveau3}}
                        </td>
                    </tr>                        
                    @endforeach

                </tbody>
            </table>
        </div>

        @if ($grille->precision != null)
        <div class="form-group">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12">
                <label style="font-size: larger">Précisions </label>
                <p> {{$grille->precision}} </p>
            </div>

        </div>            
        @endif

    </div>
@endsection