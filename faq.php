<?php

session_start();
$pageTitle = "FAQ";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'FAQ', 'url' => 'faq.php', 'active' => true]
];

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-9 mb-5">
        <h3 class="mb-4 mt-2 border-bottom pb-2">Frequently Asked Questions</h3>

        <div class="accordion" id="faqAccordion">

            <div class="card shadow-sm mb-2 border-0">
                <div class="card-header bg-white border-bottom-0" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none text-dark font-weight-bold" type="button"
                            data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                            aria-controls="collapseOne">
                            How do I leave a review for a book?
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                    <div class="card-body pt-0 text-muted">
                        To leave a review, you must first create an account and log in. Once logged in, navigate to any
                        book's detail page and you will find a form at the bottom to submit your rating and comments.
                    </div>
                </div>
            </div>


            <div class="card shadow-sm mb-2 border-0">
                <div class="card-header bg-white border-bottom-0" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-decoration-none text-dark font-weight-bold"
                            type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">
                            Can I change my account email?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                    <div class="card-body pt-0 text-muted">
                        Currently, email addresses cannot be modified after registration to ensure strict data
                        integrity. If you urgently need a new email tied to your profile, please contact support.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>