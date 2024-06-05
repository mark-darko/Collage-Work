CREATE TABLE IF NOT EXISTS `roles` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `users` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`name` varchar(255) NOT NULL,
	`surname` varchar(255) NOT NULL,
	`patronymic` varchar(255) NOT NULL,
	`login` varchar(255) NOT NULL UNIQUE,
	`email` varchar(255) NOT NULL UNIQUE,
	`password` varchar(255) NOT NULL,
	`token` varchar(255) NOT NULL UNIQUE,
	`avatar_url` varchar(255) NOT NULL,
	`role_id` int NOT NULL DEFAULT '2',
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `posts` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`title` varchar(255) NOT NULL,
	`content` text NOT NULL,
	`user_id` int NOT NULL,
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `comments` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`user_id` int NOT NULL,
	`post_id` int NOT NULL,
	`content` text NOT NULL,
	`answer_id` int NOT NULL,
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `blocked_users` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`user_id` int NOT NULL,
	`end_blocking` timestamp,
	PRIMARY KEY (`id`)
);


ALTER TABLE `users` ADD CONSTRAINT `users_fk9` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`);
ALTER TABLE `posts` ADD CONSTRAINT `posts_fk3` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);
ALTER TABLE `comments` ADD CONSTRAINT `comments_fk1` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk2` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk4` FOREIGN KEY (`answer_id`) REFERENCES `comments`(`id`);
ALTER TABLE `blocked_users` ADD CONSTRAINT `blocked_users_fk1` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);