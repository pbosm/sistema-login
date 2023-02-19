<?php require_once('../includes/headerUser.php') ?>
<?php require_once('../includes/sidenavbar.php') ?>
<?php require_once('../includes/content.php') ?>

<script type="text/html" id="templateUsers">
<tr data-table-order="{{order}}">
    <td data-column="name" style="white-space: nowrap">
        {{name}}
    </td>
    <td data-column="email" style="white-space: nowrap">
        {{email}}
    </td>
    <td data-column="cpf" style="white-space: nowrap">
        {{cpf}}
    </td>
</tr>
</script>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-6" id="table-users">
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
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div class="col-sm-12 col-xl-6"> -->
        <div class="col-6">
            <div class="rounded p-4" style="height: 97%; background-color: antiquewhite;">
                <h6 class="mb-4">Registros de cadastro</h6>
                <canvas id="line-chart"></canvas>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="previous page-link" href="javascript: paginationUsers('previous');">Anterior</a>
                    <a class="previousSearch page-link" href="javascript: searchUser('previousSearch');"
                        style="display: none;">Anterior</a>
                </li>
                <li class="page-item"><a class="page-link page-number">1</a></li>
                <li class="page-item">
                    <a class="next page-link" href="javascript: paginationUsers('next');">Próximo</a>
                    <a class="nextSearch page-link" href="javascript: searchUser('nextSearch');"
                        style="display: none;">Próximo</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php require_once('../includes/footer.php') ?>

<script>
    getUser();
    getUsers();
    loadChart();
</script>