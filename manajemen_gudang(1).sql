-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 04:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(11) NOT NULL,
  `outbound_id` int(11) DEFAULT NULL,
  `kurir_id` int(11) DEFAULT NULL,
  `destination` varchar(200) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` enum('disiapkan','dalam_pengiriman','terkirim','gagal') DEFAULT 'disiapkan',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `outbound_id`, `kurir_id`, `destination`, `delivery_date`, `status`, `note`, `created_at`) VALUES
(1, 1, 6, 'Jl. Raya Bekasi No. 12, Jakarta Timur', '2026-05-05', 'terkirim', 'Diterima Pak Hendra', '2026-05-05 06:22:31'),
(2, 2, 7, 'Jl. Sudirman No. 88, Jakarta Pusat', '2026-05-05', 'terkirim', 'Diterima Bu Ratna', '2026-05-05 06:22:31'),
(3, 3, 6, 'Jl. Alam Sutera, Tangerang', '2026-05-05', 'terkirim', 'Pengiriman 2 hari', '2026-05-05 06:22:31'),
(4, 5, 7, 'Jl. Ahmad Yani No. 5, Bekasi', '2026-05-05', 'dalam_pengiriman', 'Estimasi sampai besok', '2026-05-05 06:22:31'),
(5, 4, 6, 'Jl. TB Simatupang No. 3, Jakarta Selatan', '2026-05-05', 'disiapkan', 'Menunggu konfirmasi', '2026-05-05 06:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `inbound`
--

CREATE TABLE `inbound` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outbound`
--

CREATE TABLE `outbound` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `destination` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outbound`
--

INSERT INTO `outbound` (`id`, `product_id`, `quantity`, `destination`, `created_by`, `status`, `approved_by`, `approved_at`, `created_at`) VALUES
(1, 1, 50, 'Cabang Jakarta Timur', 4, 'approved', 2, '2026-05-05 06:22:06', '2026-05-05 06:22:06'),
(2, 4, 100, 'Cabang Jakarta Pusat', 5, 'approved', 3, '2026-05-05 06:22:06', '2026-05-05 06:22:06'),
(3, 8, 5, 'Gudang Tangerang', 4, 'approved', 2, '2026-05-05 06:22:06', '2026-05-05 06:22:06'),
(4, 2, 30, 'Cabang Jakarta Selatan', 5, 'pending', NULL, NULL, '2026-05-05 06:22:06'),
(5, 12, 10, 'Kantor Pusat Bekasi', 4, 'approved', 3, '2026-05-05 06:22:06', '2026-05-05 06:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `unit` varchar(20) DEFAULT 'pcs',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `stock`, `unit`, `created_at`) VALUES
(1, 'Kardus Ukuran S', 'PRD-001', 150, 'pcs', '2026-05-05 06:21:44'),
(2, 'Kardus Ukuran M', 'PRD-002', 200, 'pcs', '2026-05-05 06:21:44'),
(3, 'Kardus Ukuran L', 'PRD-003', 80, 'pcs', '2026-05-05 06:21:44'),
(4, 'Bubble Wrap', 'PRD-004', 500, 'meter', '2026-05-05 06:21:44'),
(5, 'Selotip Bening', 'PRD-005', 100, 'roll', '2026-05-05 06:21:44'),
(6, 'Laptop Acer', 'PRD-006', 12, 'unit', '2026-05-05 06:21:44'),
(7, 'Printer Canon', 'PRD-007', 5, 'unit', '2026-05-05 06:21:44'),
(8, 'Helm Safety', 'PRD-008', 30, 'pcs', '2026-05-05 06:21:44'),
(9, 'Rompi Safety', 'PRD-009', 25, 'pcs', '2026-05-05 06:21:44'),
(10, 'Hand Pallet', 'PRD-010', 4, 'unit', '2026-05-05 06:21:44'),
(11, 'Timbangan Digital', 'PRD-011', 6, 'unit', '2026-05-05 06:21:44'),
(12, 'Kertas HVS A4', 'PRD-012', 50, 'rim', '2026-05-05 06:21:44'),
(13, 'Pulpen', 'PRD-013', 20, 'lusin', '2026-05-05 06:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` enum('admin','supervisor','gudang','kurir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'supervisor'),
(3, 'gudang'),
(4, 'kurir');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'Administrator', 'admin@gudang.com', '0192023a7bbd73250516f069df18b500', 1, '2026-05-05 06:20:48'),
(2, 'Budi Santoso', 'supervisor1@gudang.com', '0192023a7bbd73250516f069df18b500', 2, '2026-05-05 06:20:48'),
(3, 'Rina Marlina', 'supervisor2@gudang.com', '0192023a7bbd73250516f069df18b500', 2, '2026-05-05 06:20:48'),
(4, 'Agus Wahyudi', 'gudang1@gudang.com', '0192023a7bbd73250516f069df18b500', 3, '2026-05-05 06:20:48'),
(5, 'Dewi Lestari', 'gudang2@gudang.com', '0192023a7bbd73250516f069df18b500', 3, '2026-05-05 06:20:48'),
(6, 'Fahmi Kusnadi', 'kurir1@gudang.com', '0192023a7bbd73250516f069df18b500', 4, '2026-05-05 06:20:48'),
(7, 'Siti Aminah', 'kurir2@gudang.com', '0192023a7bbd73250516f069df18b500', 4, '2026-05-05 06:20:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outbound_id` (`outbound_id`),
  ADD KEY `kurir_id` (`kurir_id`);

--
-- Indexes for table `inbound`
--
ALTER TABLE `inbound`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `outbound`
--
ALTER TABLE `outbound`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inbound`
--
ALTER TABLE `inbound`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outbound`
--
ALTER TABLE `outbound`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`outbound_id`) REFERENCES `outbound` (`id`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`kurir_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inbound`
--
ALTER TABLE `inbound`
  ADD CONSTRAINT `inbound_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `inbound_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `inbound_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inbound_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `outbound`
--
ALTER TABLE `outbound`
  ADD CONSTRAINT `outbound_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `outbound_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `outbound_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
