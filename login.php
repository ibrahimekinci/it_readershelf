<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/UserModel.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    }
    else {
        try {
            $userModel = new UserModel($db);
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // init session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];

                Logger::info("User successfully signed in. User ID: " . $user['id'] . ", Email: " . $user['email']);

                header("Location: profile.php");
                exit;
            }
            else {
                $error = "Invalid email or password provided.";
            }
        }
        catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$pageTitle = "Login";
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white text-center border-bottom-0 pt-4 pb-0">
                <ul class="nav nav-tabs card-header-tabs justify-content-center border-bottom-0">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold border-0 border-bottom border-primary text-primary"
                            href="login.php"
                            style="border-bottom-width: 3px !important; background: transparent;">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-muted border-0" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-5 pt-4">
                <h5 class="card-title text-center mb-4 font-weight-bold text-dark">Welcome Back!</h5>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger shadow-sm border-0">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
                </div>
                <?php
endif; ?>
                <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success shadow-sm border-0">
                    <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8')?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
                <?php
endif; ?>

                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-secondary">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required autofocus
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8')?>">
                    </div>

                    <div class="form-group mb-4">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="font-weight-bold text-secondary">Password</label>
                            <a href="#" class="small text-decoration-none text-muted"
                                onclick="alert('Forgot Password functionality is not yet available.'); return false;">Forgot
                                Password?</a>
                        </div>
                        <input type="password" name="password" id="password"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required>
                    </div>

                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block mt-4 shadow-sm font-weight-bold">Secure Login</button>

                    <div class="text-center mt-4">
                        <span class="text-muted">Don't have an account?</span> <a href="register.php"
                            class="text-decoration-none font-weight-bold">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>