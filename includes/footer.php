<?php

?>
</div> <!-- end container -->

<footer class="bg-dark text-light mt-5 pt-4 pb-2">
    <div class="container">
        <div class="row">
            <!-- footer links -->
            <div class="col-md-4 mb-3">
                <h5>
                    <?php echo htmlspecialchars("ITReaderShelf", ENT_QUOTES, 'UTF-8'); ?>
                </h5>
                <p>A specialized technical book review platform.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Site Map</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light">Home</a></li>
                    <li><a href="search_results.php" class="text-light">Search Books</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="text-light">My Profile</a></li>
                    <?php
else: ?>
                    <li><a href="login.php" class="text-light">Login</a></li>
                    <?php
endif; ?>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li><a href="support.php" class="text-light">Support</a></li>
                    <li><a href="faq.php" class="text-light">FAQ</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-3 border-top pt-3 border-secondary">
            <div class="col text-center">
                <small>&copy;
                    <?php echo date('Y'); ?>
                    <?php echo htmlspecialchars("ITReaderShelf", ENT_QUOTES, 'UTF-8'); ?>. All rights reserved.
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- req js -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- core js -->
<script src="assets/js/app.js"></script>

</body>

</html>