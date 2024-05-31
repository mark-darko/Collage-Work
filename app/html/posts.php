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
						<div class="col-xl-8 col-md-8 py-5 px-md-2">
							<?php if (isset($user->id)) : ?>
								<div class="row">
									<div class="col-md-12 col-lg-12">
										<div>
											<a href="<?= $response->getLink('/app/post-action.php') ?>" class="btn btn-outline-success">📝 Создать пост</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<div class="row pt-md-4">
								<?php foreach( $posts as $post ) : ?>
									<!-- один пост/превью -->
									<div class="col-md-12 col-xl-12">
										<div class="blog-entry ftco-animate d-md-flex">
											<!-- 
												изображение для поста 
												<a href="single.html" class="img img-2"
												style="background-image: url(images/image_1.jpg);"></a> 
											-->
											<div class="text text-2 pl-md-4">
												<h3 class="mb-2"><a href="single.html"><?= $post->title ?></a></h3>
												<div class="meta-wrap">
													<p class="meta">
														<!-- <img src='avatar.jpg' /> -->
														<span class="text text-3"><?= $post->author->name ?></span>
														<span><i class="icon-calendar mr-2"></i><?= $post->displayDate($post->created_at) ?></span>
														<span><i class="icon-comment2 mr-2"></i><?= $post->comment_count ?> Comment</span>
													</p>
												</div>
												<p class="mb-4"><?= mb_strimwidth( $post->br2nl($post->content), 0, 15, "...") ?></p>
												<div class="d-flex pt-1  justify-content-between">
													<div>
													<a href="<?= $response->getLink('/app/post.php', ['id' => $post->id]) ?>" class="btn-custom">
														Подробнее... <span class="ion-ios-arrow-forward"></span></a>
													</div>
													<?php if($post->author->id == $user->id) : ?>
														<div>
															<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id]) ?>" class="text-warning" style="font-size: 1.8em;" title="Редактировать">🖍</a>
															<a href="" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
														</div>
													<?php endif ?>
												</div>
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