<div class="scroll-box">

  <ul class="list-group d-inline nopadding" >

  <?php foreach ($students_obj as $student => $value):?>

    <li class="list-group-item d-block nopadding">
      <a href="/student/view/?id=<?= $value['id'];?>">
        <img class="students-courses-administrators-img d-inline-block" src="<?=$value['Image'];?>" />
        <div class="pull-right d-inline-block">
          <span class="d-block"><?= $value['Name'] ?></span>
          <span class="d-block"><?= $value['Phone'] ?></span>
        </div>
      </a>
    </li>

  <?php endforeach; ?>


  </ul>

</div>
