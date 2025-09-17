<!--===== FOOTER AREA STARTS =======-->
<div class="vl-footer1-section-area sp8">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="footer-logo1">
          <?php if(\App\Helpers\HeadHelper::getLogoFooter()): ?>
              <img src="<?php echo e(\App\Helpers\HeadHelper::getLogoFooter()); ?>" alt="<?php echo e(\App\Helpers\HeadHelper::getMetaTitle('global')); ?>">
          <?php else: ?>
              <img src="<?php echo e(asset('temas/Temateste/assets/img/logo/logo1.png')); ?>" alt="Temateste">
          <?php endif; ?>
          <div class="space24"></div>
          <p><?php echo e(\App\Helpers\HeadHelper::getDescricaoFooter() ?: 'We are committed to providing with the highest level of service expertise business and finance if you have any.'); ?></p>
          <div class="space24"></div>
          <ul>
            <?php $redesSociais = \App\Helpers\HeadHelper::getRedesSociais(); ?>
            <?php if($redesSociais['facebook']): ?>
                <li><a href="<?php echo e($redesSociais['facebook']); ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
            <?php endif; ?>
            <?php if($redesSociais['linkedin']): ?>
                <li><a href="<?php echo e($redesSociais['linkedin']); ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
            <?php endif; ?>
            <?php if($redesSociais['instagram']): ?>
                <li><a href="<?php echo e($redesSociais['instagram']); ?>" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
            <?php endif; ?>
            <?php if($redesSociais['youtube']): ?>
                <li><a href="<?php echo e($redesSociais['youtube']); ?>" target="_blank" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
            <?php endif; ?></ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-md-none d-block"></div>
        <div class="vl-footer-widget first-padding">
          <h3>Quick Links</h3>
          <div class="space4"></div>
          <ul>
            <li><a href="http://localhost/templats-link/public/sobre">About Us</a></li>
            <li><a href="service.html">Our Services</a></li>
            <li><a href="project.html">Case Studies</a></li>
             <li><a href="pricing.html">Pricing Plan</a></li>
            <li><a href="http://localhost/templats-link/public/contato">Contact Us</a></li>
          </ul>
        </div>
      </div> 
      <div class="col-lg-3 col-md-6">
        <div class="vl-footer-widget">
          <div class="space30 d-lg-none d-block"></div>
          <h3>Contact Us</h3>
          <ul>
            <li><a href="tel:+11234567890"><img src="<?php echo e(asset('temas/Temateste/assets/img/icons/phn1.svg')); ?>" alt="">+1 123 456 7890</a></li>
            <li><a href="team.html#"><img src="<?php echo e(asset('temas/Temateste/assets/img/icons/location1.svg')); ?>" alt="">421 Allen, Mexico 4233</a></li>
            <li><a href="https://html.vikinglab.agency/finazze/renevagency@com"><img src="<?php echo e(asset('temas/Temateste/assets/img/icons/email1.svg')); ?>" alt="">finazzeconsult@com</a></li>
            <li><a href="team.html#"><img src="<?php echo e(asset('temas/Temateste/assets/img/icons/global1.svg')); ?>" alt="">finazzeconsult.com</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="vl-footer-widget">
          <div class="space30 d-lg-none d-block"></div>
          <h3>Instagram Post</h3>
          <div class="space8"></div>
          <div class="row">
            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img1.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img2.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img3.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img4.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img5.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="<?php echo e(asset('temas/Temateste/assets/img/all-images/footer/footer-img6.png')); ?>" alt="">
                <div class="icons">
                  <a href="team.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="space60"></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="vl-copyright-area">
          <p><?php echo e(\App\Helpers\HeadHelper::getCopyrightFooter() ?: '© Copyright 2025 - Temateste. All Right Reserved'); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===== FOOTER AREA ENDS =======--><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/temas/Temateste/inc/footer.blade.php ENDPATH**/ ?>