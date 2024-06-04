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
							<h2 class="h3"><?= $request->get('id') ? 'Редактирование поста' : 'Создание поста' ?></h2>
						</div>

					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">
							<form action="<?= $request->get('id') ? $response->getLink('/app/post-action.php', ['id' => $request->get('id')]) : $response->getLink('/app/post-action.php') ?>" method="POST" class="bg-light p-5 contact-form">
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($post->errors['title'])) : ?>is-invalid<?php endif ?>" value="<?= $post->title ?? '' ?>" placeholder="Название поста" name="title">

									<?php if(isset($user->errors["title"])) : ?>
										<?php foreach($user->errors["title"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<textarea id="" cols="30" rows="10" class="form-control <?php if(isset($post->errors['content'])) : ?>is-invalid<?php endif ?>" placeholder="Контент поста" name="content"><?= $post->br2nl($post->content) ?? '' ?></textarea> 

									<?php if(isset($user->errors["content"])) : ?>
										<?php foreach($user->errors["content"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								
								<div class="form-group">
									<input type="submit" value="<?= $request->get('id') ? 'Редактировать пост' : 'Создать пост' ?>" class="btn btn-primary py-3 px-5">
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

</body>

</html>