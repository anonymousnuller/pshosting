-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 01. Jun 2022 um 19:49
-- Server-Version: 10.5.15-MariaDB-0+deb11u1
-- PHP-Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `sedv_panel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cashbox_clicks`
--

CREATE TABLE `cashbox_clicks` (
  `id` int(11) NOT NULL,
  `box_id` varchar(255) NOT NULL,
  `ip_addr` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `state` enum('pending','active','banned') NOT NULL,
  `role` enum('customer','partner','first','second','third','admin') NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `session_token` varchar(255) DEFAULT NULL,
  `verify_code` varchar(255) DEFAULT NULL,
  `user_addr` varchar(255) DEFAULT NULL,
  `support_pin` varchar(255) DEFAULT NULL,
  `ticket_max` int(11) NOT NULL DEFAULT 2,
  `datasavingmode` int(11) NOT NULL DEFAULT 0,
  `darkmode` int(11) NOT NULL DEFAULT 0,
  `asap_option` int(11) NOT NULL DEFAULT 0,
  `notes` longtext DEFAULT NULL,
  `livechat` int(11) NOT NULL DEFAULT 1,
  `legal_accepted` int(11) NOT NULL DEFAULT 0,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `discord_id` varchar(255) DEFAULT NULL,
  `avatar_image` longtext DEFAULT NULL,
  `mail_login_notify` int(11) NOT NULL DEFAULT 1,
  `mail_support` int(11) NOT NULL DEFAULT 1,
  `mail_runtime` int(11) NOT NULL DEFAULT 1,
  `mail_suspend` int(11) NOT NULL DEFAULT 1,
  `mail_order` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ban_reason` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customers_charge_transactions`
--

CREATE TABLE `customers_charge_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `state` enum('pending','success','abort','expired','failed','canceled','refunded') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `tid` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customers_password_resets`
--

CREATE TABLE `customers_password_resets` (
  `id` int(11) NOT NULL,
  `user_info` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customer_transactions`
--

CREATE TABLE `customer_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dedicated_servers`
--

CREATE TABLE `dedicated_servers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `template_id` varchar(512) NOT NULL,
  `node_id` int(11) NOT NULL,
  `cores` int(11) NOT NULL,
  `memory` int(11) NOT NULL,
  `disc` int(11) NOT NULL,
  `addresses` int(11) NOT NULL,
  `network` varchar(255) DEFAULT NULL,
  `price` decimal(43,2) NOT NULL,
  `state` enum('ACTIVE','DISABLED','SUSPENDED','DELETED','PENDING') NOT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `locked` text DEFAULT NULL,
  `expire_at` datetime NOT NULL,
  `disc_name` varchar(255) DEFAULT NULL,
  `traffic` int(11) DEFAULT NULL,
  `curr_traffic` varchar(255) DEFAULT NULL,
  `api_name` enum('NO_API','PLOCIC','VENOCIX','GAME') DEFAULT NULL,
  `pack_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `type` enum('LXC','KVM') NOT NULL DEFAULT 'LXC',
  `notes` text DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `venocix_id` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dedicated_server_os`
--

CREATE TABLE `dedicated_server_os` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `type` enum('interwerk','maincubes','skylink','myloc') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dedicated_server_packs`
--

CREATE TABLE `dedicated_server_packs` (
  `id` int(11) NOT NULL,
  `type` enum('interwerk','maincubes','skylink','myloc') NOT NULL,
  `time_type` enum('pre','12','48','rest') NOT NULL,
  `name` varchar(255) NOT NULL,
  `rz` varchar(255) DEFAULT NULL,
  `cpu` varchar(255) NOT NULL,
  `memory` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL,
  `datacenter` varchar(255) NOT NULL,
  `uplink` varchar(255) NOT NULL,
  `traffic` varchar(255) NOT NULL,
  `ddos_protection` varchar(255) NOT NULL,
  `setup_price` decimal(12,2) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `available` varchar(255) DEFAULT NULL,
  `reordered` enum('yes','no') NOT NULL,
  `reorder_time` enum('12','48','72','other') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dedicated_skylink_packs`
--

CREATE TABLE `dedicated_skylink_packs` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `desc` longtext DEFAULT NULL,
  `cores` varchar(255) NOT NULL,
  `memory` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL,
  `ip_adresses` varchar(255) NOT NULL,
  `traffic` varchar(255) NOT NULL,
  `virtualsiserung` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ipv4_pool`
--

CREATE TABLE `ipv4_pool` (
  `id` int(110) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` enum('VPS') DEFAULT NULL,
  `node_id` varchar(512) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `cidr` int(11) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `rdns` varchar(512) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ipv6_pool`
--

CREATE TABLE `ipv6_pool` (
  `id` int(110) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` enum('VPS') DEFAULT NULL,
  `node_id` varchar(512) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `cidr` int(11) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `rdns` varchar(512) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ip_addresses`
--

CREATE TABLE `ip_addresses` (
  `id` int(110) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` enum('VPS') DEFAULT NULL,
  `node_id` varchar(512) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `cidr` int(11) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `rdns` varchar(512) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tabellenstruktur für Tabelle `kvm_servers`
--

CREATE TABLE `kvm_servers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `hosterapi_id` text DEFAULT NULL,
  `virtualizor_id` int(11) DEFAULT NULL,
  `node_id` int(11) DEFAULT NULL,
  `api_name` enum('NO_API','VIRTUALIZOR','HOSTERAPI') DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `template_id` varchar(512) NOT NULL,
  `cores` int(11) NOT NULL,
  `memory` int(11) NOT NULL,
  `disc` int(11) NOT NULL,
  `addresses` int(11) NOT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `curr_traffic` varchar(255) DEFAULT NULL,
  `traffic` int(11) DEFAULT NULL,
  `pack_name` varchar(255) DEFAULT NULL,
  `state` enum('ACTIVE','DISABLED','SUSPENDED','DELETED','PENDING') NOT NULL,
  `price` decimal(43,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `expire_at` datetime NOT NULL,
  `days` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `locked` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_nodes`
--

CREATE TABLE `kvm_servers_nodes` (
  `id` int(11) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `root_password` varchar(512) DEFAULT NULL,
  `realm` varchar(255) NOT NULL,
  `state` enum('ACTIVE','DISABLED') NOT NULL,
  `disc_name` varchar(255) NOT NULL,
  `disc_type` enum('ssd','hdd') NOT NULL,
  `api_name` enum('NO_API','PLOCIC','VENOCIX','GAME') NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `type` enum('LXC','KVM') NOT NULL DEFAULT 'LXC',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Tabellenstruktur für Tabelle `kvm_servers_options`
--

CREATE TABLE `kvm_servers_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_option_entries`
--

CREATE TABLE `kvm_servers_option_entries` (
  `id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` decimal(43,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_os`
--

CREATE TABLE `kvm_servers_os` (
  `id` int(11) NOT NULL,
  `virt_id` int(11) DEFAULT NULL,
  `prox_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `type` enum('INTEL','AMD','PROXMOX') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_packs`
--

CREATE TABLE `kvm_servers_packs` (
  `id` int(11) NOT NULL,
  `type` enum('INTEL','AMD') NOT NULL,
  `virt_id` int(11) DEFAULT NULL,
  `slave_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cores` varchar(255) NOT NULL,
  `memory` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL,
  `traffic` varchar(512) NOT NULL,
  `addresses` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_software`
--

CREATE TABLE `kvm_servers_software` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `url` varchar(512) NOT NULL,
  `file_name` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_software_tasks`
--

CREATE TABLE `kvm_servers_software_tasks` (
  `id` int(11) NOT NULL,
  `vm_id` int(11) NOT NULL,
  `type` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kvm_servers_tasks`
--

CREATE TABLE `kvm_servers_tasks` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `task` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_addr` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `user_location` varchar(255) NOT NULL,
  `show` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers`
--

CREATE TABLE `lxc_servers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `template_id` varchar(512) NOT NULL,
  `node_id` int(11) NOT NULL,
  `cores` int(11) NOT NULL,
  `memory` int(11) NOT NULL,
  `disc` int(11) NOT NULL,
  `addresses` int(11) NOT NULL,
  `network` varchar(255) DEFAULT NULL,
  `price` decimal(43,2) NOT NULL,
  `state` enum('ACTIVE','DISABLED','SUSPENDED','DELETED','PENDING') NOT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `locked` text DEFAULT NULL,
  `expire_at` datetime NOT NULL,
  `disc_name` varchar(255) DEFAULT NULL,
  `traffic` int(11) DEFAULT NULL,
  `curr_traffic` varchar(255) DEFAULT NULL,
  `api_name` enum('NO_API','PLOCIC','VENOCIX','GAME') DEFAULT NULL,
  `pack_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `type` enum('LXC','KVM') NOT NULL DEFAULT 'LXC',
  `notes` text DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `venocix_id` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_command_presets`
--

CREATE TABLE `lxc_servers_command_presets` (
  `id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `desc` text NOT NULL,
  `command` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_nodes`
--

CREATE TABLE `lxc_servers_nodes` (
  `id` int(11) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `root_password` varchar(512) DEFAULT NULL,
  `realm` varchar(255) NOT NULL,
  `state` enum('ACTIVE','DISABLED') NOT NULL,
  `disc_name` varchar(255) NOT NULL,
  `disc_type` enum('ssd','hdd') NOT NULL,
  `api_name` enum('NO_API','PLOCIC','VENOCIX','GAME') NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `type` enum('LXC','KVM') NOT NULL DEFAULT 'LXC',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_options`
--

CREATE TABLE `lxc_servers_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_option_entries`
--

CREATE TABLE `lxc_servers_option_entries` (
  `id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` decimal(43,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_os`
--

CREATE TABLE `lxc_servers_os` (
  `id` int(11) NOT NULL,
  `virt_id` int(11) DEFAULT NULL,
  `prox_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `type` enum('INTEL','AMD','PROXMOX') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_packs`
--

CREATE TABLE `lxc_servers_packs` (
  `id` int(11) NOT NULL,
  `type` enum('normal','game') NOT NULL DEFAULT 'normal',
  `name` varchar(255) NOT NULL,
  `cores` varchar(255) NOT NULL,
  `memory` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `lxc_servers_packs`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_software`
--

CREATE TABLE `lxc_servers_software` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `url` varchar(512) NOT NULL,
  `file_name` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_software_tasks`
--

CREATE TABLE `lxc_servers_software_tasks` (
  `id` int(11) NOT NULL,
  `vm_id` int(11) NOT NULL,
  `type` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lxc_servers_tasks`
--

CREATE TABLE `lxc_servers_tasks` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `task` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `user_id` int(111) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `title` varchar(512) NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_discounts`
--

CREATE TABLE `product_discounts` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expire_at` datetime NOT NULL,
  `permanent` enum('false','true') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_options`
--

CREATE TABLE `product_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_option_entries`
--

CREATE TABLE `product_option_entries` (
  `id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` decimal(43,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_prices`
--

CREATE TABLE `product_prices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updadted_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payload` longtext DEFAULT NULL,
  `retries` int(11) NOT NULL DEFAULT 0,
  `error_log` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_type` enum('monthly','yearly','once') NOT NULL DEFAULT 'monthly',
  `state` enum('active','pending','suspended','locked','deleted') NOT NULL,
  `type` enum('dedicated_server','install','service','other') NOT NULL,
  `days` int(11) NOT NULL,
  `locked` varchar(255) DEFAULT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expire_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE `settings` (
  `login` int(11) NOT NULL,
  `register` int(11) NOT NULL DEFAULT 1,
  `webspace` int(11) NOT NULL DEFAULT 1,
  `teamspeak` int(11) NOT NULL DEFAULT 1,
  `vps` int(11) NOT NULL DEFAULT 1,
  `psc_fees` int(5) NOT NULL DEFAULT 15,
  `payment_bonus` int(11) DEFAULT NULL,
  `payment_bonus_end` datetime DEFAULT NULL,
  `default_traffic_limit` int(11) NOT NULL DEFAULT 1000,
  `dedicated` int(11) NOT NULL,
  `domains` int(11) NOT NULL,
  `plesk` int(11) NOT NULL DEFAULT 1,
  `whmcs` int(11) NOT NULL,
  `tekbase` int(11) NOT NULL,
  `gameserver` int(11) NOT NULL,
  `rootserver` int(11) NOT NULL DEFAULT 1,
  `rootserver_intel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`login`, `register`, `webspace`, `teamspeak`, `vps`, `psc_fees`, `payment_bonus`, `payment_bonus_end`, `default_traffic_limit`, `dedicated`, `domains`, `plesk`, `whmcs`, `tekbase`, `gameserver`, `rootserver`, `rootserver_intel`) VALUES
(1, 1, 1, 1, 1, 20, 20, '2022-06-11 21:07:25', 1024, 1, 1, 1, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `categorie` enum('ALLGEMEIN','TECHNIK','BUCHHALTUNG','PARTNER','FEEDBACK','AUSFALL','BUGS') NOT NULL,
  `priority` enum('LOW','MIDDEL','HIGH','SEHR','ASAP') NOT NULL,
  `title` varchar(255) NOT NULL,
  `state` enum('OPEN','PROCESSING','WAITINGC','WAITINGI','CLOSED') NOT NULL,
  `last_msg` enum('CUSTOMER','SUPPORT') NOT NULL,
  `product_category` enum('teamspeak','lxc','kvm','dedicated','webspace','domain','plesk_license','services') DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `support_tickets_messages`
--

CREATE TABLE `support_tickets_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `type` enum('message','answer','team_answer','log') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `webspaces`
--

CREATE TABLE `webspaces` (
  `id` int(11) NOT NULL,
  `plan_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `node_id` varchar(50) NOT NULL,
  `ftp_name` varchar(255) NOT NULL,
  `ftp_password` varchar(255) NOT NULL,
  `plesk_uid` int(115) DEFAULT NULL,
  `plesk_password` varchar(512) NOT NULL,
  `domainName` varchar(255) NOT NULL,
  `webspace_id` int(11) NOT NULL,
  `state` enum('active','suspended','deleted') NOT NULL,
  `expire_at` datetime NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `locked` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `webspaces_hosts`
--

CREATE TABLE `webspaces_hosts` (
  `id` int(11) NOT NULL,
  `node_id` varchar(255) DEFAULT NULL,
  `domainName` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `ipv6` varchar(1024) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `state` enum('offline','online') NOT NULL,
  `url` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `webspaces_packs_normal`
--

CREATE TABLE `webspaces_packs_normal` (
  `id` int(11) NOT NULL,
  `plesk_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `disc` varchar(255) NOT NULL,
  `domains` varchar(255) NOT NULL,
  `subdomains` varchar(255) NOT NULL,
  `databases` varchar(255) NOT NULL,
  `ftp_accounts` varchar(255) NOT NULL,
  `emails` varchar(255) NOT NULL,
  `frontend` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `customers_charge_transactions`
--
ALTER TABLE `customers_charge_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `customers_password_resets`
--
ALTER TABLE `customers_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `customer_transactions`
--
ALTER TABLE `customer_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dedicated_server_packs`
--
ALTER TABLE `dedicated_server_packs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `ipv4_pool`
--
ALTER TABLE `ipv4_pool`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ip_addresses`
--
ALTER TABLE `ip_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers`
--
ALTER TABLE `kvm_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers_nodes`
--
ALTER TABLE `kvm_servers_nodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `kvm_servers_options`
--
ALTER TABLE `kvm_servers_options`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers_option_entries`
--
ALTER TABLE `kvm_servers_option_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers_os`
--
ALTER TABLE `kvm_servers_os`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers_packs`
--
ALTER TABLE `kvm_servers_packs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kvm_servers_tasks`
--
ALTER TABLE `kvm_servers_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers`
--
ALTER TABLE `lxc_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_command_presets`
--
ALTER TABLE `lxc_servers_command_presets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_nodes`
--
ALTER TABLE `lxc_servers_nodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `lxc_servers_options`
--
ALTER TABLE `lxc_servers_options`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_option_entries`
--
ALTER TABLE `lxc_servers_option_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_os`
--
ALTER TABLE `lxc_servers_os`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_packs`
--
ALTER TABLE `lxc_servers_packs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_software`
--
ALTER TABLE `lxc_servers_software`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_software_tasks`
--
ALTER TABLE `lxc_servers_software_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lxc_servers_tasks`
--
ALTER TABLE `lxc_servers_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_option_entries`
--
ALTER TABLE `product_option_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`login`);

--
-- Indizes für die Tabelle `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `support_tickets_messages`
--
ALTER TABLE `support_tickets_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `webspaces`
--
ALTER TABLE `webspaces`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `webspaces_hosts`
--
ALTER TABLE `webspaces_hosts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `webspaces_packs_normal`
--
ALTER TABLE `webspaces_packs_normal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `customers_charge_transactions`
--
ALTER TABLE `customers_charge_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `customers_password_resets`
--
ALTER TABLE `customers_password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `customer_transactions`
--
ALTER TABLE `customer_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `ipv4_pool`
--
ALTER TABLE `ipv4_pool`
  MODIFY `id` int(110) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers`
--
ALTER TABLE `kvm_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=906;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_nodes`
--
ALTER TABLE `kvm_servers_nodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_options`
--
ALTER TABLE `kvm_servers_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_option_entries`
--
ALTER TABLE `kvm_servers_option_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_os`
--
ALTER TABLE `kvm_servers_os`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_packs`
--
ALTER TABLE `kvm_servers_packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `kvm_servers_tasks`
--
ALTER TABLE `kvm_servers_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers`
--
ALTER TABLE `lxc_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_command_presets`
--
ALTER TABLE `lxc_servers_command_presets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_nodes`
--
ALTER TABLE `lxc_servers_nodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_options`
--
ALTER TABLE `lxc_servers_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_option_entries`
--
ALTER TABLE `lxc_servers_option_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_software`
--
ALTER TABLE `lxc_servers_software`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_software_tasks`
--
ALTER TABLE `lxc_servers_software_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `lxc_servers_tasks`
--
ALTER TABLE `lxc_servers_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `product_option_entries`
--
ALTER TABLE `product_option_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `support_tickets_messages`
--
ALTER TABLE `support_tickets_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `webspaces`
--
ALTER TABLE `webspaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `webspaces_hosts`
--
ALTER TABLE `webspaces_hosts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `webspaces_packs_normal`
--
ALTER TABLE `webspaces_packs_normal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
