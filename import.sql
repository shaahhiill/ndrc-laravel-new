SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` enum('pending','picked_up','in_transit','delivered','failed') DEFAULT 'pending',
  `estimated_delivery` datetime DEFAULT NULL,
  `actual_delivery` datetime DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `driver_id` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `vehicle_type` enum('bike','van','truck') DEFAULT 'van',
  `status` enum('idle','active','offline') DEFAULT 'offline',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `distributor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distributor_id` (`distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `factory_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `distributor_id` int(11) NOT NULL,
  `status` enum('pending','accepted','dispatched','delivered','rejected') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_dist_factory` (`distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `category` enum('Dairy','Beverages','Noodles','Confectionery','Culinary') NOT NULL,
  `unit` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_category` (`category`),
  KEY `idx_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`id`, `name`, `sku`, `category`, `unit`, `description`, `image_url`, `price`) VALUES
(1, 'MILO 1kg Refill Pack', 'MILO-1KG-D', 'Beverages', '1kg', 'MILO chocolate', NULL, 720.00),
(2, 'NESCAFE Classic 500g', 'NESCAFE-500G-D', 'Beverages', '500g', 'Instant coffee', NULL, 1050.00),
(3, 'Milo Ready-to-Drink', 'MILO-RTD-C24', 'Beverages', 'Case', '24 packs', NULL, 1800.00),
(4, 'MAGGI Noodles', 'MAGGI-40PK', 'Noodles', 'Box', 'Bulk box of 40', NULL, 1600.00),
(5, 'MAGGI Curry Noodles', 'MAGGI-8PK-V', 'Noodles', '8-pack', 'Value pack of 8', NULL, 350.00),
(6, 'Milkmaid', 'MILKMAID-C12', 'Dairy', 'Case', '12 cans', NULL, 4200.00),
(7, 'Everyday Milk Powder', 'EVERYDAY-1KG', 'Dairy', '1kg', 'Full cream milk', NULL, 1450.00),
(8, 'Anchor Milk Powder', 'ANCHOR-400G-B', 'Dairy', '400g', '400g pack', NULL, 580.00),
(9, 'KitKat 4-Finger', 'KITKAT-BOX24', 'Confectionery', 'Box', 'Display box', NULL, 1100.00),
(10, 'Milkybar', 'MILKYBAR-P12', 'Confectionery', '12-pack', 'White chocolate', NULL, 480.00),
(11, 'Maggi Coconut Milk', 'MAGGI-CMP-1KG', 'Culinary', '1kg', '1kg bulk', NULL, 1250.00),
(12, 'Maggi Seasoning', 'MAGGI-SEASON-200', 'Culinary', 'Bottle', '200ml bottle', NULL, 280.00);

CREATE TABLE IF NOT EXISTS `factory_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factory_order_id` (`factory_order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('retailer','wholesaler','distributor','nestle') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `territory` varchar(100) DEFAULT NULL,
  `wholesaler_id` int(11) DEFAULT NULL,
  `distributor_id` int(11) DEFAULT NULL,
  `order_direct` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_wholesaler` (`wholesaler_id`),
  KEY `idx_distributor_aff` (`distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `region`, `territory`, `wholesaler_id`, `distributor_id`, `order_direct`, `status`) VALUES
(1, 'Nestle Admin', 'admin@nestle.lk', '$2y$10$bGrPvw8/NQ855l82NCC6JuFw6OO5GYpiA67Dmcgxm/HvIEfGf9pri', 'nestle', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'active'),
(7, 'udakara Distributors ', 'udakara@gmail.com', '$2y$10$Vmd2QFeW2aKe6rIRxfe60O4gnClRppCSFcYlXCIifra2tanPxUVY6', 'distributor', '0773344555', NULL, NULL, 'Western Province', NULL, NULL, 0, 'active'),
(8, 'Sunshine traders ', 'sunshine@gmail.com', '$2y$10$F5zMGCN6CpScqstwgErdtete74wNbZGWFyIASI4GhvKYcPiTas8Me', 'retailer', '0773344555', 'No. 24, Galle Road, Colombo 03, Sri Lanka', NULL, NULL, NULL, 7, 1, 'active'),
(9, 'Bernards mart ', 'bernmart@gmail.com', '$2y$10$nu/mhYJT8BOLhiE8zvsVQebc.xFSlOjEBqrm5Lw3TBksbogLukmT2', 'retailer', '0773344555', '458, Kandy Road, Kiribathgoda, Sri Lanka', NULL, NULL, NULL, 7, 1, 'active'),
(10, 'Glomark ', 'glomark@gmail.com', '$2y$10$p9hsbh/cd2mVN6CwXBCRb.pj9JBk.AFNU891CwZwFPUinkY3TU0IO', 'retailer', '0778899243', '132, Galle road, dehiwala ', NULL, NULL, NULL, 7, 1, 'active'),
(11, 'mitsis delicacies ', 'mitsis@gmail.com', '$2y$10$IOTwsxcgoRJ3R6jdwoivguOo.Ed8KWyh.z2hWRwRKB4rxqCXSCPLK', 'wholesaler', '0776622330', '123, Galle road, Dehiwala ', NULL, NULL, NULL, 7, 0, 'active'),
(12, 'galvins store ', 'galvin@gmail.com', '$2y$10$8WIEOcbDv9I3WFr4TO/6o.M4mQjl820nU2fFEhHWyfLf.75k7CEqi', 'wholesaler', '0772233440', '123/2, Galle road, dehiwala ', NULL, NULL, NULL, 7, 0, 'active');

CREATE TABLE IF NOT EXISTS `network_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retailer_id` int(11) NOT NULL,
  `wholesaler_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_retailer_req` (`retailer_id`),
  KEY `idx_wholesaler_req` (`wholesaler_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('order_status','stock_alert','system') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_unread` (`user_id`,`read_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `wholesaler_id` int(11) DEFAULT NULL,
  `distributor_id` int(11) NOT NULL,
  `status` enum('placed','wholesaler_pending','wholesaler_accepted','distributor_pending','distributor_confirmed','dispatched','delivered','rejected') NOT NULL,
  `order_date` date NOT NULL,
  `scheduled_dispatch_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `retailer_id` (`retailer_id`),
  KEY `wholesaler_id` (`wholesaler_id`),
  KEY `idx_status` (`status`),
  KEY `idx_order_date` (`order_date`),
  KEY `idx_distributor` (`distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` (`id`, `order_number`, `retailer_id`, `wholesaler_id`, `distributor_id`, `status`, `order_date`, `scheduled_dispatch_date`, `total_amount`) VALUES
(1, 'ORD-69C6D4006767A', 8, NULL, 7, 'distributor_confirmed', '2026-03-27', '2026-03-30', 300000.00),
(2, 'ORD-69C6D4282698D', 8, NULL, 7, 'distributor_confirmed', '2026-03-27', '2026-03-30', 56900.00),
(3, 'ORD-69C6D7EC183B9', 9, NULL, 7, 'distributor_confirmed', '2026-03-27', '2026-03-30', 660200.00),
(4, 'ORD-69C6D9820D241', 10, NULL, 7, 'distributor_confirmed', '2026-03-27', '2026-03-30', 452100.00),
(5, 'ORD-20260327-E8BFC8', 8, NULL, 7, 'distributor_confirmed', '2026-03-28', '2026-03-30', 820000.00),
(6, 'ORD-20260327-523EBE', 8, NULL, 7, 'distributor_confirmed', '2026-03-28', '2026-03-30', 72000.00);

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 8, 50, 580.00, 29000.00),
(2, 1, 6, 30, 4200.00, 126000.00),
(3, 1, 7, 100, 1450.00, 145000.00);

CREATE TABLE IF NOT EXISTS `warehouse_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `total_stock` int(11) NOT NULL DEFAULT 0,
  `reserved_stock` int(11) NOT NULL DEFAULT 0,
  `available_stock` int(11) GENERATED ALWAYS AS (`total_stock` - `reserved_stock`) STORED,
  `reorder_point` int(11) NOT NULL DEFAULT 100,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `idx_available` (`available_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `warehouse_stock` (`id`, `product_id`, `total_stock`, `reserved_stock`, `reorder_point`) VALUES
(1, 6, 10000, 30, 1000),
(2, 7, 10000, 100, 1000),
(3, 8, 10000, 50, 1000),
(4, 1, 10000, 0, 1000),
(5, 2, 10000, 0, 1000),
(6, 3, 10000, 0, 1000),
(7, 4, 10000, 2, 1000),
(8, 5, 10000, 30, 1000),
(9, 9, 10000, 0, 1000),
(10, 10, 10000, 90, 1000),
(11, 11, 10000, 0, 1000),
(12, 12, 10000, 0, 1000);

COMMIT;
