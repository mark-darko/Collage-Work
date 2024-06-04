<!DOCTYPE html>
<html lang="ru">

<head>
	<?= include('./html_separate/header.php') ?>
</head>

<body>
	<div id="colorlib-page">
		<?= $menu_html ?>

		<div id="colorlib-main">
			<section class="contact-section px-md-4 pt-5">
				<div class="container">
					<div class="row block-9">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 mb-1">
									<h3 class="h3">Пользователи</h3>
								</div>
							</div>
							<div class="row" style="overflow-x:auto;">
								<div class="col-md-12 mb-1">
									<table class="table table-striped" style="text-wrap: nowrap;">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Имя</th>
												<th scope="col">Фамилия</th>
												<th scope="col">Отчество</th>
												<th scope="col">Логин</th>

												<th scope="col">E-mail</th>
												<th scope="col">Временная блокировка</th>
												<th scope="col">Перманентная блокировка</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($users as $key => $user) : ?>
												<tr>
													<th scope="row"><?= $key+1 ?></th>
													<td><?= $user->name ?></td>
													<td><?= $user->surname ?></td>
													<td><?= $user->patronymic ?></td>
													<td><?= $user->login ?></td>
													<td><?= $user->email ?></td>
													<td>
														<?php if ($user->isBlocked && $user->endBlocking) : ?>
															<a href="<?= $response->getLink('/app/temp-block.php', ['user_id' => $user->id, 'action' => 'unblock']) ?>" class="btn btn-outline-warning px-4">⏳ Разблокировать</a>
														<?php else : ?>
															<a href="<?= $response->getLink('/app/temp-block.php', ['user_id' => $user->id]) ?>" class="btn btn-outline-warning px-4" >⏳ Блокировать</a>
														<?php endif; ?>
													</td>
													<td>
														<?php if ($user->isBlocked && !$user->endBlocking) : ?>
															<a href="<?= $response->getLink('/app/temp-block.php', ['user_id' => $user->id, 'action' => 'unblock']) ?>" class="btn btn-outline-danger px-4">📌 Разблокировать</a>
														<?php else : ?>
															<a href="<?= $response->getLink('/app/temp-block.php', ['user_id' => $user->id, 'action' => 'permanentBlock']) ?>" class="btn btn-outline-danger px-4">📌 Блокировать</a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
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