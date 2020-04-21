ALTER TABLE `store_master` ADD `open_close_time` TEXT NOT NULL AFTER `description`;
ALTER TABLE `store_master` CHANGE `open_close_time` `open_time` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `store_master` ADD `close_time` TEXT NOT NULL AFTER `open_time`;
ALTER TABLE `store_master` ADD `password` VARCHAR(255) NOT NULL AFTER `email`;

ALTER TABLE `store_master` ADD `pickup_delivery` ENUM('pickup','delivery') NOT NULL AFTER `close_time`, ADD `delivery_charge` VARCHAR(100) NULL DEFAULT NULL AFTER `pickup_delivery`;
ALTER TABLE `category` ADD `store_id` INT NOT NULL AFTER `description`;

ALTER TABLE `users` CHANGE `user_type` `user_type` ENUM('admin','appraiser','lender','store') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `users` CHANGE `user_role` `user_role` ENUM('individual','company','manager','employee','store') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'individual';

ALTER TABLE `users` ADD `store_id` INT(11) NULL AFTER `phone_number`;