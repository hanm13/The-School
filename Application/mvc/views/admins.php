<div class="scroll-box">

  <ul class="list-group d-inline nopadding" >

  <?php foreach ($admins_obj as $admin => $value):?>
    <li class="list-group-item d-block nopadding">

      <div class="d-flex row">
        <img class="administrators-img d-inline-block" src="<?=$value['Image'];?>" />
        <div class="pull-right d-inline-block">
          <a <?= ($value["showClickableAnchor"] == true || !isset($value["showClickableAnchor"])) ? "href='/admin/edit/?id=".$value['id'] ."'"  : "" ?>><span class="d-block"><?= $value['Name'] ?>, </span></a>
          <span class="d-block"><?= convertUserRoleToName($value['Role']) ?></span>
          <span class="d-block"><?= $value['Phone'] ?></span>
          <span class="d-block"><?= $value['Email'] ?></span>
        </div>
      </div>
    </li>

  <?php endforeach; ?>


  </ul>

</div>
