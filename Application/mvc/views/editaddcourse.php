<div>

  <div class = "border">

    <div class="border-bottom">
      <h5 class="d-inline"> <?= (isset($course_obj['Name'])) ? "Edit Course : " . $course_obj['Name'] : "Add Course";?></h5>



    </div>

    <form action="?id=<?= $course_obj['id'] ?>" method="post">

      <? foreach ($form_errors as $key => $value):?>
        <?php createErrorMessage($value, "info"); ?>
      <?php endforeach ?>

      <div>
        <input type="submit" class="btn btn-primary" name="action" value="Save">
        <?php if (isset($course_obj['id'])): ?>

          <?php if ($course_students_amt == 0 ):?>

            <input type='submit' class='btn btn-primary' data-toggle='confirmation' name='action' value='Delete'>

          <?php endif; ?>

        <?php endif; ?>

      </div>

      <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Name</label>
        <div class="col-10">
          <input name="editadd_name" class="form-control" type="text" value="<?= $course_obj["Name"] ?>" id="example-text-input">
        </div>
      </div>
      <div class="form-group row">
        <label for="example-email-input" class="col-2 col-form-label">Description</label>
        <div class="col-10">
          <textarea class="form-control" id="editadd_description" name="editadd_description" rows="5"><?= $course_obj["Description"] ?></textarea>
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
          <img src="<?= $course_obj["Image"] ?>" class="imagePreview pull-left" width="50" height="50">
        </div>

      </div>

    </form>

    <div class="col-10">
      <?= (isset($course_obj['id'])) ? "<center><h4> Total " . $course_students_amt . " students taking this course.</h4></center>" : "" ; ?>
    </div>

  </div>


</div>
