<?= View::render('header', $header_view) ?>

<section id="page-body">

    <div class="page-content">

      <h1>Page not found!</h1>
      <p>Notification has been sent to the developers with this info : <?= $error ?></p>

      <?php
       // After calling the view, we will have to call a php function to send info to developers via mail, maybe unique class.
       ?>

    </div>

</section>


<?= View::render('footer') ?>
