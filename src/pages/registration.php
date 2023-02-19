<?php require_once('../includes/headerUser.php') ?>
<?php require_once('../includes/sidenavbar.php') ?>
<?php require_once('../includes/content.php') ?>

<script type="text/html" id="templateCollaborators">
    <form id="form-collaborators">
        <div class="row ml-0">
            <div class="col-12">
                <p>Tem certeza que deseja cadastrar esse colaborador?</p>
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

<div class="container-fluid pt-4 px-4">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-9 col-xl-7">
                <div class="card shadow-2-strong card-registration"
                    style="border-radius: 15px; background-color: antiquewhite;">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Cadastro de colaborador</h3>
                        <form id="formRegistration">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="mb-2" for="txtName">Nome</label>
                                        <input class="form-control form-control-lg" type="text" id="txtName"
                                            name="txtName">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="mb-2" for="txtName">Email</label>
                                        <input class="form-control form-control-lg" type="email" id="txtEmail"
                                            name="txtEmail">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div class="form-outline">
                                        <label class="mb-2" for="txtCPF">CPF</label>
                                        <input class="form-control form-control-lg" type="text" id="txtCPF"
                                            name="txtCPF">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 pt-2">
                                <input class="btn btn-primary btn-lg" type="submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php') ?>

<script>
    getUser();
    registrationCollaborators();
</script>