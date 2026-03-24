<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/UserModel.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = Constants::MSG_ERR_REQUIRED_FIELDS;
    }
    elseif ($password !== $confirmPassword) {
        $error = Constants::MSG_ERR_PASSWORDS_MATCH;
    }
    else {
        try {
            $userModel = new UserModel($db);
            if ($userModel->findByEmail($email)) {
                $error = Constants::MSG_ERR_EMAIL_EXISTS;
            }
            else {
                $userId = $userModel->create([
                    'full_name' => $fullName,
                    'email' => $email,
                    'password' => $password
                ]);

                Logger::info("New user registered successfully. User ID: " . $userId . ", Email: " . $email);

                $_SESSION['flash_success'] = Constants::MSG_SUCCESS_REGISTER;
                header("Location: login.php");
                exit;
            }
        }
        catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$pageTitle = "Register";
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white text-center border-bottom-0 pt-4 pb-0">
                <ul class="nav nav-tabs card-header-tabs justify-content-center border-bottom-0">
                    <li class="nav-item">
                        <a class="nav-link text-muted border-0" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold border-0 border-bottom border-primary text-primary"
                            href="register.php"
                            style="border-bottom-width: 3px !important; background: transparent;">Register</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-5 pt-4">
                <h5 class="card-title text-center mb-4 font-weight-bold text-dark">Create an Account</h5>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger shadow-sm border-0">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
                </div>
                <?php
endif; ?>

                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="full_name" class="font-weight-bold text-secondary">Full Name</label>
                        <input type="text" name="full_name" id="full_name"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required autofocus
                            value="<?= htmlspecialchars($_POST['full_name'] ?? '', ENT_QUOTES, 'UTF-8')?>">
                    </div>

                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-secondary">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8')?>">
                    </div>

                    <div class="form-group">
                        <label for="password" class="font-weight-bold text-secondary">Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="confirm_password" class="font-weight-bold text-secondary">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="form-control form-control-lg bg-light border-0 shadow-sm" required>
                    </div>

                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block mt-4 shadow-sm font-weight-bold">Create Account</button>

                    <div class="text-center mt-4">
                        <span class="text-muted">Already have an account?</span> <a href="login.php"
                            class="text-decoration-none font-weight-bold">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>