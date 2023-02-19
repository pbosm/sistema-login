<?php require_once('../includes/headerUser.php') ?>
<?php require_once('../includes/sidenavbar.php') ?>
<?php require_once('../includes/content.php') ?>

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

<script type="text/html" id="templateUsers">
<tr data-table-order="{{order}}">
    <td data-column="name" style="white-space: nowrap">
        {{name}}
    </td>
    <td data-column="email" style="white-space: nowrap">
        {{email}}
    </td>
    <td id="cpf" data-column="cpf" style="white-space: nowrap">
        {{cpf}}
    </td>
    <td style="white-space: nowrap;" class="text-center">
        <button class="btn btn-actions" onclick="updateUser('{{iduser}}', '{{name}}', '{{email}}', '{{cpf}}')" tooltip="Editar usuário" style="background-color: #0d6efd;">
            <i class="fas fa-edit"></i>
        </button>
    </td>
    <td style="white-space: nowrap;" class="text-center">
        <button class="btn btn-actions" onclick="deleteUser('{{iduser}}', '{{name}}')" tooltip="Excluir usuário" style="background-color: #0d6efd;">
            <i class="fas fa-user-times"></i>
        </button>
    </td>
</tr>
</script>


<div class="container-fluid pt-4 px-4">

    <div class="row g-4">
        <div class="col-6 mb-3">
            <button class="btn w-100 btn-users" onclick="viewLaunch('users')"
                style="background-color: aqua;">
                <i class="fas fa-user"></i><span class="no-mobile">&ensp;Usuários</span>
            </button>
        </div>
        <div class="col-6 mb-3">
            <button class="btn w-100 btn-collaborators" onclick="viewLaunch('collaborators')"
                style="background-color: aqua;">
                <i class="fas fa-user-tie"></i><span class="no-mobile">&ensp;Colaboradores</span>
            </button>
        </div>
    </div>

    <div class="dashboard mb-3" id="dashboard">
        <div class="text-center title-page-text" style="color: white;">
            Relacionamento de usuários
        </div>
    </div>

    <div class="col-md-12">
        <form id="tableForm">
            <select name="txtNames" id="txtNames" class="form-select" aria-label="Default select example">
                <!-- <option class="clean" disabled="disabled" selected>Filtro por nome</option> -->
            </select>
            <button class="btn w-100 mt-3" style="background-color: aqua;" onclick="filterSearch()">Confirmar</button>
        </form>
    </div>

    <div class="row g-4 mt-5">
        <div class="col-12" id="table-users">
            <div class="table-responsive">
                <table class="table table-striped users">
                    <thead>
                        <tr>
                            <th>Usuário
                                <span class="order" data-table-order="false" data-column-order="name-users"
                                    onclick="toggleTableOrder('name-users', 'alphabet')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>Email
                                <span class="order" data-table-order="false" data-column-order="email-users"
                                    onclick="toggleTableOrder('email-users', 'alphabet')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>CPF
                                <span class="order" data-table-order="false" data-column-order="cpf-users"
                                    onclick="toggleTableOrder('cpf-users', 'numeric')">
                                    <i class="fas fa-sort-up"></i>
                                    <i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th style="white-space: nowrap;"></th>
                            <th style="white-space: nowrap;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="previous page-link" href="javascript: loadLaunch('previous');">Anterior</a>
                    <div class="previousCollaborators page-link" style='display: none'>
                        <a href="javascript:paginationCollaborators('previousCollaborators')" style="text-decoration: none">Anterior</a>
                    </div>
                </li>
                <li class="page-item"><a class="page-link page-number">1</a></li>
                <li class="page-item">
                    <a class="next page-link" href="javascript: loadLaunch('next');">Próximo</a>
                    <div class="nextCollaborators page-link" style='display: none'>
                        <a href="javascript:paginationCollaborators('nextCollaborators')" style="text-decoration: none">Próximo</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php require_once('../includes/footer.php') ?>

<script>
    getUser();
    loadLaunch();
    selectUsers();
    // filterSearch();
</script>