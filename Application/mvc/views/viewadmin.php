<div>

  <div class = "border">
    <div class="border-bottom">
      <h5 class="d-inline"> Admin: <?= $admin_obj['Name'] ?> </h5>
      <?php if ($access_can_edit): ?>
        <a class="btn btn-primary" href="/admin/edit/?id=<?= $admin_obj['id'] ?>" role="button">Edit</a>
      <?php endif; ?>
    </div>
    <?php createMessage($msg); ?>

    <div class="d-block text-justify">

      <img class="students-courses-administrators-img d-inline" src="<?= $admin_obj['Image'];?>" />

      <ul class="list-group d-inline nopadding" >
        <li class="list-group-item d-inline-block nopadding"><?= $admin_obj['Name'] ?>, <?= $admin_role ?></li>
        <li class="list-group-item d-block nopadding"><?= $admin_obj['Phone'] ?></li>
        <li class="list-group-item d-block nopadding"><?= $admin_obj['Email'] ?></li>

      </ul>

    </div>

  </div>


</div>
