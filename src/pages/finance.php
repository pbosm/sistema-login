<?php require_once('../includes/headerUser.php') ?>
<?php require_once('../includes/sidenavbar.php') ?>
<?php require_once('../includes/content.php') ?>

<script type="text/html" id="templateCreate">
<div class="row ml-0">
    <div class="col-12">
        <form id="createForm">
            <div class="row ml-0">
                <div class="form-group row mb-3">
                    <label for="txtProduct" class="col-sm-2 col-form-label">Produto</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="txtProduct" name="txtProduct">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtValue" class="col-sm-2 col-form-label">Valor</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="txtValue" name="txtValue">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtPlace" class="col-sm-2 col-form-label">Lugar</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="txtPlace" name="txtPlace">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtPassword" class="col-sm-2 col-form-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="txtPassword">
                    </div>
                </div>
                <button class="btn w-100 mt-3" style="background-color: aqua;">Confirmar</button>
            </div>
        </form>
    </div>
</div>
</script>

<script type="text/html" id="templateEdit">
<div class="row ml-0">
    <div class="col-12">
        <form id="editForm">
            <div class="row ml-0">
                <div class="form-group row mb-3">
                    <label for="txtName" class="col-sm-2 col-form-label">Usuário</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="txtName" name="txtName">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtCPF" class="col-sm-2 col-form-label">CPF</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="txtCPF" name="txtCPF">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="txtPassword" class="col-sm-2 col-form-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="txtPassword">
                    </div>
                </div>
                <button class="btn w-100 mt-3" style="background-color: aqua;">Confirmar</button>
            </div>
        </form>
    </div>
</div>
</script>

<script type="text/html" id="templateDelete">
    <form id="deleteForm">
        <div class="row ml-0">
            <div class="col-12">
                <p class="teste">Tem certeza que deseja excluir usuario?</p>
            </div>
            <div class="col-12 mt-0">               
                <div class="form-group">
                    <label for="txtPassword">Senha</label>
                    <input type="password" id="txtPassword" name="txtPassword">
                </div>
                    <button class="btn w-100 mt-3" style="background-color: aqua;">Confirmar</button>
            </div>
        </div>
    </form>
</script>

<script type="text/html" id="templateFinance">
<tr data-table-order="{{order}}">
    <td data-column="product" style="white-space: nowrap">
        {{product}}
    </td>
    <td data-column="place" style="white-space: nowrap">
        {{place}}
    </td>
    <td data-column="value" style="white-space: nowrap">
        {{value}}
    </td>
    <td data-column="data" style="white-space: nowrap">
        {{date}}
    </td>
    <td></td>
    <!-- <td style="white-space: nowrap;" class="text-center">
        <button class="btn btn-actions" onclick="updateUser('{{iduser}}', '{{name}}', '{{email}}', '{{cpf}}')" tooltip="Editar usuário" style="background-color: #0d6efd;">
            <i class="fas fa-edit"></i>
        </button>
    </td>
    <td style="white-space: nowrap;" class="text-center">
        <button class="btn btn-actions" onclick="deleteUser('{{iduser}}', '{{name}}')" tooltip="Excluir usuário" style="background-color: #0d6efd;">
            <i class="fas fa-user-times"></i>
        </button>
    </td> -->
</tr>
</script>

<div class="container-fluid pt-4 px-4">

    <div class="dashboard mb-3" id="dashboard">
        <div class="text-center title-page-text" style="color: white;">
            Finanças acompanhamento
            <div class="msg" style="display: inline;"></div>
            <button class="btn btn-primary" style="float: right" onclick="createProduct()">Cadastrar comprar</button>
        </div>
    </div>

    <div class="col-md-12 mt-5">
        <form id="tableMonth">
            <select name="txtMonth" id="txtMonth" class="form-select" aria-label="Default select example">
                <!-- <option class="clean" disabled="disabled" selected>Filtro por nome</option> -->
            </select>
            <button class="btn w-100 mt-3" style="background-color: aqua;" onclick="filterDate()">Confirmar</button>
        </form>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12" id="table-product">
            <div class="table-responsive">
                <table class="table table-striped product">
                    <thead>
                        <tr>
                            <th>Produto
                                <span class="order" data-table-order="false" data-column-order="product-product"
                                    onclick="toggleTableOrder('product-product', 'alphabet')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>Lugar
                                <span class="order" data-table-order="false" data-column-order="place-product"
                                    onclick="toggleTableOrder('place-product', 'alphabet')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>Valor
                                <span class="order" data-table-order="false" data-column-order="value-product"
                                    onclick="toggleTableOrder('value-product', 'money')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>Dia
                                <span class="order" data-table-order="false" data-column-order="date-product"
                                    onclick="toggleTableOrder('date-product', 'date')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th style="text-align: right">Total: 
                                <span id="totalValue"></span>
                            </th>
                            <!-- <th style="white-space: nowrap;"></th> -->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php') ?>

<script>
    getUser();
    loadFinance();
    loadDates();
</script>