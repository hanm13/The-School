<?= View::render('header', $header_view); ?>

  <div class="container mh-100">

    <div class='d-flex row ml-auto'>

        <div class='col-sm-3 flex-grow-0 border-right'>

            <span class="border-bottom"> <strong><h4>Courses <?= ($access_can_add == true) ? "<a href = '/course/add'> + </a>" : "" ?></h4></strong></span>

            <?php if($courses_amt > 0): ?>
              <?= View::render('courses', $courses_obj); ?>
            <?php endif; ?>


        </div>

        <div class='col-sm-3 flex-grow-0 border-right'>

            <span class="border-bottom"> <strong><h4>Students <?= ($access_can_add == true) ? "<a href = '/student/add'> + </a>" : "" ?></h4></strong></span>

            <?php if($students_amt > 0): ?>
              <?= View::render('students', $students_obj); ?>
            <?php endif; ?>


        </div>

        <div class='col-sm flex-grow-1 mh-100 border-right'>
            <?= View::render('defaultcoursesstudentscontainer', $default_container_data); ?>
        </div>

    </div>

  </div>



<?= View::render('footer'); ?>
