<!DOCTYPE html>
<html lang="ru">

<head>
	<?= include('./html_separate/header.php') ?>
</head>

<body>
	<div id="colorlib-page">
		<?= $menu_html ?>

		<div id="colorlib-main">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container">
					<?php if ($user->isBlocked ) : ?>
						<div style="background-color: orange; text-align: center; border-radius: 10px; padding: 8px 0; margin: 0 40px;">Вы были заблокированы! <?= $user->endBlocking ? '<br>Дата разблокировки: ' . $user->formatDate($user->endBlocking) : 'Перманентно!' ?></div>
					<?php endif; ?>
					<div class="row">
						<div class="col-lg-12 px-md-3 py-5">
							<div class="post">
								<h1 class="mb-3"><?= $post->title ?></h1>
								<div class="meta-wrap">
									<p class="meta">
										<?php if ($post->author->avatar_url) : ?>
											<img src="/app/uploaded_files/<?= $post->author->avatar_url ?>" style="max-width: 30px; max-height: 30px; border-radius: 999px;">
										<?php endif ?>
										<span class="text text-3"><?= $post->author->name ?></span>
										<span><i class="icon-calendar mr-2"></i><?= $post->displayDate($post->created_at) ?></span>
										<span><i class="icon-comment2 mr-2"></i><?= $post->pluralize($post->comment_count, 'Комментарий', 'Комментария', 'Комментариев') ?></span>
									</p>
								</div>

								<div>
									<?= $post->content ?>
								</div>
								<div>
									<?php if ($user->id == $post->author->id && !$user->isBlocked) : ?>
										<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id]) ?>" class="text-warning" style="font-size: 1.8em;"
											title="Редактировать">🖍</a>
									<?php endif; ?>

									<?php if (($user->isAdmin || ($post->comment_count == 0 && $post->author->id == $user->id)) && !$user->isBlocked) : ?>
										<a href="<?= $response->getLink('/app/post-action.php', ["id" => $post->id, 'action' => 'delete']) ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
									<?php endif; ?>
								</div>
							</div>
							<div class="comments mt-5">
								<h3 class="mb-5 font-weight-bold"><?= $post->pluralize($post->comment_count, 'Комментарий', 'Комментария', 'Комментариев') ?></h3>
								<ul class="comment-list">
									<?php foreach($comments as $comment) : ?>
										<li id="<?= $comment->id ?>" class="comment">
											<div class="comment-body">
												<div class="d-flex justify-content-between">
													<h3><?= $comment->author->name . ' ' . $comment->author->surname ?></h3>
													<?php if($user->isAdmin && !$user->isBlocked) : ?>
														<a href="<?= $response->getLink('/app/post.php', ['id' => $post->id, "comment_id" => $comment->id, 'action' => 'delete']) ?>" class="text-danger" style="font-size: 1.8em;"
															title="Удалить">🗑</a>
													<?php endif; ?>
												</div>
												<div class="meta"><?= $comment->formatDate($comment->created_at) ?></div>
												<p>
													<?= $comment->answer_comment ? '<a href="#' . $comment->answer_comment->id . '"><b>Ответ на комментарий: ' . mb_strimwidth( $post->br2nl($comment->answer_comment->content), 0, 15, "...") . '</b></a><br>' : '' ?><?= $comment->content ?>
												</p>
												<?php if ($post->author->id == $user->id && $comment->author->id !== $user->id) : ?>
													<a href="<?= $response->getLink('/app/post.php', ["id" => $post->id, 'answer_id' => $comment->id]) ?>">Ответить</a>
												<?php endif ?>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
								<!-- END comment-list -->
								<?php if ($user->id && !$user->isBlocked && ($post->author->id !== $user->id || $request->get('answer_id'))) : ?>
									<div class="comment-form-wrap">
										<h3 class="mb-5"><?= $request->get('answer_id') ? 'Ответ на комментарий: ' : 'Оставьте комментарий' ?></h3>
										<form action="<?= $request->get('answer_id') ? $response->getLink('/app/post.php', ["id" => $post->id, 'answer_id' => $request->get('answer_id')]) : $response->getLink('/app/post.php', ["id" => $post->id]) ?>" method="POST" class="p-3 p-md-5 bg-light">
											<div class="form-group">
												<label for="message">Сообщение</label>
												<textarea name="content" id="message" cols="30" rows="10"
													class="form-control"></textarea>
											</div>
											<div class="form-group">
												<input type="submit" value="Отправить" name="send_comment"
													class="btn py-3 px-4 btn-primary">
											</div>
										</form>
									</div>
								<?php endif ?>
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