<div>

  <div class = "border">

    <div class="border-bottom">
      <h5 class="d-inline"> <?= (isset($admin_obj['Name'])) ? "Edit Admin : " . $admin_obj['Name'] : "Add Admin";?></h5>
    </div>

    <form action="?id=<?= $admin_obj['id'] ?>" method="post">

      <? foreach ($form_errors as $key => $value):?>
        <?php createErrorMessage($value, "info"); ?>
      <?php endforeach ?>

      <div>
        <input type="submit" class="btn btn-primary" name="action" value="Save">
        <?= (isset($admin_obj['id'])) ? "<input type='submit' class='btn btn-primary' data-toggle='confirmation' name='action' value='Delete'>" : "" ; ?>
      </div>

      <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Name</label>
        <div class="col-10">
          <input placeholder="John Doe" name="editadd_name" class="form-control" type="text" value="<?= $admin_obj["Name"] ?>">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Phone</label>
        <div class="col-10">
          <input placeholder="000-000-0000" name="editadd_phone" class="form-control" type="text" value="<?= $admin_obj["Phone"] ?>">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Email</label>
        <div class="col-10">
          <input placeholder="email@email.com" name="editadd_email" class="form-control" type="email" value="<?= $admin_obj["Email"] ?>">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Role</label>
        <div class="col-10">
          <select class="form-control" id="role" name="editadd_role">
            <option value="1" <?= (isset($access_can_edit_role) && $access_can_edit_role == false)? "disabled" : "" ?>> Sales</option>
            <option value="2" <?= (isset($access_can_edit_role) && $access_can_edit_role == false)? "disabled" : "" ?>>Manager</option>
            <option value="3" disabled>Owner</option>
          </select>
        </div>
      </div>

      <?php if(!isset($admin_obj['id'])): ?>

      <div class="form-group row">
        <label for="exampleInputPassword1" class="col-2 col-form-label">Password</label>
        <div class="col-10">
          <input type="password" class="form-control" placeholder="Password" name="editadd_password">
        </div>
      </div>

      <?php endif;?>

      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Image</label>

        <div class="col-10">
          <label class="custom-file-label" for="customFile">Choose Image file</label>
          <input class="file_upload" type="file" width="150" id="customFile">
          <input type="hidden" id="image_hidden_input" name="editadd_image">
        </div>

      </div>

      <div class="form-group row">

        <label>Preview</label>

        <div class="col-10">
          <img src="<?= $admin_obj["Image"] ?>" class="imagePreview pull-left" width="50" height="50">
        </div>

      </div>

    </form>

  </div>


</div>
