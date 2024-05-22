<!DOCTYPE html>
<html lang="en">

<head>
	<?= include('./html/header.php') ?>
</head>

<body>
	<div id="colorlib-page">
		<?= $menu_html ?>
		
		<div id="colorlib-main">
			<section class="contact-section px-md-2  pt-5">
				<div class="container">
					<div class="row d-flex contact-info">
						<div class="col-md-12 mb-1">
							<h2 class="h3">Авторизация</h2>
						</div>
					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">
							<form action="/app/login.php" method="POST" class="bg-light p-5 contact-form">
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($user->errors['login'])) : ?>is-invalid<?php endif ?>" value="<?= $user->login ?? '' ?>" placeholder="Your Login"
										name="login">

									<?php if(isset($user->errors["login"])) : ?>
										<?php foreach($user->errors["login"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="password" class="form-control <?php if(isset($user->errors['login'])) : ?>is-invalid<?php endif ?>" value="<?= $user->password ?? '' ?>" placeholder="Password"
										name="password">
								</div>
								<div class="form-group">
									<input type="submit" value="Вход" class="btn btn-primary py-3 px-5">
								</div>
							</form>
						</div>
					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<?= include('./html/preloader.php') ?>

	<?= include('./html/footer.php') ?>

</body>

</html>