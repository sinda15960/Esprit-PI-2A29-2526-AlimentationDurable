<?php
$isBack = (isset($_GET['office']) && $_GET['office'] === 'back');
?>

<?php if ($isBack): ?>
</div>
<?php else: ?>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>