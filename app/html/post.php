<!DOCTYPE html>
<html lang="en">

<head>
	<?= include('./html_separate/header.php') ?>
</head>

<body>
	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		
		<?= $menu_html ?>

		<div id="colorlib-main">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 px-md-3 py-5">
							<div class="post">
								<h1 class="mb-3"><?= $post->title ?></h1>
								<div class="meta-wrap">
									<p class="meta">
										<!-- <img src='avatar.jpg' /> -->
										<span class="text text-3"><?= $post->author->name ?></span>
										<span><i class="icon-calendar mr-2"></i><?= $post->displayDate($post->created_at) ?></span>
										<span><i class="icon-comment2 mr-2"></i><?= $post->comment_count ?> Comment</span>
									</p>
								</div>

								<div>
									<?= $post->content ?>
								</div>
								
								<div>
									<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id]) ?>" class="text-warning" style="font-size: 1.8em;"
										title="Редактировать">🖍</a>
									<a href="" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
								</div>

							</div>
							<div class="comments pt-5 mt-5">
								<h3 class="mb-5 font-weight-bold"><?= $post->comment_count ?> комментариев</h3>
								<ul class="comment-list">
									<li class="comment">
										<div class="comment-body">
											<div class="d-flex justify-content-between">
												<h3>John Doe</h3>
												<a href="" class="text-danger" style="font-size: 1.8em;"
													title="Удалить">🗑</a>
											</div>
											<div class="meta">October 03, 2018 at 2:21pm</div>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
												Pariatur
												quidem laborum necessitatibus, ipsam impedit vitae autem, eum
												officia, fugiat saepe enim sapiente iste iure! Quam voluptas
												earum
												impedit necessitatibus, nihil?
											</p>
										</div>
									</li>
									<li class="comment">
										<div class="comment-body">
											<div class="d-flex justify-content-between">
												<h3>John Doe</h3>
												<a href="" class="text-danger" style="font-size: 1.8em;"
													title="Удалить">🗑</a>
											</div>
											<div class="meta">
												October 03, 2018 at 2:21pm
											</div>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
												Pariatur
												quidem laborum necessitatibus, ipsam impedit vitae autem, eum
												officia, fugiat saepe enim sapiente iste iure! Quam voluptas
												earum
												impedit necessitatibus, nihil?
											</p>
										</div>
									</li>
								</ul>
								<!-- END comment-list -->
								<div class="comment-form-wrap pt-5">
									<h3 class="mb-5">Оставьте комментарий</h3>
									<form action="#" class="p-3 p-md-5 bg-light">
										<div class="form-group">
											<label for="message">Сообщение</label>
											<textarea name="message" id="message" cols="30" rows="10"
												class="form-control"></textarea>
										</div>
										<div class="form-group">
											<input type="submit" value="Отправить" name="send_comment"
												class="btn py-3 px-4 btn-primary">
										</div>
									</form>
								</div>
							</div>
						</div>
					</div><!-- END-->
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<?= include('./html_separate/preloader.php') ?>

	<?= include('./html_separate/footer.php') ?>

</body>

</html>