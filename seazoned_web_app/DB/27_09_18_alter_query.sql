UPDATE `faq` SET `answers` = '<p>\r\n\r\n\r\n \r\n \r\n \r\n \r\n </p><p>To\r\nsign up, please download the Seazoned app from the <u>iTunes\r\nApp Store</u>&nbsp;or <u>Google\r\nPlay Store</u>,\r\nand enter your information into the app. Or register through our\r\nwebsite via <a href=\"http://www.seazoned.com\" rel=\"nofollow\" target=\"_blank\">www.seazoned.com</a></p>\r\n\r\n<br><p></p>' WHERE `faq`.`id` = 1 


UPDATE `faq` SET `answers` = '<p>\r\n\r\n\r\n \r\n \r\n \r\n \r\n </p><p>To\r\nsign up, please download the Seazoned app from the iTunes\r\nApp Store&nbsp;or Google\r\nPlay Store,\r\nand enter your information into the app. Or register through our\r\nwebsite via <a href=\"http://www.seazoned.com\" rel=\"nofollow\" target=\"_blank\">www.seazoned.com</a></p>\r\n\r\n<br><p></p>' WHERE `faq`.`id` = 1 



ALTER TABLE `book_services` ADD `service_booked_price` INT(100) NOT NULL AFTER `additional_note`;