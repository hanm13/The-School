<?= View::render('header', $header_view); ?>

  <body class="text-center">


     <form action="" method="post" class = "form-signin">

       <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
       <input type="text" placeholder="Email" name="email" class="form-control">
       <input type="password" placeholder="Password" name="password" class="form-control">

       <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

     </form>

    <!--<p id="noAccountP"> If you do not have account please <a href ='registerform.php'> Register!</a></p>-->

  </body>

<?= View::render('footer'); ?>
