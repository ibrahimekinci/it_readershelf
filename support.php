<?php

session_start();
require_once __DIR__ . '/includes/Constants.php';

$pageTitle = "Support";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Support', 'url' => 'support.php', 'active' => true]
];

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-7 mb-5">
        <div class="card shadow-sm mt-3 border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Contact Support</h4>
            </div>
            <div class="card-body p-4">
                <p class="lead">We're here to help.</p>
                <p class="text-muted">If you need assistance with your account, reporting a bug, or have any other
                    questions regarding the ITReaderShelf platform, please reach out to our support team using the
                    information below.</p>

                <hr class="my-4">

                <div class="mb-3">
                    <h6 class="text-uppercase text-secondary font-weight-bold">Email Support</h6>
                    <p><a href="mailto:support@itreadershelf.com"
                            class="text-decoration-none font-weight-bold">support@itreadershelf.com</a></p>
                </div>

                <div class="mb-3">
                    <h6 class="text-uppercase text-secondary font-weight-bold">Phone Support</h6>
                    <p>+1 (555) 123-4567</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-uppercase text-secondary font-weight-bold">Business Hours</h6>
                    <p>Monday - Friday<br>9:00 AM - 5:00 PM EST</p>
                </div>

                <div class="alert alert-info mt-4 mb-0" role="alert">
                    <small>We aim to respond to all technical inquiries within 24-48 business hours. For common
                        questions, please check our <a href="faq.php" class="alert-link">FAQ page</a> first.</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>