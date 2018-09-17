<?= View::render('header', $header_view); ?>

  <div class="container mh-100">

    <div class='d-flex row ml-auto'>

        <div class='col-sm-3 flex-grow-0 border-right'>

            <h1 class="border-bottom"> <strong>Courses <?= ($access_can_add == true) ? "<a href = '/course/add'> + </a>" : "" ?></strong> </h1>

            <?php if($courses_amt > 0): ?>
              <?= View::render('courses', $courses_obj); ?>
            <?php endif; ?>


        </div>

        <div class='col-sm-3 flex-grow-0 border-right'>

            <h1 class="border-bottom"> <strong>Students <?php if($access_can_add == true): ?><a href = "/student/add"> + </a><?php endif; ?></strong> </h1>

            <?php if($students_amt > 0): ?>
              <?= View::render('students', $students_obj); ?>
            <?php endif; ?>


        </div>

        <div class='col-sm flex-grow-1 mh-100'>
            <?= View::render($more_data['container_action'], $more_data); ?>
        </div>

    </div>

  </div>



<?= View::render('footer'); ?>
