<!DOCTYPE html>
<html lang="ru">

<head>
	<?= include('./html_separate/header.php') ?>
</head>

<body>

	<div id="colorlib-page">
		<?= $menu_html ?>
		
		<div id="colorlib-main">
			<section class="contact-section px-md-2 pt-5">
				<div class="container">
					<div class="row d-flex contact-info">
						<div class="col-md-12 mb-1">
							<h2 class="h3">Регистрация</h2>
						</div>

					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">

							<form method="POST" action="/app/register.php" enctype="multipart/form-data" class="bg-light p-5 contact-form">
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($user->validate_name_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->name ?? '' ?>" placeholder="Ваше имя" name="name">

									<?php if(isset($user->validate_name_error)) : ?>
										<?php foreach($user->validate_name_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($user->validate_surname_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->surname ?? '' ?>" placeholder="Ваша фамилия" name="surname">

									<?php if(isset($user->validate_surname_error)) : ?>
										<?php foreach($user->validate_surname_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($user->validate_patronymic_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->patronymic ?? '' ?>" placeholder="Ваше отчество"
										name="patronymic">

									<?php if(isset($user->validate_patronymic_error)) : ?>
										<?php foreach($user->validate_patronymic_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($user->validate_login_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->login ?? '' ?>" placeholder="Ваш логин"
										name="login">

									<?php if(isset($user->validate_login_error)) : ?>
										<?php foreach($user->validate_login_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="email" class="form-control <?php if(isset($user->validate_email_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->email ?? '' ?>" placeholder="Ваш email"
										name="email">

									<?php if(isset($user->validate_email_error)) : ?>
										<?php foreach($user->validate_email_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="password" class="form-control <?php if(isset($user->validate_password_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->password ?? '' ?>" placeholder="Пароль"
										name="password">

									<?php if(isset($user->validate_password_error)) : ?>
										<?php foreach($user->validate_password_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="password" class="form-control <?php if(isset($user->validate_confirmPassword_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->confirmPassword ?? '' ?>" placeholder="Повтор пароля"
										name="confirmPassword">

									<?php if(isset($user->validate_confirmPassword_error)) : ?>
										<?php foreach($user->validate_confirmPassword_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>

								<div class="form-group">
									<input type="file" class="form-control <?php if(isset($user->validate_avatar_url_error)) : ?>is-invalid<?php endif ?>" value="<?= $user->avatar_url ?? '' ?>"
										name="avatar_url" accept=".jpg, .jpeg, .png">

									<?php if(isset($user->validate_avatar_url_error)) : ?>
										<?php foreach($user->validate_avatar_url_error as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>

								<div class="form-group">
									<div class="form-check">
										<input class="form-check-input is-invalid" type="checkbox" value=""
											id="rules" aria-describedby="invalidCheck3Feedback" required>
										<label class="form-check-label" for="rules">
											Согласен с правилами использования
										</label>
										<div id="rulesFeedback" class="invalid-feedback">
											Необходимо согласиться с правилами регистрации.
										</div>
									</div>
								</div>
								<div class="form-group">
									<input type="submit" value="Регистрация" class="btn btn-primary py-3 px-5" style="width: 100%;">
								</div>
							</form>

						</div>
					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<?= include('./html_separate/preloader.php') ?>

	<?= include('./html_separate/footer.php') ?>
	
	<script>
		$(document).ready(function(){
			$('#rules').click(function(e){
				$(this).toggleClass('is-invalid');
				$(this).toggleClass('is-valid');
				$('#rulesFeedback').toggleClass('d-none');				
			})
		})

	</script>

</body>

</html>