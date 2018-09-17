<div class="scroll-box">

  <ul class="list-group d-inline nopadding" >

  <?php foreach ($courses_obj as $course => $value):?>

    <li class="list-group-item d-block nopadding">
      <a href="/course/view/?id=<?= $value['id'];?>">
          <img class="students-courses-administrators-img d-inline-block" src="<?=$value['Image'];?>" />
          <div class="pull-right d-inline-block">
            <span class="d-block"><?= $value['Name'] ?></span>
          </div>
      </a>
    </li>

  <?php endforeach; ?>


  </ul>

</div>
