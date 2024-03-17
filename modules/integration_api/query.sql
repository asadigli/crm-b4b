DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_customer_account_cashbox_id_add_on_update`$$
CREATE TRIGGER `avh_cached_customer_account_cashbox_id_add_on_update` BEFORE UPDATE ON `avh_cached_customer_accounts`
FOR EACH ROW BEGIN
SET NEW.`cashbox_id` = (SELECT `id` FROM `avh_cached_cashboxes` WHERE `remote_id` = NEW.`remote_cashbox_id` LIMIT 1);
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_customer_account_cashbox_id_add_on_insert`$$
CREATE TRIGGER `avh_cached_customer_account_cashbox_id_add_on_insert` BEFORE INSERT ON `avh_cached_customer_accounts`
FOR EACH ROW BEGIN
SET NEW.`cashbox_id` = (SELECT `id` FROM `avh_cached_cashboxes` WHERE `remote_id` = NEW.`remote_cashbox_id` LIMIT 1);
END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_product_codes_product_id_on_insert`$$
CREATE TRIGGER `avh_cached_product_codes_product_id_on_insert` BEFORE INSERT ON `avh_cached_product_codes`
FOR EACH ROW BEGIN
SET NEW.`product_id` = (SELECT `id` FROM `avh_cached_products` WHERE `remote_id` = NEW.`remote_product_id` LIMIT 1);
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_product_codes_product_id_on_update`$$
CREATE TRIGGER `avh_cached_product_codes_product_id_on_update` BEFORE UPDATE ON `avh_cached_product_codes`
FOR EACH ROW BEGIN
SET NEW.`product_id` = (SELECT `id` FROM `avh_cached_products` WHERE `remote_id` = NEW.`remote_product_id` LIMIT 1);
END$$
DELIMITER ;

-- 21 FEV
DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_product_codes_product_id_on_insert`$$
CREATE TRIGGER `avh_cached_product_codes_product_id_on_insert` BEFORE INSERT ON `avh_cached_product_codes`
FOR EACH ROW BEGIN
SET NEW.`product_id` = (SELECT `id` FROM `avh_cached_products` WHERE `token` = NEW.`product_token` LIMIT 1);
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `avh_cached_product_codes_product_id_on_update`$$
CREATE TRIGGER `avh_cached_product_codes_product_id_on_update` BEFORE UPDATE ON `avh_cached_product_codes`
FOR EACH ROW BEGIN
SET NEW.`product_id` = (SELECT `id` FROM `avh_cached_products` WHERE `token` = NEW.`product_token` LIMIT 1);
END$$
DELIMITER ;
