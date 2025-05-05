
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `checkouts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `checkouts` (`id`, `product_id`, `user_id`, `quantity`, `status`, `created_at`) VALUES
(12, 6, 1, 1, 'paid', '2025-01-03 13:36:52'),
(13, 6, 1, 1, 'paid', '2025-01-03 13:37:31'),
(14, 6, 1, 1, 'paid', '2025-01-03 13:41:37'),
(15, 6, 1, 1, 'paid', '2025-01-03 13:41:45'),
(17, 6, 1, 1, 'paid', '2025-01-03 13:42:22'),
(18, 5, 1, 1, 'paid', '2025-01-03 13:45:37'),
(19, 11, 1, 1, 'paid', '2025-01-03 14:19:42'),
(20, 5, 1, 2, 'paid', '2025-01-03 14:33:05'),
(21, 7, 1, 1, 'unpaid', '2025-01-04 08:28:27');



CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `products` (`id`, `category`, `name`, `price`, `stock`, `image`, `created_at`) VALUES
(5, 'Accessories', 'Gelang', 15000, '6', '1735746886-gelang.jpeg', '2025-01-01 15:54:46'),
(6, 'clothes', 'Baju Cewe', 170000, '6', '1735747569-white-denim-jacket-front-view-streetwear-fashion-scaled.jpg', '2025-01-01 16:06:09'),
(7, 'shoes', 'Sepatu cowo', 200000, '2', '1735747828-sepat.jpg', '2025-01-01 16:10:28'),
(8, 'shoes', 'Sepatu Converse', 250000, '2', '1735749760-converse.jpeg', '2025-01-01 16:42:40'),
(9, 'shoes', 'Sepatu Murah', 100000, '9', '1735750059-gudangventela_124646017_820646835432806_6703998581866251577_n.jpg', '2025-01-01 16:47:39'),
(11, 'Accessories', 'Gelang', 5000, '10', '1735826211-gelang.jpeg', '2025-01-02 13:56:51'),
(12, 'clothes', 'Baju merah', 75000, '2', '1735910208-baju.jpg', '2025-01-03 13:16:48'),
(13, 'Accessories', 'Baju new', 200000, '4', '1735914680-baju.jpg', '2025-01-03 14:31:20');


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`) VALUES
(1, 'muhammadrendykrisna@gmail.com', 'Rendy', '$2y$10$i2xPgcm63WGIWmG7D8yinuEp5azXuWZG.iIJ1qbDxjwIOvD/zOVYy', 'user');


ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `checkouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;


ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `checkouts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

