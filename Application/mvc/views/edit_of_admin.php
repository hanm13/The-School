<?= View::render('header', $header_view); ?>

  <div class="container mh-100">

    <div class='d-flex row ml-auto'>

      <div class='col-sm-63 flex-grow-0 border-right'>

          <span class="border-bottom"> <strong><h4>Administrators <a href = "/admin/add"> + </a></h4></strong></span>

          <?php if($admins_amt > 0): ?>
            <?= View::render('admins', $admins_obj); ?>
          <?php endif; ?>


      </div>

        <div class='col-sm flex-grow-1 mh-100 border-right'>
            <?= View::render($more_data['container_action'], $more_data); ?>
        </div>

    </div>

  </div>



<?= View::render('footer'); ?>
