$(document).ready(function () {

    /**
     * Handle favorite toggle via AJAX.
     * Uses event delegation for buttons in lists (index/search) and the detail page.
     */
    $(document).on('click', '.fav-btn', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const bookId = $btn.data('book-id');

        if ($btn.hasClass('loading')) return;
        $btn.addClass('loading');

        $.ajax({
            url: 'toggle_favorite.php',
            type: 'POST',
            data: { book_id: bookId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.action === 'added') {
                        // detail page (full button)
                        if ($btn.hasClass('btn-block')) {
                            $btn.removeClass('btn-outline-primary').addClass('btn-outline-danger');
                            $btn.html('❤️ Remove from Favourites');
                        } 
                        // listing or search results (icon only)
                        else {
                            $btn.removeClass('text-secondary').addClass('text-danger');
                            $btn.html('❤️');
                        }
                    } else {
                        // detail page (full button)
                        if ($btn.hasClass('btn-block')) {
                            $btn.removeClass('btn-outline-danger').addClass('btn-outline-primary');
                            $btn.html('🤍 Add to Favourites');
                        } 
                        // listing or search results (icon only)
                        else {
                            $btn.removeClass('text-danger').addClass('text-secondary');
                            $btn.html('🤍');
                        }
                    }
                } else {
                    console.error('Favorite toggle failed:', response.message);
                    alert(response.message || 'An error occurred.');
                }
            },
            error: function (xhr) {
                const res = xhr.responseJSON;
                console.error('AJAX error in toggle_favorite:', xhr.status, res);
                
                if (xhr.status === 401) {
                    window.location.href = 'login.php';
                } else {
                    alert(res && res.message ? res.message : 'An error occurred.');
                }
            },
            complete: function () {
                $btn.removeClass('loading');
            }
        });
    });

});
