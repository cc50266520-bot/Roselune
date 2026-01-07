<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-danger" href="/Hasnaachakik_51831003/index.php">
            Roselune
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/Hasnaachakik_51831003/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/Hasnaachakik_51831003/products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="/Hasnaachakik_51831003/upload_photo.php">Match Makeup</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#">
                            Hi, <?php echo $_SESSION['user_name']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="/Hasnaachakik_51831003/auth/logout.php">
                            Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="/Hasnaachakik_51831003/auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="/Hasnaachakik_51831003/auth/register.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>
