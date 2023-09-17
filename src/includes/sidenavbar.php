<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar navbar-dark">
        <a href="" class="navbar-brand mx-4 mb-3">
            <h1 class="text-primary" style="font-size: 15px;"><i class="fa fa-user-edit me-2"></i>Projeto de Login com
                JS</h1>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <i class="fa fa-user light me-2" style="font-size: 25px; color: white;"></i>
            </div>
            <div class="ms-3">
                <h6 class="name-user mb-0" style="color: white;"></h6>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="../pages/indexmaster.php" class="nav-item nav-link indexmaster"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <!-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="fa fa-laptop me-2"></i>Elements</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="button.html" class="dropdown-item">Buttons</a>
                    <a href="typography.html" class="dropdown-item">Typography</a>
                    <a href="element.html" class="dropdown-item">Other Elements</a>
                </div>
            </div> -->
            <a href="../pages/registration.php" class="nav-item nav-link registration"><i class="fas fa-book-open me-2"></i>Cadastro</a>
            <a href="../pages/launch.php" class="nav-item nav-link launch"><i class="fa fa-keyboard me-2"></i>Lançamento</a>
            <a href="../pages/finance.php" class="nav-item nav-link finance"><i class="fa fa-table me-2"></i>Finanças</a>
            <!-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Pages</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="signin.html" class="dropdown-item">Sign In</a>
                    <a href="signup.html" class="dropdown-item">Sign Up</a>
                    <a href="404.html" class="dropdown-item">404 Error</a>
                    <a href="blank.html" class="dropdown-item">Blank Page</a>
                </div>
            </div> -->
        </div>
    </nav>
</div>
<!-- Sidebar End -->

<script>

	let current_url = window.location.href;
	let formatURL = current_url.slice(current_url.indexOf('pages')).split('/')[1];

    if(formatURL == 'registration.php') {
	    $('.registration').addClass('active');
	} 

    if(formatURL == 'indexmaster.php') {
	    $('.indexmaster').addClass('active');
	}

    if(formatURL == 'launch.php') {
	    $('.launch').addClass('active');
	}
    
    if(formatURL == 'finance.php') {
	    $('.finance').addClass('active');
	}

</script>