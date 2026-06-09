<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-text">                
                <p><?php echo \App\Helpers\NavbarHelper::getCopyrightFooter(); ?></p>
            </div>
            <div class="footer-social">
                <?php if(\App\Helpers\NavbarHelper::getLinkedin()): ?>                    
                    <a href="<?php echo e(\App\Helpers\NavbarHelper::getLinkedin()); ?>" class="social-link" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a>
                <?php endif; ?>
                
                <?php if(\App\Helpers\NavbarHelper::getGithub()): ?>                    
                    <a href="<?php echo e(\App\Helpers\NavbarHelper::getGithub()); ?>" class="social-link" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                <?php endif; ?>                 
                <a href="https://wa.me/55<?php echo e(preg_replace('/[()\s-]+/', '', \App\Helpers\NavbarHelper::getTelefone())); ?>" class="social-link" target="_blank"><i class="fab fa-whatsapp"></i></a>
                <a href="mailto:<?php echo e(\App\Helpers\NavbarHelper::getEmailContato()); ?>" class="social-link"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </div>
</footer><?php /**PATH C:\inetpub\wwwroot\templats-link\resources\views/temas/Portfolio/inc/footer.blade.php ENDPATH**/ ?>