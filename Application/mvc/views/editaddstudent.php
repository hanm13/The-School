<div>

  <div class = "border">

    <div class="border-bottom">
      <h5 class="d-inline"> <?= (isset($student_obj['Name'])) ? "Edit Student : " . $student_obj['Name'] : "Add Student";?></h5>



    </div>

    <form action="?id=<?= $student_obj['id'] ?>" method="post">

      <? foreach ($form_errors as $key => $value):?>
        <?php createErrorMessage($value, "info"); ?>
      <?php endforeach ?>

      <div>
        <input type="submit" class="btn btn-primary" name="action" value="Save">
        <?= (isset($student_obj['id'])) ? "<input type='submit' class='btn btn-primary' data-toggle='confirmation' name='action' value='Delete'>" : "" ; ?>
      </div>

      <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Name</label>
        <div class="col-10">
          <input placeholder="John Doe" name="editadd_name" class="form-control" type="text" value="<?= $student_obj["Name"] ?>" id="example-text-input">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Phone</label>
        <div class="col-10">
          <input placeholder="000-000-0000" name="editadd_phone" class="form-control" type="text" value="<?= $student_obj["Phone"] ?>" id="example-text-input">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Email</label>
        <div class="col-10">
          <input placeholder="email@email.com" name="editadd_email" class="form-control" type="email" value="<?= $student_obj["Email"] ?>" id="example-email-input">
        </div>
      </div>

      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Image</label>

        <div class="col-10">
          <label class="custom-file-label" for="customFile">Choose Image file</label>
          <input class="file_upload" type="file" id="customFile" width="150">
          <input type="hidden" id="image_hidden_input" name="editadd_image">
        </div>

      </div>

      <div class="form-group row">

        <label>Preview</label>

        <div class="col-10">
          <img src="<?= $student_obj["Image"] ?>" class="imagePreview pull-left" width="50" height="50">
        </div>

      </div>

      <div class="form-group row">
        <h4>Courses: </h4>

        <div>

          <?php foreach ($courses_obj as $key => $value): ?>

            <div class="form-check">
              <input name="editadd_courses[]" type="checkbox" class="form-check-input" id="<?=$value['id']?>" value="<?= $value['id']?>" <?= in_array($value['id'], $student_courses_ids) ? "checked" : ""; ?>>
              <label class="form-check-label" for="<?=$value['id']?>"><?=$value['Name']?></label>
            </div>

          <?php endforeach; ?>

        </div>

      </div>

    </form>

  </div>


</div>
