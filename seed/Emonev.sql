CREATE TABLE `user` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `user_permission` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `permissionId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `permission` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(30) UNIQUE NOT NULL,
  `description` varchar(255) NOT NULL,
  `moduleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `module` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `role` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `dinasId` bigint NOT NULL,
  `role` varchar(100) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `role_permission` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `permissionId` bigint NOT NULL,
  `roleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `role_user` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `roleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `user_dinas` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `dinasId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

CREATE TABLE `dinas` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `noHp` varchar(16) NOT NULL,
  `address` text NOT NULL,
  `logo` varchar(255),
  `domain` varchar(255) UNIQUE NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50),
  `deleteAt` varchar(50)
);

ALTER TABLE `user_permission` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `user_permission` ADD FOREIGN KEY (`permissionId`) REFERENCES `permission` (`id`);

ALTER TABLE `permission` ADD FOREIGN KEY (`moduleId`) REFERENCES `module` (`id`);

ALTER TABLE `role` ADD FOREIGN KEY (`dinasId`) REFERENCES `dinas` (`id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`permissionId`) REFERENCES `permission` (`id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`roleId`) REFERENCES `role` (`id`);

ALTER TABLE `role_user` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `role_user` ADD FOREIGN KEY (`roleId`) REFERENCES `role` (`id`);

ALTER TABLE `user_dinas` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `user_dinas` ADD FOREIGN KEY (`dinasId`) REFERENCES `dinas` (`id`);
