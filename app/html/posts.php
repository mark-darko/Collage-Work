<!DOCTYPE html>
<html lang="ru">

<head>
	<?= include('./html_separate/header.php') ?>
</head>
<body>

	<div id="colorlib-page">
		<?= $menu_html ?>
		
		<?php ?>
		<div id="colorlib-main">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container">
					<?php if ($user->isBlocked ) : ?>
						<div style="background-color: orange; text-align: center; border-radius: 10px; padding: 8px 0; margin: 0 40px;">Вы были заблокированы! <?= $user->endBlocking ? '<br>Дата разблокировки: ' . $user->formatDate($user->endBlocking) : 'Перманентно!' ?></div>
					<?php endif; ?>
					<div class="row d-flex">
						<div class="col-xl-8 col-md-8 py-5 px-md-2">
							<?php if ($user->id && !$user->isBlocked ) : ?>
								<div class="row">
									<div class="col-md-12 col-lg-12">
										<div>
											<a href="<?= $response->getLink('/app/post-action.php') ?>" class="btn btn-outline-success">📝 Создать пост</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<div class="row pt-md-4">
								<?php if (empty($posts)) : ?>
									<p style="font-size: 20px; padding: 0 20px; font-weight: 600;">Постов пока, что нет! Вы можете быть первым!</p>	
								<?php endif ?>

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
														<?php if ($user->avatar_url) : ?>
															<img src="/app/uploaded_files/<?= $user->avatar_url ?>" style="max-width: 30px; max-height: 30px; border-radius: 999px;">
														<?php endif ?>
														<span class="text text-3"><?= $post->author->name ?></span>
														<span><i class="icon-calendar mr-2"></i><?= $post->displayDate($post->created_at) ?></span>
														<span><i class="icon-comment2 mr-2"></i><?= $post->pluralize($post->comment_count, 'Комментарий', 'Комментария', 'Комментариев') ?></span>
													</p>
												</div>
												<p class="mb-4"><?= mb_strimwidth( $post->br2nl($post->content), 0, 50, "...") ?></p>
												<div class="d-flex pt-1  justify-content-between">
													<div>
													<a href="<?= $response->getLink('/app/post.php', ['id' => $post->id]) ?>" class="btn-custom">
														Подробнее... <span class="ion-ios-arrow-forward"></span></a>
													</div>
													
														<div>
															<?php if($post->author->id == $user->id && !$user->isBlocked) : ?>
																<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id]) ?>" class="text-warning" style="font-size: 1.8em;" title="Редактировать">🖍</a>
															<?php endif ?>

															<?php if(($user->isAdmin || ($post->comment_count == 0 && $post->author->id == $user->id)) && !$user->isBlocked) : ?>
																<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id, 'action' => 'delete']) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
															<?php endif ?>
														</div>
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