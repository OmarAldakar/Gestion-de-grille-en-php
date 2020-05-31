@extends('layouts.app')

@section('content')
<form method="POST">
    @csrf

    <div class="container" style="background-color: #f7f7f7;padding : 10px;border-radius : 20px">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <div class="col-md-6 col-lg-6 col-sm-12 col-xl-6">
                <label for="exampleFormControlInput1" style="font-size: larger;font-weight:normal">Titre de la grille
                </label>
                <input type="text" class="form-control" autofocus name="titre">
            </div>
        </div>
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
                    <tr>
                        <td style="height: 150px;">
                            <textarea name="critere[]" class="form-control" style="width: 100%;height: 40%;"></textarea>
                        </td>
                        <td style="height: 150px;">
                            <textarea name="niveau1[]" class="form-control"
                                style="width: 100%;height: 100%;"></textarea>
                        </td>
                        <td style="height: 150px;">
                            <textarea name="niveau2[]" class="form-control"
                                style="width: 100%;height: 100%;"></textarea>
                        </td>
                        <td style="height: 150px;">
                            <textarea name="niveau3[]" class="form-control"
                                style="width: 100%;height: 100%;"></textarea>
                        </td>
                    </tr>
                    <tr id="btn-add">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="padding-bottom: 0px;">
                            <button class="btn btn-primary text-right" type="button" id="nouveau">Nouveau
                                critère</button>
                        </td>

                </tbody>
            </table>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12">
                <label style="font-size: larger">Précisions </label>
                <textarea type="text" class="form-control" name="precision" style="height: 200px"> </textarea>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12" style="text-align: right">
                    <button class="btn btn-primary" type="submit" style="margin-right:15px ">Valider</button>
                </div>
            </div>
        </div>

    </div>
</form>

<script type="text/javascript">
    const html = ' \
                    <td style="height: 150px;"> \
                            <textarea name="critere[]" class="form-control" style="width: 100%;height: 40%;"></textarea> \
                        </td> \
                        <td style="height: 150px;"> \
                            <textarea name="niveau1[]" class="form-control" style="width: 100%;height: 100%;"></textarea> \
                        </td> \
                        <td style="height: 150px;"> \
                            <textarea name="niveau2[]" class="form-control" style="width: 100%;height: 100%;"></textarea> \
                        </td> \
                        <td style="height: 150px;"> \
                            <textarea name="niveau3[]" class="form-control" style="width: 100%;height: 100%;"></textarea> \
                        </td> \
                    '




    const new_line = document.getElementById("nouveau");
    new_line.onclick = function () {
        var btn_add = document.getElementById('btn-add');

        var tr = document.createElement("tr");
        tr.innerHTML = html;
        btn_add.parentNode.insertBefore(tr, btn_add);
    }
</script>
@endsection