<!DOCTYPE html>
<html lang="en">

<head>
	<?= include('./html/header.php') ?>
</head>

<body>
	<div id="colorlib-page">
		<?= $menu_html ?>

		<div id="colorlib-main">
			<section class="contact-section px-md-2 pt-5">
				<div class="container">
					<div class="row d-flex contact-info">
						<div class="col-md-12 mb-1">
							<h2 class="h3">Создание поста</h2>
						</div>

					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">

							<form action="<?= $response->getLink('/app/post-action.php') ?>" method="POST" class="bg-light p-5 contact-form">
								<input type="text" style="display: none;" value="<?= $post->id ?? '' ?>" name="id">

								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($post->errors['title'])) : ?>is-invalid<?php endif ?>" value="<?= $post->title ?? '' ?>" placeholder="Post title" name="title">

									<?php if(isset($user->errors["title"])) : ?>
										<?php foreach($user->errors["title"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<input type="text" class="form-control <?php if(isset($post->errors['preview_text'])) : ?>is-invalid<?php endif ?>" value="<?= $post->preview_text ?? '' ?>" placeholder="Post preview" name="preview_text">

									<?php if(isset($user->errors["preview_text"])) : ?>
										<?php foreach($user->errors["preview_text"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div class="form-group">
									<textarea id="" cols="30" rows="10" class="form-control <?php if(isset($post->errors['content'])) : ?>is-invalid<?php endif ?>" placeholder="Post content" name="content"><?= $post->br2nl($post->content) ?? '' ?></textarea> 

									<?php if(isset($user->errors["content"])) : ?>
										<?php foreach($user->errors["content"] as $error) : ?>
											<div class="invalid-feedback">
												<?= $error ?>
											</div>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								
								<div class="form-group">
									<input type="submit" value="Создать пост" class="btn btn-primary py-3 px-5">
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