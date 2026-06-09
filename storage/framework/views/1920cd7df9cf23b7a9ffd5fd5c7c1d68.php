<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Analytics System - Templats-Link -->
<script src="<?php echo e(asset('js/analytics.js')); ?>"></script>

<!-- Contact Form Analytics -->
<?php echo $__env->make('analytics.contact-form-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Test Analytics (temporário) -->
<?php if(config('app.debug')): ?>



<?php endif; ?>

<?php echo $__env->yieldPushContent('scripts'); ?>
<?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/main-Thema/inc/scripts.blade.php ENDPATH**/ ?>