<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?php if(\App\Helpers\HeadHelper::getLogoFooter()): ?>
                    <div class="footer-logo mb-3">
                        <img src="<?php echo e(\App\Helpers\HeadHelper::getLogoFooter()); ?>" 
                             alt="Logo Footer" 
                             class="footer-logo-img" 
                             style="max-height: 60px; max-width: 200px;">
                    </div>
                <?php else: ?>
                    <h5><i class="fas fa-link me-2"></i>Templats-link</h5>
                <?php endif; ?>
                
                <?php if(\App\Helpers\HeadHelper::getDescricaoFooter()): ?>
                    <p class="mb-0"><?php echo e(\App\Helpers\HeadHelper::getDescricaoFooter()); ?></p>
                <?php else: ?>
                    <p class="mb-0">Sua plataforma de templates e soluções web.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <?php if(\App\Helpers\HeadHelper::getFacebook()): ?>
                        <a href="<?php echo e(\App\Helpers\HeadHelper::getFacebook()); ?>" target="_blank" class="text-white me-3" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(\App\Helpers\HeadHelper::getTwitter()): ?>
                        <a href="<?php echo e(\App\Helpers\HeadHelper::getTwitter()); ?>" target="_blank" class="text-white me-3" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(\App\Helpers\HeadHelper::getInstagram()): ?>
                        <a href="<?php echo e(\App\Helpers\HeadHelper::getInstagram()); ?>" target="_blank" class="text-white me-3" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(\App\Helpers\HeadHelper::getLinkedin()): ?>
                        <a href="<?php echo e(\App\Helpers\HeadHelper::getLinkedin()); ?>" target="_blank" class="text-white me-3" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(\App\Helpers\HeadHelper::getYoutube()): ?>
                        <a href="<?php echo e(\App\Helpers\HeadHelper::getYoutube()); ?>" target="_blank" class="text-white" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <p class="mt-3 mb-0"><?php echo e(\App\Helpers\HeadHelper::getCopyrightFooter()); ?></p>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\templats-link\resources\views/main-Thema/inc/footer.blade.php ENDPATH**/ ?>