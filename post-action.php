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

							<form action="#" class="bg-light p-5 contact-form">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Post title" name="title">

								</div>
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Post preview" name="preview">
								</div>
								<div class="form-group">
									<textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="Post content"
										name="content"></textarea> 
								</div>
								
								<div class="form-group">
									<input type="submit" value="Регистрация" class="btn btn-primary py-3 px-5">
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