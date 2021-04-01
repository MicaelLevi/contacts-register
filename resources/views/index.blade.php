<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>A Web Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    <style>
        .table-responsive {
            max-height: 300px;
        }
    </style>
</head>

<body>

    <div class="container mt-2">
        <!-- Card referente a parte de Cadastro de Pessoa -->
        <div class="card">
            <div class="card-header font-weight-bold">
                Cadastro de Pessoas
            </div>
            <div class="card-body">
                <form name="register" id="register" method="post" action="{{url('store')}}">
                    @csrf
                    <!-- Coluna Input dados pessoais -->
                    <div class="form-row">
                        <div class="col-md-6 d-flex flex-column">
                            <div class="form-group row">
                                <label for="InputName" class="col-sm-2 col-form-label">Nome:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" name="name" class="form-control" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="InputCpf" class="col-sm-2 col-form-label">CPF: </label>
                                <div class="col-sm-7">
                                    <input type="text" id="cpf" name="cpf" class="form-control" required="">
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary mt-auto col-sm-2 mx-auto" id="submit">Gravar</button>
                        </div>
                        <!-- Coluna input dados telefonicos -->
                        <div class="col-md-4 mx-auto">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Telefone</th>
                                        <th scope="col">Descrição Telefone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="contact1" name="contacts[0][contact]" class="form-control input-sm"></td>
                                        <td><input type="text" id="description1" name="contacts[0][description]" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="contact2" name="contacts[1][contact]" class="form-control"></td>
                                        <td><input type="text" id="description2" name="contacts[1][description]" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="contact3" name="contacts[2][contact]" class="form-control"></td>
                                        <td><input type="text" id="description3" name="contacts[2][description]" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="contact4" name="contacts[3][contact]" class="form-control"></td>
                                        <td><input type="text" id="description4" name="contacts[3][description]" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="contact5" name="contacts[4][contact]" class="form-control"></td>
                                        <td><input type="text" id="description5" name="contacts[4][description]" class="form-control"></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- @TODO: Card para mostrar dados -->
        <div class="card">
            <div class="card-header font-weight-bold">
                Dados Gravados
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-sm table-bordered table-striped ">
                        <thead>
                            <tr>
                                <td>Nome</td>
                                <td>CPF</td>
                                <td>Telefone - Descrição</td>
                                <!--
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Telefone - Descrição</th> -->
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            //Ajax para submicação do formulario
            if ($("#register").length > 0) {
                $("#register").validate({
                    submitHandler: function(form) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#submit').html('Aguade...');
                        $("#submit").attr("disabled", true);

                        $.ajax({
                            url: "{{url('/store')}}",
                            type: "POST",
                            data: $('#register').serialize(),
                            success: function(response) {
                                $('#submit').html('Gravar');
                                $("#submit").attr("disabled", false);
                                alert('Pessoa registrada com sucesso!');
                                document.getElementById("register").reset();
                                loadData();

                            }
                        })


                    }
                })
            }

            //Script para inserir dados na tabela
            const tableBody = document.querySelector("#data-table > tbody");

            function loadData() {
                const request = new XMLHttpRequest();

                request.open("get", '/contacts-register/public/retrieve');
                request.onload = () => {
                    try {
                        const json = JSON.parse(request.responseText);
                        populateTable(json);
                    } catch (e) {
                        alert("Não foi possivel carregar os dados!");
                    }

                };

                request.send();

            }

            function populateTable(json) {
                //Limpar tabela
                while (tableBody.firstChild) {
                    tableBody.removeChild(tableBody.firstChild);
                }


                //Colocar dados na tabela
                json.forEach((row) => {
                    const tr = document.createElement("tr");

                    const td1 = document.createElement("td");
                    td1.textContent = row.name;
                    tr.appendChild(td1);

                    const td2 = document.createElement("td");
                    td2.textContent = row.cpf;
                    tr.appendChild(td2);

                    const td3 = document.createElement("td");
                    row.contacts.forEach((contact) => {
                        const text = contact.number + " - " + contact.description;
                        const p = document.createElement("p");
                        p.textContent = text;
                        td3.appendChild(p);
                    })
                    tr.appendChild(td3);

                    tableBody.appendChild(tr);
                });
            }

            document.addEventListener("DOMContentLoaded", () => {
                loadData();
            });
        </script>

</body>

</html>