<div>

  <div class = "border">

    <div class="border-bottom">
      <h5 class="d-inline"> Student: <?= $student_obj['Name'] ?> </h5>
      <a class="btn btn-primary" href="/student/edit/?id=<?= $student_obj['id'] ?>" role="button">Edit</a>
    </div>
    <?php createMessage($msg); ?>

    <div class="d-block text-justify">

      <img class="students-courses-administrators-img d-inline" src="<?= $student_obj['Image'];?>" />

      <ul class="list-group d-inline nopadding" >
        <li class="list-group-item d-inline-block nopadding"><?= $student_obj['Name'] ?></li>
        <li class="list-group-item d-block nopadding"><?= $student_obj['Phone'] ?></li>
        <li class="list-group-item d-block nopadding"><?= $student_obj['Email'] ?></li>

      </ul>

    </div>


      <table class="students-courses-administrators-table border-top">

        <?php foreach ($student_courses as $key => $value): ?>

          <tr><th>
            <img class="students-courses-administrators-img" src="<?=$value['course_image'];?>" />
            <?= $value['course_name']; ?>
          </th></tr>

        <?php endforeach; ?>

      </table>

  </div>


</div>
