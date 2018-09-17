	<head>

		<?= "<title>" . $header_info['Title'] . "</title>" ?>

		<meta charset="UTF-8">
		<meta name="description" content="<?= $header_info['Description'] ?>">
		<meta name="robots" content="noindex">
		<meta name="googlebot" content="noindex">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="/css/style.css">

	</head>

	<header>

		<?php if(is_logged_in() ):?>

		<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <a class="navbar-brand" href="#"><img id="websiteLogo" src= "/images/logo.png"></img></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/theschool/home">School <span class="sr-only">(current)</span></a>
          </li>

					<?php if( $user_info['Role'] == ROLE_MANAGER || $user_info['Role'] == ROLE_OWNER): ?>

	          <li class="nav-item active">
	            <a class="nav-link" href="/theschool/admin">Administration</a>
	          </li>

					<?php endif; ?>

        </ul>
        <p id="user_info_p" class="text-light"><?= $user_info["Name"] ?>, <?= $user_role ?>, <a href="/theschool/logout">Logout</a> </p>
      </div>
    </nav>

	<?php endif; ?>

	</header>
