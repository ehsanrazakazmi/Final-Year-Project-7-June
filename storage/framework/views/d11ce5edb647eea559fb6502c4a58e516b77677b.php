  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
      <div class="col-lg-8 mb-4 mx-auto text-center">
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Our Society
          </a>
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              About Us
          </a>
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Our Team
          </a>
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              our services
          </a>
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Blog about us
          </a>
          <a href="#" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              contact us
          </a>
      </div>
        <?php if(!auth()->user() || \Request::is('static-sign-up')): ?> 
          
        <?php endif; ?>
      </div>
      
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
<?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/layouts/footers/guest/footer.blade.php ENDPATH**/ ?>