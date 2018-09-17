<div>

  <div class = "border">

    <div class="border-bottom">
      <h5 class="d-inline"> Course: <?= $course_obj['Name'] ?> </h5>

      <?php if($access_can_edit): ?>

        <a class="btn btn-primary" href="/course/edit/?id=<?= $course_obj['id'] ?>" role="button">Edit</a>

      <?php endif; ?>

    </div>
    <?php createMessage($msg); ?>

    <img class="students-courses-administrators-img" src="<?=$course_obj['Image'];?>" />

      <span class="entityInfoSpan">Course <?= $course_obj['Name'] ?>, <?= count($course_students) ?> Students</span>
      <div>
        <textarea readonly class="form-control px-2" rows="5"><?= $course_obj['Description'] ?></textarea>
      </div>

      <ul class="list-group d-inline nopadding" >
        <?php foreach ($course_students as $key => $value): ?>

          <li class="list-group-item d-block nopadding no-border">
            <img class="students-courses-administrators-img" src="<?=$value['Image'];?>" />
            <?= $value['Name']; ?>
          </li>

        <?php endforeach; ?>

      </ul>

  </div>


</div>
