<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/UserModel.php';

$userModel = new UserModel($db);
$userId = $_SESSION['user_id'];
$user = $userModel->findById($userId);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($fullName) || empty($email)) {
            $error = Constants::MSG_ERR_REQUIRED_FIELDS;
        }
        else {
            try {
                // check existing email
                if ($email !== $user['email'] && $userModel->findByEmail($email)) {
                    $error = Constants::MSG_ERR_EMAIL_EXISTS;
                }
                else {
                    $userModel->update($userId, [
                        'full_name' => $fullName,
                        'email' => $email
                    ]);
                    $success = "Profile updated successfully!";
                    $_SESSION['full_name'] = $fullName;
                    $user['full_name'] = $fullName;
                    $user['email'] = $email;
                }
            }
            catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
    elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = Constants::MSG_ERR_REQUIRED_FIELDS;
        }
        elseif (!password_verify($currentPassword, $user['password_hash'])) {
            $error = "Current password is incorrect.";
        }
        elseif ($newPassword !== $confirmPassword) {
            $error = Constants::MSG_ERR_PASSWORDS_MATCH;
        }
        else {
            try {
                $userModel->updatePassword($userId, $newPassword);
                $success = "Password changed successfully!";
            }
            catch (Exception $e) {
                $error = "Unable to change password.";
            }
        }
    }
}

$pageTitle = "My Dashboard";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Dashboard', 'url' => '#', 'active' => true]
];
require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-4 mb-5">
    <!-- sidebar -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm border-0">
            <a href="profile.php" class="list-group-item list-group-item-action font-weight-bold active p-3">Profile</a>
            <a href="favorites.php" class="list-group-item list-group-item-action p-3">My Favourites</a>
            <a href="my_reviews.php" class="list-group-item list-group-item-action p-3">My Reviews</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger p-3">Logout</a>
        </div>
    </div>

    <!-- forms -->
    <div class="col-md-9">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>
        <?php if (!empty($success)): ?>
        <div class="alert alert-success shadow-sm border-0">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>

        <!-- update profile -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="font-weight-bold text-dark">Update Profile</h4>
                <p class="text-muted small">Manage your personal information and contact details.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="profile.php" method="POST">
                    <div class="form-group row">
                        <label for="full_name" class="col-sm-3 col-form-label font-weight-bold text-secondary">Profile
                            Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="full_name" class="form-control bg-light border-0 shadow-sm"
                                id="full_name" value="<?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8')?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label font-weight-bold text-secondary">Email
                            Address</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control bg-light border-0 shadow-sm" id="email"
                                value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8')?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" name="update_profile"
                                class="btn btn-primary font-weight-bold shadow-sm px-4">Update Profile Name
                                Button</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- pw pw -->
        <div class="card shadow-sm border-0" id="pwd-section">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="font-weight-bold text-dark">Change Password</h4>
                <p class="text-muted small">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="profile.php" method="POST">
                    <div class="form-group row">
                        <label for="current_password"
                            class="col-sm-4 col-form-label font-weight-bold text-secondary">Current Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="current_password"
                                class="form-control bg-light border-0 shadow-sm" id="current_password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="new_password" class="col-sm-4 col-form-label font-weight-bold text-secondary">New
                            Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="new_password" class="form-control bg-light border-0 shadow-sm"
                                id="new_password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="confirm_password"
                            class="col-sm-4 col-form-label font-weight-bold text-secondary">Confirm New Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="confirm_password"
                                class="form-control bg-light border-0 shadow-sm" id="confirm_password" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" name="change_password"
                                class="btn btn-primary font-weight-bold shadow-sm px-4">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>