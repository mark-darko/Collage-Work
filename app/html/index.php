<!DOCTYPE html>
<html lang="en">

<head>
	<?= include('./html_separate/header.php') ?>
</head>

<body>

	<div id="colorlib-page">
		<?= $menu_html ?>
		
		<div id="colorlib-main">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container">
					<div class="row d-flex">
						<div class="col-xl-8 py-5 px-md-2">
							<div class="row pt-md-4">
								<!-- один пост/превью -->
								<?php foreach( $posts as $post ) : ?>
									<div class="col-md-12">
										<div class="blog-entry ftco-animate d-md-flex">
											<div class="text text-2 pl-md-4">
												<h3 class="mb-2"><a href="single.html"><?= $post->title ?></a></h3>
												<div class="meta-wrap">
													<p class="meta">
														<span class="text text-3"><?= $post->author->name ?></span>
														<span><i class="icon-calendar mr-2"></i><?= $post->displayDate($post->created_at) ?></span>
														<span><i class="icon-comment2 mr-2"></i><?= $post->comment_count ?> Comment</span>
													</p>
												</div>
												<p class="mb-4"><?= mb_strimwidth( $post->br2nl($post->content), 0, 15, "...") ?></p>
												<p><a href="<?= $response->getLink('/app/post.php', ['id' => $post->id]) ?>" class="btn-custom">Подробнее... <span
															class="ion-ios-arrow-forward"></span></a></p>
											</div>
										</div>
									</div>
								<?php endforeach ?>

							</div><!-- END-->

							<!-- 
								pagination
								<div class="row">
								<div class="col">
									<div class="block-27">
										<ul>
											<li><a href="#">&lt;</a></li>
											<li class="active"><span>1</span></li>
											<li><a href="#">2</a></li>
											<li><a href="#">3</a></li>
											<li><a href="#">4</a></li>
											<li><a href="#">5</a></li>
											<li><a href="#">&gt;</a></li>
										</ul>
									</div>
								</div>
							</div> -->

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