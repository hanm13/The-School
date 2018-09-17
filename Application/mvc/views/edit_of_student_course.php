<?= View::render('header', $header_view); ?>

  <div class="container mh-100">

    <div class='d-flex row'>

        <div class='col-sm flex-grow-0 border-right'>

            <span class="border-bottom"> <strong><h4>Courses <a href = "/course/add"> + </a></h4></strong></span>

            <?= View::render('courses', $courses_obj); ?>

        </div>

        <div class='col-sm flex-grow-0 border-right'>

            <span class="border-bottom"> <strong><h4>Students <a href = "/student/add"> + </a></h4></strong></span>

            <?= View::render('students', $students_obj); ?>

        </div>

        <div class='col-sm flex-grow-1 mh-100 border-right'>
            <?= View::render($more_data['container_action'], $more_data); ?>
        </div>

    </div>

  </div>



<?= View::render('footer'); ?>
