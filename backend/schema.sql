CREATE DATABASE IF NOT EXISTS `old_coffee_st` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `old_coffee_st`;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `thread_messages`;
DROP TABLE IF EXISTS `inquiry_threads`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;
-- users
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_users_email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- products
CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(50) NOT NULL,
  `name` VARCHAR(160) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `image_url` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_products_category` (`category`),
  INDEX `idx_products_is_active` (`is_active`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- product_variants (add-ons/options per product)
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `group_name` VARCHAR(120) NOT NULL,
  -- e.g., Size, Milk, Toppings
  `name` VARCHAR(120) NOT NULL,
  -- e.g., Large, Oat Milk
  `price_delta` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_variants_product` (`product_id`),
  CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- carts (per-user)
CREATE TABLE IF NOT EXISTS `carts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('active', 'converted', 'abandoned') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_carts_user_status` (`user_id`, `status`),
  CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- orders (snapshot totals)
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('pending', 'paid', 'cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `delivery_fee` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(6, 4) NOT NULL DEFAULT 0.0800,
  `tax_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `tax` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_orders_user_status` (`user_id`, `status`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- cart_items (references carts, products) + variant snapshot columns
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `variant_id` BIGINT UNSIGNED NULL,
  `variant_name` VARCHAR(255) NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10, 2) NOT NULL,
  `price_delta` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_cart_product` (`cart_id`, `product_id`),
  CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- order_items (references orders, products) + variant snapshot columns
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `variant_id` BIGINT UNSIGNED NULL,
  `variant_name` VARCHAR(255) NULL,
  `price_delta` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `product_name` VARCHAR(255) NOT NULL,
  `unit_price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `line_total` DECIMAL(10, 2) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order` (`order_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- inquiry_threads (threaded inquiries replacing legacy `inquiries` table)
CREATE TABLE IF NOT EXISTS `inquiry_threads` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `guest_email` VARCHAR(190) NULL,
  `guest_name` VARCHAR(190) NULL,
  `subject` VARCHAR(255) NOT NULL,
  `status` ENUM('pending', 'responded', 'done') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_message_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_last_viewed_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_threads_user_subject` (`user_id`, `subject`),
  INDEX `idx_threads_guest_subject` (`guest_email`, `subject`),
  INDEX `idx_threads_status_last_message` (`status`, `last_message_at`),
  CONSTRAINT `fk_threads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
  SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- thread_messages (messages per thread)
CREATE TABLE IF NOT EXISTS `thread_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thread_id` BIGINT UNSIGNED NOT NULL,
  `sender_type` ENUM('user', 'guest', 'admin') NOT NULL,
  `sender_id` BIGINT UNSIGNED NULL,
  `sender_name` VARCHAR(190) NULL,
  `sender_email` VARCHAR(190) NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_thread_messages_thread_created` (`thread_id`, `created_at`),
  CONSTRAINT `fk_thread_messages_thread` FOREIGN KEY (`thread_id`) REFERENCES `inquiry_threads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_thread_messages_user` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE
  SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
INSERT INTO `products`
VALUES (
    1,
    'hot-coffee',
    'Americano',
    'A cool blend of espresso and chilled water, served over ice for a crisp and light finish.',
    95.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761317233/products/americano-68fb916de1765.png',
    1,
    '2025-10-24 16:47:14',
    '2025-10-25 13:28:55'
  ),
  (
    2,
    'pastries',
    'Cinnamon Roll',
    'Warm, soft roll swirled with cinnamon sugar and topped with rich cream cheese frosting.',
    80.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761317649/products/cinammon-68fb930c670dc.png',
    1,
    '2025-10-24 16:54:10',
    '2025-10-24 17:25:43'
  ),
  (
    3,
    'iced-coffee',
    'Cappuccino',
    'Freshly brewed espresso balanced with steamed milk and a velvety foam finish.',
    110.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761326259/products/CAPUCCINO-68fbb4b1e30b3.png',
    1,
    '2025-10-24 19:17:40',
    '2025-10-25 13:28:55'
  ),
  (
    4,
    'non-coffee',
    'Raspeberry Tea',
    'No description yet for this product',
    65.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761371183/products/Raspberry_tea-68fc6410c28b7.png',
    1,
    '2025-10-25 07:46:24',
    '2025-10-25 07:46:24'
  ),
  (
    5,
    'frappe',
    'Blueberry Frappe',
    'No description yet for this product',
    89.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761371469/products/blueberry_frappe-5.png',
    1,
    '2025-10-25 07:46:41',
    '2025-10-25 07:51:11'
  ),
  (
    6,
    'frappe',
    'Choco Chip',
    'No description yet for this product',
    999.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761371319/products/CHOCOCHIP-6.png',
    1,
    '2025-10-25 07:46:52',
    '2025-10-25 08:08:58'
  ),
  (
    7,
    'frappe',
    'Salted Caramel',
    'No description yet for this product',
    999.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761372526/products/Salted_caramel-68fc696c94b25.png',
    1,
    '2025-10-25 08:08:47',
    '2025-10-25 08:08:47'
  ),
  (
    8,
    'pastries',
    'Cookie',
    'No description yet for this product.',
    9999.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761372699/products/cookies-68fc6a19aea84.png',
    1,
    '2025-10-25 08:11:41',
    '2025-10-25 08:11:41'
  ),
  (
    9,
    'pastries',
    'Red Velvet Cookie',
    'No description yet for this product.',
    1.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761373153/products/RED_VELVE_COOKIES-68fc6bdec141c.png',
    1,
    '2025-10-25 08:19:14',
    '2025-10-25 08:19:47'
  ),
  (
    10,
    'pastries',
    'White Chocolate',
    'No description yet for this product',
    10999.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761373257/products/white_chocolate-68fc6c47bbb70.png',
    1,
    '2025-10-25 08:20:59',
    '2025-10-25 08:20:59'
  ),
  (
    11,
    'frappe',
    'Ube Frappe',
    'No description yet for this product.',
    95.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761373632/products/UBE%20FRAPPE-68fc6dbe9ab9a.png',
    1,
    '2025-10-25 08:27:13',
    '2025-10-25 08:27:13'
  ),
  (
    12,
    'frappe',
    'Strawberry Frappe',
    'No description yet for this product.',
    50000.65,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761373963/products/STRAWBEERY_FRAPPE-68fc6f0a39048.png',
    1,
    '2025-10-25 08:32:45',
    '2025-10-25 08:32:45'
  ),
  (
    13,
    'frappe',
    'Vanilla Latte',
    'No description yet for this product.',
    89.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761374132/products/VANILLA%20LATTE-68fc6f836ce0a.png',
    1,
    '2025-10-25 08:35:34',
    '2025-10-25 08:35:34'
  ),
  (
    14,
    'hot-coffee',
    'White Mocha',
    'No description for this product yet.',
    69.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761390498/products/white_mocha-68fcaf9fbd61c.png',
    1,
    '2025-10-25 13:08:20',
    '2025-10-25 13:08:20'
  ),
  (
    15,
    'non-coffee',
    'Lemon Tea',
    'No description for this product yet.',
    79.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761390642/products/lemon_tea-15.png',
    1,
    '2025-10-25 13:10:15',
    '2025-10-25 19:12:47'
  ),
  (
    16,
    'iced-coffee',
    'Matcha',
    'No description for this product yet.',
    1.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391021/products/Matcha-68fcb1aa6c31c.png',
    1,
    '2025-10-25 13:17:02',
    '2025-10-25 13:17:02'
  ),
  (
    17,
    'iced-coffee',
    'Noir Mocha',
    'No description for this product yet.',
    95.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391081/products/Noir_Mocha-68fcb1e57225f.png',
    1,
    '2025-10-25 13:18:03',
    '2025-10-25 13:18:03'
  ),
  (
    18,
    'iced-coffee',
    'Spanish Latte',
    'No description for this product yet.',
    95.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391130/products/spanish_latte-68fcb216757c3.png',
    1,
    '2025-10-25 13:18:52',
    '2025-10-25 13:18:52'
  ),
  (
    19,
    'iced-coffee',
    'Strawberry Matcha',
    'No description for this product yet.',
    2.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391166/products/starwberry_matcha-68fcb23c1d113.png',
    1,
    '2025-10-25 13:19:28',
    '2025-10-25 13:19:28'
  ),
  (
    20,
    'iced-coffee',
    'Macchiato',
    'No description for this product yet.',
    95.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391229/products/macchiato-68fcb274842b9.png',
    1,
    '2025-10-25 13:20:31',
    '2025-10-25 13:20:31'
  ),
  (
    21,
    'iced-coffee',
    'Cheesecake',
    'No description for this product yet.',
    89.00,
    'https://res.cloudinary.com/dsfcry9re/image/upload/v1761391266/products/cheesecake-68fcb2939bc35.png',
    1,
    '2025-10-25 13:21:10',
    '2025-10-25 13:21:10'
  );
SET FOREIGN_KEY_CHECKS = 1;