
<?php if ($error == 404) { ?>
    
    <div class="content">
  <div class="row hero text-center text-lg-left">
    
    <div class="col-lg-6 text text-right">
      <div class="ml-auto">

          <p class="margin-top-s">
            We looked really closely,<br>
            but we could not find <br>
            what you are looking for.
          </p>
          <a class="btn btn-primary btn-lg" href="<?= ROOTPATH ?>/"><i class="far fa-house"></i> Go home</a>


      </div>
      
    </div>
    <div class="col-lg-6 image text text-left">
      <object alt="404" class="img-fluid mw-full" data="<?= ROOTPATH ?>/img/404.svg"></object>
    </div>
  </div>
</div>


<?php } else { 
    echo $error;
} ?>