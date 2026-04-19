$(document).ready(function () {

    /**
     * Submit a book review via AJAX.
     * Prevents reload and appends the new review to the top of the list.
     */
    $('#review-form').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const bookId = $form.data('book-id');
        const rating = $form.find('select[name="rating"]').val();
        const reviewText = $form.find('textarea[name="review_text"]').val().trim();

        // simple front-end validation
        if (!rating || rating < 1 || rating > 5) {
            alert('Please select a valid star rating.');
            return;
        }
        if (!reviewText) {
            alert('Please share your thoughts before submitting.');
            return;
        }

        // prevent multiple clicks
        $submitBtn.prop('disabled', true).text('Posting...');

        $.ajax({
            url: 'submit_review.php',
            type: 'POST',
            data: {
                book_id: bookId,
                rating: rating,
                review_text: reviewText
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const review = response.review;

                    // construct the review card HTML
                    const reviewCard = `
                        <div class="list-group-item list-group-item-action border-0 mb-2 rounded pt-4 pb-4">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 font-weight-bold text-primary">
                                    <span class="badge badge-pill badge-primary mr-2" style="font-size: 1.1em;">👤</span>
                                    ${review.reviewer_name}
                                </h6>
                                <small class="text-muted font-italic">${review.created_at}</small>
                            </div>
                            <div class="mb-2 text-warning" style="font-size: 1.2rem;">
                                ${review.stars}
                            </div>
                            <p class="mb-0 text-muted" style="line-height: 1.6;">
                                ${review.review_text.replace(/\n/g, '<br>')}
                            </p>
                        </div>`;

                    // remove "no reviews" placeholder if present
                    $('#review-list').find('.alert-secondary').remove();

                    // show the new review at the top
                    $('#review-list').prepend(reviewCard);

                    // clean up the form
                    $form[0].reset();
                    $form.find('select[name="rating"]').val('5');

                    // show quick success message
                    const $alert = $(`
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            ${response.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`);
                    
                    $form.before($alert);
                    setTimeout(() => $alert.alert('close'), 3000);

                } else {
                    console.error('Review submission failed:', response.message);
                    alert(response.message || 'Error adding review.');
                }
            },
            error: function (xhr) {
                const res = xhr.responseJSON;
                console.error('AJAX error in submit_review:', xhr.status, res);

                if (xhr.status === 401) {
                    window.location.href = 'login.php';
                } else {
                    alert(res && res.message ? res.message : 'Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $submitBtn.prop('disabled', false).text('Submit Review');
            }
        });
    });

});
