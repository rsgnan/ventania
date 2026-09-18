-- Ventania - banco de dados de desenvolvimento
-- Massa de dados integralmente regenerada em 18/09/2026
-- Usuário padrão: admin / admin

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Estrutura: activity_logs
CREATE TABLE `activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `record_type` varchar(50) NOT NULL,
  `record_id` int(10) UNSIGNED NOT NULL,
  `record_label` varchar(255) NOT NULL,
  `action` varchar(50) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: categories
CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon_svg` mediumtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: products
CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `tag` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: sales
CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'completed',
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: sale_items
CREATE TABLE `sale_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `original_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: sale_item_changes
CREATE TABLE `sale_item_changes` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `sale_item_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `action` enum('created','updated','removed') NOT NULL,
  `old_quantity` int(10) UNSIGNED DEFAULT NULL,
  `new_quantity` int(10) UNSIGNED DEFAULT NULL,
  `old_unit_price` decimal(10,2) DEFAULT NULL,
  `new_unit_price` decimal(10,2) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Estrutura: users
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'operator',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `users` (`id`,`name`,`username`,`email`,`password`,`role`,`is_active`,`last_ip`,`created_at`,`updated_at`) VALUES
(1, 'Administrador', 'admin', 'admin@ventania.local', '$2y$12$fsUt0STylkiEqtjNKdrrmOYUu8RKjzfqbPYvzGja6Z3nW2W3B5SxS', 'admin', 1, NULL, '2026-09-18 00:00:00', '2026-09-18 00:00:00');

INSERT INTO `categories` (`id`,`name`,`icon_svg`,`created_at`,`updated_at`) VALUES
(1, 'Vestuários', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"12\" cy=\"4\" r=\"1.4\"/><path d=\"M12 5.4v2.2\"/><path d=\"M12 7.6l-9 6.4c-1 .7-.5 2.3.7 2.3h16.6c1.2 0 1.7-1.6.7-2.3l-9-6.4z\"/><line x1=\"4\" y1=\"20\" x2=\"20\" y2=\"20\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(2, 'Pedras', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M6 3h12l3 5-9 13L3 8z\"/><path d=\"M3 8h18\"/><path d=\"M9 3l-2 5 5 13 5-13-2-5\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(3, 'Guias', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M4 6c2 6 6 9 8 9s6-3 8-9\"/><circle cx=\"4\" cy=\"6\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"7.5\" cy=\"11.5\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"12\" cy=\"14.5\" r=\"1.6\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"16.5\" cy=\"11.5\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"20\" cy=\"6\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(4, 'Livros', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M12 6c-1.5-1.3-3.6-2-6.5-2-1 0-1.5.3-1.5 1v13c0 .7.5 1 1.5 1 2.9 0 5 .7 6.5 2\"/><path d=\"M12 6c1.5-1.3 3.6-2 6.5-2 1 0 1.5.3 1.5 1v13c0 .7-.5 1-1.5 1-2.9 0-5 .7-6.5 2V6z\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(5, 'Banhos', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M12 2s7 8.5 7 13a7 7 0 01-14 0c0-4.5 7-13 7-13z\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(6, 'Velas', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><rect x=\"9\" y=\"10\" width=\"6\" height=\"11\" rx=\"1\"/><path d=\"M12 10c-1.5-1.8-1.2-3.6 0-5.5C13.2 6.4 13.5 8.2 12 10z\"/></svg>', '2026-08-01 09:00:00', '2026-08-01 09:00:00');

INSERT INTO `products` (`id`,`name`,`category_id`,`tag`,`price`,`stock`,`description`,`photo`,`created_at`,`updated_at`) VALUES
(1, 'Saia Branca Rendada', 1, 'Ritual', 89.90, 18, 'Saia longa em tecido leve com barra rendada, ideal para gira e rituais de Umbanda.', 'vestuario-saia-branca-rendada.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(2, 'Blusa de Cetim Branca', 1, 'Gira', 69.90, 22, 'Blusa de manga longa em cetim branco, corte tradicional para trabalhos de terreiro.', 'vestuario-blusa-de-cetim-branca.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(3, 'Turbante Branco Amarrado', 1, 'Cabeça', 39.90, 30, 'Turbante em tecido de algodão já modelado, pronto para uso em rituais e giras.', 'vestuario-turbante-branco-amarrado.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(4, 'Pano da Costa Estampado', 1, 'Ombro', 79.90, 15, 'Pano da costa em algodão estampado, usado sobre o ombro em cerimônias de Umbanda.', 'vestuario-pano-da-costa-estampado.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(5, 'Anágua de Balão', 1, 'Rodada', 54.90, 20, 'Anágua rodada em tecido fresco, dá volume às saias rituais.', 'vestuario-anagua-de-balao.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(6, 'Camisa de Alinhavo Masculina', 1, 'Terno', 74.90, 14, 'Camisa branca de linho com alinhavo bordado à mão, uso masculino em cerimônias.', 'vestuario-camisa-de-alinhavo-masculina.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(7, 'Saia Rodada Sete Cores', 1, 'Pombagira', 99.90, 10, 'Saia rodada com fitas nas sete cores, referência às linhas de Pombagira.', 'vestuario-saia-rodada-sete-cores.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(8, 'Blusa Ciganinha Vermelha', 1, 'Cigana', 64.90, 16, 'Blusa ombro a ombro vermelha, modelo cigana usado em giras de Exu e Pombagira.', 'vestuario-blusa-ciganinha-vermelha.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(9, 'Quartzo Rosa Bruto', 2, 'Amor', 24.90, 40, 'Pedra bruta de quartzo rosa, associada ao amor próprio e à harmonia afetiva.', 'pedra-quartzo-rosa-bruto.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(10, 'Ametista Ponta Natural', 2, 'Proteção', 34.90, 35, 'Ponta natural de ametista, usada para proteção espiritual e elevação da energia.', 'pedra-ametista-ponta-natural.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(11, 'Olho de Tigre Rolado', 2, 'Prosperidade', 19.90, 45, 'Pedra rolada de olho de tigre, associada à prosperidade e à firmeza de propósito.', 'pedra-olho-de-tigre-rolado.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(12, 'Quartzo Branco Lapidado', 2, 'Limpeza', 22.90, 38, 'Quartzo branco lapidado, indicado para limpeza e equilíbrio energético do ambiente.', 'pedra-quartzo-branco-lapidado.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(13, 'Turmalina Negra Bruta', 2, 'Aterramento', 29.90, 28, 'Pedra bruta de turmalina negra, usada para aterramento e proteção contra energias densas.', 'pedra-turmalina-negra-bruta.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(14, 'Citrino Ponta Natural', 2, 'Abundância', 32.90, 25, 'Ponta natural de citrino, associada à abundância e à alegria.', 'pedra-citrino-ponta-natural.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(15, 'Ágata Azul Rolada', 2, 'Calma', 21.90, 24, 'Pedra rolada de ágata azul, indicada para trazer calma e clareza mental.', 'pedra-agata-azul-rolada.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(16, 'Obsidiana Negra Polida', 2, 'Blindagem', 26.90, 30, 'Pedra polida de obsidiana negra, usada como blindagem energética.', 'pedra-obsidiana-negra-polida.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(17, 'Guia de Oxalá', 3, 'Paz', 119.90, 12, 'Guia de contas brancas dedicada a Oxalá, símbolo de paz e criação.', 'guia-guia-de-oxala.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(18, 'Guia de Iemanjá', 3, 'Mar', 129.90, 12, 'Guia de contas em tons de azul e cristal, dedicada a Iemanjá, rainha do mar.', 'guia-guia-de-iemanja.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(19, 'Guia de Ogum', 3, 'Vitória', 109.90, 14, 'Guia de contas azul-marinho, dedicada a Ogum, orixá guerreiro.', 'guia-guia-de-ogum.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(20, 'Guia de Oxóssi', 3, 'Mata', 114.90, 13, 'Guia de contas verdes, dedicada a Oxóssi, senhor das matas.', 'guia-guia-de-oxossi.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(21, 'Guia de Xangô', 3, 'Justiça', 124.90, 11, 'Guia de contas marrom e branco, dedicada a Xangô, orixá da justiça.', 'guia-guia-de-xango.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(22, 'Guia de Iansã', 3, 'Ventania', 109.90, 13, 'Guia de contas amarelas e vermelhas, dedicada a Iansã, senhora dos ventos.', 'guia-guia-de-iansa.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(23, 'Guia de Oxum', 3, 'Amor', 119.90, 12, 'Guia de contas douradas, dedicada a Oxum, orixá do amor e das águas doces.', 'guia-guia-de-oxum.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(24, 'Guia de Pombagira', 3, 'Mulher', 99.90, 15, 'Guia de contas coloridas, dedicada às Pombagiras, entidades femininas da Umbanda.', 'guia-guia-de-pombagira.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(25, 'Umbanda Sagrada', 4, 'Fundamentos', 49.90, 20, 'Livro introdutório sobre os fundamentos e a história da Umbanda.', 'livro-umbanda-sagrada.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(26, 'Pontos Cantados na Umbanda', 4, 'Cânticos', 39.90, 24, 'Coletânea de pontos cantados usados em giras e rituais.', 'livro-pontos-cantados-na-umbanda.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(27, 'As Sete Linhas de Umbanda', 4, 'Linhas', 44.90, 18, 'Estudo sobre as sete linhas de trabalho espiritual da Umbanda.', 'livro-as-sete-linhas-de-umbanda.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(28, 'Ervas Sagradas na Umbanda', 4, 'Ervas', 42.90, 19, 'Guia prático sobre o uso ritualístico de ervas na Umbanda.', 'livro-ervas-sagradas-na-umbanda.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(29, 'Orixás — Mitos e Mensagens', 4, 'Mitologia', 54.90, 16, 'Livro sobre a mitologia e as mensagens dos orixás africanos.', 'livro-orixas-mitos-e-mensagens.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(30, 'O Livro dos Médiuns Umbandistas', 4, 'Mediunidade', 47.90, 17, 'Obra dedicada ao desenvolvimento mediúnico dentro da Umbanda.', 'livro-o-livro-dos-mediuns-umbandistas.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(31, 'Exu — O Guardião das Encruzilhadas', 4, 'Exu', 45.90, 20, 'Estudo sobre a figura de Exu e seu papel como guardião espiritual.', 'livro-exu-o-guardiao-das-encruzilhadas.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(32, 'Preto Velho — Sabedoria Ancestral', 4, 'Preto Velho', 41.90, 21, 'Livro sobre a sabedoria e a história dos Pretos Velhos na Umbanda.', 'livro-preto-velho-sabedoria-ancestral.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(33, 'Banho de Arruda', 5, 'Descarrego', 14.90, 50, 'Banho ritual à base de arruda, indicado para descarrego energético.', 'banho-banho-de-arruda.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(34, 'Banho de Alecrim', 5, 'Proteção', 15.90, 43, 'Banho ritual à base de alecrim, indicado para proteção espiritual.', 'banho-banho-de-alecrim.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(35, 'Banho de Rosas Brancas', 5, 'Amor Próprio', 16.90, 40, 'Banho ritual à base de pétalas de rosas brancas, indicado para amor próprio.', 'banho-banho-de-rosas-brancas.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(36, 'Banho de Sete Ervas', 5, 'Abertura de Caminhos', 18.90, 35, 'Banho ritual composto por sete ervas, indicado para abertura de caminhos.', 'banho-banho-de-sete-ervas.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(37, 'Banho de Manjericão', 5, 'Prosperidade', 15.90, 42, 'Banho ritual à base de manjericão, associado à prosperidade.', 'banho-banho-de-manjericao.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(38, 'Banho de Espada de Ogum', 5, 'Força', 17.90, 30, 'Banho ritual à base de espada-de-ogum, indicado para força e coragem.', 'banho-banho-de-espada-de-ogum.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(39, 'Banho de Alfazema', 5, 'Harmonia', 16.90, 38, 'Banho ritual à base de alfazema, indicado para harmonia no lar.', 'banho-banho-de-alfazema.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(40, 'Banho de Folha de Louro', 5, 'Sorte', 14.90, 45, 'Banho ritual à base de folhas de louro, associado à sorte.', 'banho-banho-de-folha-de-louro.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(41, 'Vela Branca 7 Dias', 6, 'Paz', 12.90, 60, 'Vela branca de sete dias, usada para pedidos de paz e harmonia.', 'vela-vela-branca-7-dias.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(42, 'Vela Vermelha', 6, 'Paixão', 8.90, 70, 'Vela vermelha, associada à paixão e à força vital.', 'vela-vela-vermelha.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(43, 'Vela Azul', 6, 'Cura', 8.90, 65, 'Vela azul, associada à cura e à tranquilidade.', 'vela-vela-azul.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(44, 'Vela Amarela', 6, 'Prosperidade', 8.90, 68, 'Vela amarela, associada à prosperidade e à conquista.', 'vela-vela-amarela.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(45, 'Vela Verde', 6, 'Esperança', 8.90, 66, 'Vela verde, associada à esperança e à cura financeira.', 'vela-vela-verde.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(46, 'Vela Roxa', 6, 'Espiritualidade', 9.90, 55, 'Vela roxa, associada à espiritualidade e à elevação.', 'vela-vela-roxa.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(47, 'Vela de Exu Preta e Vermelha', 6, 'Exu', 11.90, 40, 'Vela bicolor preta e vermelha, usada em trabalhos para Exu.', 'vela-vela-de-exu-preta-e-vermelha.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00'),
(48, 'Vela de Pombagira Rosa', 6, 'Pombagira', 11.90, 40, 'Vela rosa, usada em trabalhos e oferendas para Pombagira.', 'vela-vela-de-pombagira-rosa.jpg', '2026-08-01 09:30:00', '2026-08-01 09:30:00');

INSERT INTO `sales` (`id`,`customer_name`,`discount_amount`,`total_amount`,`status`,`user_id`,`created_at`,`updated_at`) VALUES
(1, 'Ana Paula Ribeiro', 0.00, 92.80, 'completed', 1, '2026-08-04 18:20:00', '2026-08-04 18:20:00'),
(2, 'Bruno Martins', 0.00, 419.40, 'completed', 1, '2026-08-06 02:20:00', '2026-08-06 02:20:00'),
(3, 'Camila Ferreira', 0.00, 729.00, 'completed', 1, '2026-08-07 10:20:00', '2026-08-07 10:20:00'),
(4, 'Daniel Souza', 0.00, 359.70, 'completed', 1, '2026-08-08 18:20:00', '2026-08-08 18:20:00'),
(5, 'Elaine Rodrigues', 10.00, 409.50, 'completed', 1, '2026-08-09 16:20:00', '2026-08-09 16:20:00'),
(6, 'Felipe Almeida', 0.00, 383.20, 'completed', 1, '2026-08-11 00:20:00', '2026-08-11 00:45:00'),
(7, 'Gabriela Costa', 0.00, 242.40, 'cancelled', 1, '2026-08-12 08:20:00', '2026-08-12 08:20:00'),
(8, 'Henrique Oliveira', 0.00, 8.90, 'completed', 1, '2026-08-13 16:20:00', '2026-08-13 16:20:00'),
(9, 'Isabela Santos', 0.00, 69.60, 'completed', 1, '2026-08-15 00:20:00', '2026-08-15 00:20:00'),
(10, 'João Victor Lima', 10.00, 194.40, 'completed', 1, '2026-08-15 22:20:00', '2026-08-15 22:20:00'),
(11, 'Karina Nascimento', 0.00, 288.20, 'completed', 1, '2026-08-17 06:20:00', '2026-08-17 06:20:00'),
(12, 'Lucas Pereira', 0.00, 23.80, 'pending', 1, '2026-08-18 14:20:00', '2026-08-18 14:20:00'),
(13, 'Mariana Barbosa', 0.00, 43.60, 'completed', 1, '2026-08-19 22:20:00', '2026-08-19 22:20:00'),
(14, 'Nicolas Cardoso', 0.00, 246.20, 'completed', 1, '2026-08-21 06:20:00', '2026-08-21 06:45:00'),
(15, 'Olívia Ramos', 10.00, 338.20, 'completed', 1, '2026-08-22 04:20:00', '2026-08-22 04:20:00'),
(16, 'Paulo Mendes', 0.00, 17.80, 'completed', 1, '2026-08-23 12:20:00', '2026-08-23 12:20:00'),
(17, 'Queila Fernandes', 0.00, 190.50, 'completed', 1, '2026-08-24 20:20:00', '2026-08-24 20:20:00'),
(18, 'Rafael Duarte', 0.00, 140.40, 'cancelled', 1, '2026-08-26 04:20:00', '2026-08-26 04:20:00'),
(19, 'Sabrina Teixeira', 0.00, 274.20, 'completed', 1, '2026-08-27 12:20:00', '2026-08-27 12:20:00'),
(20, 'Tiago Moreira', 10.00, 59.90, 'completed', 1, '2026-08-28 10:20:00', '2026-08-28 10:20:00'),
(21, 'Vanessa Lopes', 0.00, 47.80, 'completed', 1, '2026-08-29 18:20:00', '2026-08-29 18:20:00'),
(22, 'William Carvalho', 0.00, 511.30, 'completed', 1, '2026-08-31 02:20:00', '2026-08-31 02:45:00'),
(23, 'Yasmin Araújo', 0.00, 298.30, 'completed', 1, '2026-09-01 10:20:00', '2026-09-01 10:20:00'),
(24, 'Zélia Batista', 0.00, 99.90, 'completed', 1, '2026-09-02 18:20:00', '2026-09-02 18:20:00'),
(25, 'Ana Paula Ribeiro', 10.00, 33.80, 'completed', 1, '2026-09-03 16:20:00', '2026-09-03 16:20:00'),
(26, 'Bruno Martins', 0.00, 279.60, 'completed', 1, '2026-09-05 00:20:00', '2026-09-05 00:20:00'),
(27, 'Camila Ferreira', 0.00, 130.40, 'pending', 1, '2026-09-06 08:20:00', '2026-09-06 08:20:00'),
(28, 'Daniel Souza', 0.00, 14.90, 'completed', 1, '2026-09-07 16:20:00', '2026-09-07 16:20:00'),
(29, 'Elaine Rodrigues', 0.00, 229.60, 'completed', 1, '2026-09-09 00:20:00', '2026-09-09 00:20:00'),
(30, 'Felipe Almeida', 10.00, 463.40, 'completed', 1, '2026-09-09 22:20:00', '2026-09-09 22:45:00'),
(31, 'Gabriela Costa', 0.00, 458.40, 'cancelled', 1, '2026-09-11 06:20:00', '2026-09-11 06:20:00'),
(32, 'Henrique Oliveira', 0.00, 199.80, 'completed', 1, '2026-09-12 14:20:00', '2026-09-12 14:20:00'),
(33, 'Isabela Santos', 0.00, 72.70, 'completed', 1, '2026-09-13 22:20:00', '2026-09-13 22:20:00'),
(34, 'João Victor Lima', 0.00, 598.20, 'completed', 1, '2026-09-15 06:20:00', '2026-09-15 06:20:00'),
(35, 'Karina Nascimento', 10.00, 347.10, 'pending', 1, '2026-09-16 04:20:00', '2026-09-16 04:20:00'),
(36, 'Lucas Pereira', 0.00, 65.80, 'completed', 1, '2026-09-17 12:20:00', '2026-09-17 12:20:00');

INSERT INTO `sale_items` (`id`,`sale_id`,`product_id`,`product_name`,`original_price`,`unit_price`,`quantity`,`subtotal`,`created_at`,`updated_at`) VALUES
(1, 1, 6, 'Camisa de Alinhavo Masculina', 74.90, 74.90, 1, 74.90, '2026-08-04 18:20:00', '2026-08-04 18:20:00'),
(2, 1, 38, 'Banho de Espada de Ogum', 17.90, 17.90, 1, 17.90, '2026-08-04 18:20:00', '2026-08-04 18:20:00'),
(3, 2, 26, 'Pontos Cantados na Umbanda', 39.90, 39.90, 2, 79.80, '2026-08-06 02:20:00', '2026-08-06 02:20:00'),
(4, 2, 2, 'Blusa de Cetim Branca', 69.90, 69.90, 2, 139.80, '2026-08-06 02:20:00', '2026-08-06 02:20:00'),
(5, 2, 7, 'Saia Rodada Sete Cores', 99.90, 99.90, 2, 199.80, '2026-08-06 02:20:00', '2026-08-06 02:20:00'),
(6, 3, 6, 'Camisa de Alinhavo Masculina', 74.90, 74.90, 3, 224.70, '2026-08-07 10:20:00', '2026-08-07 10:20:00'),
(7, 3, 19, 'Guia de Ogum', 109.90, 109.90, 3, 329.70, '2026-08-07 10:20:00', '2026-08-07 10:20:00'),
(8, 3, 26, 'Pontos Cantados na Umbanda', 39.90, 39.90, 3, 119.70, '2026-08-07 10:20:00', '2026-08-07 10:20:00'),
(9, 3, 5, 'Anágua de Balão', 54.90, 54.90, 1, 54.90, '2026-08-07 10:20:00', '2026-08-07 10:20:00'),
(10, 4, 23, 'Guia de Oxum', 119.90, 119.90, 3, 359.70, '2026-08-08 18:20:00', '2026-08-08 18:20:00'),
(11, 5, 6, 'Camisa de Alinhavo Masculina', 74.90, 74.90, 2, 149.80, '2026-08-09 16:20:00', '2026-08-09 16:20:00'),
(12, 5, 1, 'Saia Branca Rendada', 89.90, 89.90, 3, 269.70, '2026-08-09 16:20:00', '2026-08-09 16:20:00'),
(13, 6, 29, 'Orixás — Mitos e Mensagens', 54.90, 54.90, 4, 219.60, '2026-08-11 00:20:00', '2026-08-11 00:45:00'),
(14, 6, 2, 'Blusa de Cetim Branca', 69.90, 69.90, 2, 139.80, '2026-08-11 00:20:00', '2026-08-11 00:20:00'),
(15, 6, 47, 'Vela de Exu Preta e Vermelha', 11.90, 11.90, 2, 23.80, '2026-08-11 00:20:00', '2026-08-11 00:20:00'),
(16, 7, 41, 'Vela Branca 7 Dias', 12.90, 12.90, 1, 12.90, '2026-08-12 08:20:00', '2026-08-12 08:20:00'),
(17, 7, 26, 'Pontos Cantados na Umbanda', 39.90, 39.90, 2, 79.80, '2026-08-12 08:20:00', '2026-08-12 08:20:00'),
(18, 7, 33, 'Banho de Arruda', 14.90, 14.90, 2, 29.80, '2026-08-12 08:20:00', '2026-08-12 08:20:00'),
(19, 7, 23, 'Guia de Oxum', 119.90, 119.90, 1, 119.90, '2026-08-12 08:20:00', '2026-08-12 08:20:00'),
(20, 8, 45, 'Vela Verde', 8.90, 8.90, 1, 8.90, '2026-08-13 16:20:00', '2026-08-13 16:20:00'),
(21, 9, 28, 'Ervas Sagradas na Umbanda', 42.90, 42.90, 1, 42.90, '2026-08-15 00:20:00', '2026-08-15 00:20:00'),
(22, 9, 43, 'Vela Azul', 8.90, 8.90, 3, 26.70, '2026-08-15 00:20:00', '2026-08-15 00:20:00'),
(23, 10, 14, 'Citrino Ponta Natural', 32.90, 32.90, 3, 98.70, '2026-08-15 22:20:00', '2026-08-15 22:20:00'),
(24, 10, 37, 'Banho de Manjericão', 15.90, 15.90, 1, 15.90, '2026-08-15 22:20:00', '2026-08-15 22:20:00'),
(25, 10, 27, 'As Sete Linhas de Umbanda', 44.90, 44.90, 2, 89.80, '2026-08-15 22:20:00', '2026-08-15 22:20:00'),
(26, 11, 13, 'Turmalina Negra Bruta', 29.90, 29.90, 3, 89.70, '2026-08-17 06:20:00', '2026-08-17 06:20:00'),
(27, 11, 30, 'O Livro dos Médiuns Umbandistas', 47.90, 47.90, 3, 143.70, '2026-08-17 06:20:00', '2026-08-17 06:20:00'),
(28, 11, 44, 'Vela Amarela', 8.90, 8.90, 1, 8.90, '2026-08-17 06:20:00', '2026-08-17 06:20:00'),
(29, 11, 31, 'Exu — O Guardião das Encruzilhadas', 45.90, 45.90, 1, 45.90, '2026-08-17 06:20:00', '2026-08-17 06:20:00'),
(30, 12, 47, 'Vela de Exu Preta e Vermelha', 11.90, 11.90, 2, 23.80, '2026-08-18 14:20:00', '2026-08-18 14:20:00'),
(31, 13, 41, 'Vela Branca 7 Dias', 12.90, 12.90, 2, 25.80, '2026-08-19 22:20:00', '2026-08-19 22:20:00'),
(32, 13, 43, 'Vela Azul', 8.90, 8.90, 2, 17.80, '2026-08-19 22:20:00', '2026-08-19 22:20:00'),
(33, 14, 37, 'Banho de Manjericão', 15.90, 15.90, 3, 47.70, '2026-08-21 06:20:00', '2026-08-21 06:45:00'),
(34, 14, 35, 'Banho de Rosas Brancas', 16.90, 16.90, 2, 33.80, '2026-08-21 06:20:00', '2026-08-21 06:20:00'),
(35, 14, 29, 'Orixás — Mitos e Mensagens', 54.90, 54.90, 3, 164.70, '2026-08-21 06:20:00', '2026-08-21 06:20:00'),
(36, 15, 25, 'Umbanda Sagrada', 49.90, 49.90, 2, 99.80, '2026-08-22 04:20:00', '2026-08-22 04:20:00'),
(37, 15, 36, 'Banho de Sete Ervas', 18.90, 18.90, 1, 18.90, '2026-08-22 04:20:00', '2026-08-22 04:20:00'),
(38, 15, 5, 'Anágua de Balão', 54.90, 54.90, 2, 109.80, '2026-08-22 04:20:00', '2026-08-22 04:20:00'),
(39, 15, 3, 'Turbante Branco Amarrado', 39.90, 39.90, 3, 119.70, '2026-08-22 04:20:00', '2026-08-22 04:20:00'),
(40, 16, 45, 'Vela Verde', 8.90, 8.90, 2, 17.80, '2026-08-23 12:20:00', '2026-08-23 12:20:00'),
(41, 17, 39, 'Banho de Alfazema', 16.90, 16.90, 3, 50.70, '2026-08-24 20:20:00', '2026-08-24 20:20:00'),
(42, 17, 2, 'Blusa de Cetim Branca', 69.90, 69.90, 2, 139.80, '2026-08-24 20:20:00', '2026-08-24 20:20:00'),
(43, 18, 12, 'Quartzo Branco Lapidado', 22.90, 22.90, 3, 68.70, '2026-08-26 04:20:00', '2026-08-26 04:20:00'),
(44, 18, 15, 'Ágata Azul Rolada', 21.90, 21.90, 1, 21.90, '2026-08-26 04:20:00', '2026-08-26 04:20:00'),
(45, 18, 9, 'Quartzo Rosa Bruto', 24.90, 24.90, 2, 49.80, '2026-08-26 04:20:00', '2026-08-26 04:20:00'),
(46, 19, 16, 'Obsidiana Negra Polida', 26.90, 26.90, 2, 53.80, '2026-08-27 12:20:00', '2026-08-27 12:20:00'),
(47, 19, 19, 'Guia de Ogum', 109.90, 109.90, 1, 109.90, '2026-08-27 12:20:00', '2026-08-27 12:20:00'),
(48, 19, 32, 'Preto Velho — Sabedoria Ancestral', 41.90, 41.90, 2, 83.80, '2026-08-27 12:20:00', '2026-08-27 12:20:00'),
(49, 19, 44, 'Vela Amarela', 8.90, 8.90, 3, 26.70, '2026-08-27 12:20:00', '2026-08-27 12:20:00'),
(50, 20, 2, 'Blusa de Cetim Branca', 69.90, 69.90, 1, 69.90, '2026-08-28 10:20:00', '2026-08-28 10:20:00'),
(51, 21, 13, 'Turmalina Negra Bruta', 29.90, 29.90, 1, 29.90, '2026-08-29 18:20:00', '2026-08-29 18:20:00'),
(52, 21, 38, 'Banho de Espada de Ogum', 17.90, 17.90, 1, 17.90, '2026-08-29 18:20:00', '2026-08-29 18:20:00'),
(53, 22, 28, 'Ervas Sagradas na Umbanda', 42.90, 42.90, 4, 171.60, '2026-08-31 02:20:00', '2026-08-31 02:45:00'),
(54, 22, 24, 'Guia de Pombagira', 99.90, 99.90, 1, 99.90, '2026-08-31 02:20:00', '2026-08-31 02:20:00'),
(55, 22, 23, 'Guia de Oxum', 119.90, 119.90, 2, 239.80, '2026-08-31 02:20:00', '2026-08-31 02:20:00'),
(56, 23, 36, 'Banho de Sete Ervas', 18.90, 18.90, 1, 18.90, '2026-09-01 10:20:00', '2026-09-01 10:20:00'),
(57, 23, 37, 'Banho de Manjericão', 15.90, 15.90, 1, 15.90, '2026-09-01 10:20:00', '2026-09-01 10:20:00'),
(58, 23, 47, 'Vela de Exu Preta e Vermelha', 11.90, 11.90, 2, 23.80, '2026-09-01 10:20:00', '2026-09-01 10:20:00'),
(59, 23, 4, 'Pano da Costa Estampado', 79.90, 79.90, 3, 239.70, '2026-09-01 10:20:00', '2026-09-01 10:20:00'),
(60, 24, 24, 'Guia de Pombagira', 99.90, 99.90, 1, 99.90, '2026-09-02 18:20:00', '2026-09-02 18:20:00'),
(61, 25, 44, 'Vela Amarela', 8.90, 8.90, 1, 8.90, '2026-09-03 16:20:00', '2026-09-03 16:20:00'),
(62, 25, 10, 'Ametista Ponta Natural', 34.90, 34.90, 1, 34.90, '2026-09-03 16:20:00', '2026-09-03 16:20:00'),
(63, 26, 7, 'Saia Rodada Sete Cores', 99.90, 99.90, 2, 199.80, '2026-09-05 00:20:00', '2026-09-05 00:20:00'),
(64, 26, 8, 'Blusa Ciganinha Vermelha', 64.90, 64.90, 1, 64.90, '2026-09-05 00:20:00', '2026-09-05 00:20:00'),
(65, 26, 40, 'Banho de Folha de Louro', 14.90, 14.90, 1, 14.90, '2026-09-05 00:20:00', '2026-09-05 00:20:00'),
(66, 27, 6, 'Camisa de Alinhavo Masculina', 74.90, 74.90, 1, 74.90, '2026-09-06 08:20:00', '2026-09-06 08:20:00'),
(67, 27, 35, 'Banho de Rosas Brancas', 16.90, 16.90, 1, 16.90, '2026-09-06 08:20:00', '2026-09-06 08:20:00'),
(68, 27, 46, 'Vela Roxa', 9.90, 9.90, 3, 29.70, '2026-09-06 08:20:00', '2026-09-06 08:20:00'),
(69, 27, 42, 'Vela Vermelha', 8.90, 8.90, 1, 8.90, '2026-09-06 08:20:00', '2026-09-06 08:20:00'),
(70, 28, 40, 'Banho de Folha de Louro', 14.90, 14.90, 1, 14.90, '2026-09-07 16:20:00', '2026-09-07 16:20:00'),
(71, 29, 6, 'Camisa de Alinhavo Masculina', 74.90, 74.90, 2, 149.80, '2026-09-09 00:20:00', '2026-09-09 00:20:00'),
(72, 29, 3, 'Turbante Branco Amarrado', 39.90, 39.90, 2, 79.80, '2026-09-09 00:20:00', '2026-09-09 00:20:00'),
(73, 30, 19, 'Guia de Ogum', 109.90, 109.90, 2, 219.80, '2026-09-09 22:20:00', '2026-09-09 22:45:00'),
(74, 30, 20, 'Guia de Oxóssi', 114.90, 114.90, 2, 229.80, '2026-09-09 22:20:00', '2026-09-09 22:20:00'),
(75, 30, 47, 'Vela de Exu Preta e Vermelha', 11.90, 11.90, 2, 23.80, '2026-09-09 22:20:00', '2026-09-09 22:20:00'),
(76, 31, 17, 'Guia de Oxalá', 119.90, 119.90, 2, 239.80, '2026-09-11 06:20:00', '2026-09-11 06:20:00'),
(77, 31, 23, 'Guia de Oxum', 119.90, 119.90, 1, 119.90, '2026-09-11 06:20:00', '2026-09-11 06:20:00'),
(78, 31, 8, 'Blusa Ciganinha Vermelha', 64.90, 64.90, 1, 64.90, '2026-09-11 06:20:00', '2026-09-11 06:20:00'),
(79, 31, 35, 'Banho de Rosas Brancas', 16.90, 16.90, 2, 33.80, '2026-09-11 06:20:00', '2026-09-11 06:20:00'),
(80, 32, 7, 'Saia Rodada Sete Cores', 99.90, 99.90, 2, 199.80, '2026-09-12 14:20:00', '2026-09-12 14:20:00'),
(81, 33, 14, 'Citrino Ponta Natural', 32.90, 32.90, 1, 32.90, '2026-09-13 22:20:00', '2026-09-13 22:20:00'),
(82, 33, 11, 'Olho de Tigre Rolado', 19.90, 19.90, 2, 39.80, '2026-09-13 22:20:00', '2026-09-13 22:20:00'),
(83, 34, 41, 'Vela Branca 7 Dias', 12.90, 12.90, 3, 38.70, '2026-09-15 06:20:00', '2026-09-15 06:20:00'),
(84, 34, 24, 'Guia de Pombagira', 99.90, 99.90, 3, 299.70, '2026-09-15 06:20:00', '2026-09-15 06:20:00'),
(85, 34, 18, 'Guia de Iemanjá', 129.90, 129.90, 2, 259.80, '2026-09-15 06:20:00', '2026-09-15 06:20:00'),
(86, 35, 34, 'Banho de Alecrim', 15.90, 15.90, 2, 31.80, '2026-09-16 04:20:00', '2026-09-16 04:20:00'),
(87, 35, 44, 'Vela Amarela', 8.90, 8.90, 2, 17.80, '2026-09-16 04:20:00', '2026-09-16 04:20:00'),
(88, 35, 18, 'Guia de Iemanjá', 129.90, 129.90, 2, 259.80, '2026-09-16 04:20:00', '2026-09-16 04:20:00'),
(89, 35, 37, 'Banho de Manjericão', 15.90, 15.90, 3, 47.70, '2026-09-16 04:20:00', '2026-09-16 04:20:00'),
(90, 36, 14, 'Citrino Ponta Natural', 32.90, 32.90, 2, 65.80, '2026-09-17 12:20:00', '2026-09-17 12:20:00');

INSERT INTO `sale_item_changes` (`id`,`sale_id`,`sale_item_id`,`product_id`,`product_name`,`action`,`old_quantity`,`new_quantity`,`old_unit_price`,`new_unit_price`,`user_id`,`created_at`) VALUES
(1, 1, 1, 6, 'Camisa de Alinhavo Masculina', 'created', NULL, 1, NULL, 74.90, 1, '2026-08-04 18:20:00'),
(2, 1, 2, 38, 'Banho de Espada de Ogum', 'created', NULL, 1, NULL, 17.90, 1, '2026-08-04 18:20:00'),
(3, 2, 3, 26, 'Pontos Cantados na Umbanda', 'created', NULL, 2, NULL, 39.90, 1, '2026-08-06 02:20:00'),
(4, 2, 4, 2, 'Blusa de Cetim Branca', 'created', NULL, 2, NULL, 69.90, 1, '2026-08-06 02:20:00'),
(5, 2, 5, 7, 'Saia Rodada Sete Cores', 'created', NULL, 2, NULL, 99.90, 1, '2026-08-06 02:20:00'),
(6, 3, 6, 6, 'Camisa de Alinhavo Masculina', 'created', NULL, 3, NULL, 74.90, 1, '2026-08-07 10:20:00'),
(7, 3, 7, 19, 'Guia de Ogum', 'created', NULL, 3, NULL, 109.90, 1, '2026-08-07 10:20:00'),
(8, 3, 8, 26, 'Pontos Cantados na Umbanda', 'created', NULL, 3, NULL, 39.90, 1, '2026-08-07 10:20:00'),
(9, 3, 9, 5, 'Anágua de Balão', 'created', NULL, 1, NULL, 54.90, 1, '2026-08-07 10:20:00'),
(10, 4, 10, 23, 'Guia de Oxum', 'created', NULL, 3, NULL, 119.90, 1, '2026-08-08 18:20:00'),
(11, 5, 11, 6, 'Camisa de Alinhavo Masculina', 'created', NULL, 2, NULL, 74.90, 1, '2026-08-09 16:20:00'),
(12, 5, 12, 1, 'Saia Branca Rendada', 'created', NULL, 3, NULL, 89.90, 1, '2026-08-09 16:20:00'),
(13, 6, 13, 29, 'Orixás — Mitos e Mensagens', 'created', NULL, 3, NULL, 54.90, 1, '2026-08-11 00:20:00'),
(14, 6, 14, 2, 'Blusa de Cetim Branca', 'created', NULL, 2, NULL, 69.90, 1, '2026-08-11 00:20:00'),
(15, 6, 15, 47, 'Vela de Exu Preta e Vermelha', 'created', NULL, 2, NULL, 11.90, 1, '2026-08-11 00:20:00'),
(16, 6, 13, 29, 'Orixás — Mitos e Mensagens', 'updated', 3, 4, 54.90, 54.90, 1, '2026-08-11 00:45:00'),
(17, 7, 16, 41, 'Vela Branca 7 Dias', 'created', NULL, 1, NULL, 12.90, 1, '2026-08-12 08:20:00'),
(18, 7, 17, 26, 'Pontos Cantados na Umbanda', 'created', NULL, 2, NULL, 39.90, 1, '2026-08-12 08:20:00'),
(19, 7, 18, 33, 'Banho de Arruda', 'created', NULL, 2, NULL, 14.90, 1, '2026-08-12 08:20:00'),
(20, 7, 19, 23, 'Guia de Oxum', 'created', NULL, 1, NULL, 119.90, 1, '2026-08-12 08:20:00'),
(21, 8, 20, 45, 'Vela Verde', 'created', NULL, 1, NULL, 8.90, 1, '2026-08-13 16:20:00'),
(22, 9, 21, 28, 'Ervas Sagradas na Umbanda', 'created', NULL, 1, NULL, 42.90, 1, '2026-08-15 00:20:00'),
(23, 9, 22, 43, 'Vela Azul', 'created', NULL, 3, NULL, 8.90, 1, '2026-08-15 00:20:00'),
(24, 10, 23, 14, 'Citrino Ponta Natural', 'created', NULL, 3, NULL, 32.90, 1, '2026-08-15 22:20:00'),
(25, 10, 24, 37, 'Banho de Manjericão', 'created', NULL, 1, NULL, 15.90, 1, '2026-08-15 22:20:00'),
(26, 10, 25, 27, 'As Sete Linhas de Umbanda', 'created', NULL, 2, NULL, 44.90, 1, '2026-08-15 22:20:00'),
(27, 11, 26, 13, 'Turmalina Negra Bruta', 'created', NULL, 3, NULL, 29.90, 1, '2026-08-17 06:20:00'),
(28, 11, 27, 30, 'O Livro dos Médiuns Umbandistas', 'created', NULL, 3, NULL, 47.90, 1, '2026-08-17 06:20:00'),
(29, 11, 28, 44, 'Vela Amarela', 'created', NULL, 1, NULL, 8.90, 1, '2026-08-17 06:20:00'),
(30, 11, 29, 31, 'Exu — O Guardião das Encruzilhadas', 'created', NULL, 1, NULL, 45.90, 1, '2026-08-17 06:20:00'),
(31, 12, 30, 47, 'Vela de Exu Preta e Vermelha', 'created', NULL, 2, NULL, 11.90, 1, '2026-08-18 14:20:00'),
(32, 13, 31, 41, 'Vela Branca 7 Dias', 'created', NULL, 2, NULL, 12.90, 1, '2026-08-19 22:20:00'),
(33, 13, 32, 43, 'Vela Azul', 'created', NULL, 2, NULL, 8.90, 1, '2026-08-19 22:20:00'),
(34, 14, 33, 37, 'Banho de Manjericão', 'created', NULL, 2, NULL, 15.90, 1, '2026-08-21 06:20:00'),
(35, 14, 34, 35, 'Banho de Rosas Brancas', 'created', NULL, 2, NULL, 16.90, 1, '2026-08-21 06:20:00'),
(36, 14, 35, 29, 'Orixás — Mitos e Mensagens', 'created', NULL, 3, NULL, 54.90, 1, '2026-08-21 06:20:00'),
(37, 14, 33, 37, 'Banho de Manjericão', 'updated', 2, 3, 15.90, 15.90, 1, '2026-08-21 06:45:00'),
(38, 15, 36, 25, 'Umbanda Sagrada', 'created', NULL, 2, NULL, 49.90, 1, '2026-08-22 04:20:00'),
(39, 15, 37, 36, 'Banho de Sete Ervas', 'created', NULL, 1, NULL, 18.90, 1, '2026-08-22 04:20:00'),
(40, 15, 38, 5, 'Anágua de Balão', 'created', NULL, 2, NULL, 54.90, 1, '2026-08-22 04:20:00'),
(41, 15, 39, 3, 'Turbante Branco Amarrado', 'created', NULL, 3, NULL, 39.90, 1, '2026-08-22 04:20:00'),
(42, 16, 40, 45, 'Vela Verde', 'created', NULL, 2, NULL, 8.90, 1, '2026-08-23 12:20:00'),
(43, 17, 41, 39, 'Banho de Alfazema', 'created', NULL, 3, NULL, 16.90, 1, '2026-08-24 20:20:00'),
(44, 17, 42, 2, 'Blusa de Cetim Branca', 'created', NULL, 2, NULL, 69.90, 1, '2026-08-24 20:20:00'),
(45, 18, 43, 12, 'Quartzo Branco Lapidado', 'created', NULL, 3, NULL, 22.90, 1, '2026-08-26 04:20:00'),
(46, 18, 44, 15, 'Ágata Azul Rolada', 'created', NULL, 1, NULL, 21.90, 1, '2026-08-26 04:20:00'),
(47, 18, 45, 9, 'Quartzo Rosa Bruto', 'created', NULL, 2, NULL, 24.90, 1, '2026-08-26 04:20:00'),
(48, 19, 46, 16, 'Obsidiana Negra Polida', 'created', NULL, 2, NULL, 26.90, 1, '2026-08-27 12:20:00'),
(49, 19, 47, 19, 'Guia de Ogum', 'created', NULL, 1, NULL, 109.90, 1, '2026-08-27 12:20:00'),
(50, 19, 48, 32, 'Preto Velho — Sabedoria Ancestral', 'created', NULL, 2, NULL, 41.90, 1, '2026-08-27 12:20:00'),
(51, 19, 49, 44, 'Vela Amarela', 'created', NULL, 3, NULL, 8.90, 1, '2026-08-27 12:20:00'),
(52, 20, 50, 2, 'Blusa de Cetim Branca', 'created', NULL, 1, NULL, 69.90, 1, '2026-08-28 10:20:00'),
(53, 21, 51, 13, 'Turmalina Negra Bruta', 'created', NULL, 1, NULL, 29.90, 1, '2026-08-29 18:20:00'),
(54, 21, 52, 38, 'Banho de Espada de Ogum', 'created', NULL, 1, NULL, 17.90, 1, '2026-08-29 18:20:00'),
(55, 22, 53, 28, 'Ervas Sagradas na Umbanda', 'created', NULL, 3, NULL, 42.90, 1, '2026-08-31 02:20:00'),
(56, 22, 54, 24, 'Guia de Pombagira', 'created', NULL, 1, NULL, 99.90, 1, '2026-08-31 02:20:00'),
(57, 22, 55, 23, 'Guia de Oxum', 'created', NULL, 2, NULL, 119.90, 1, '2026-08-31 02:20:00'),
(58, 22, 53, 28, 'Ervas Sagradas na Umbanda', 'updated', 3, 4, 42.90, 42.90, 1, '2026-08-31 02:45:00'),
(59, 23, 56, 36, 'Banho de Sete Ervas', 'created', NULL, 1, NULL, 18.90, 1, '2026-09-01 10:20:00'),
(60, 23, 57, 37, 'Banho de Manjericão', 'created', NULL, 1, NULL, 15.90, 1, '2026-09-01 10:20:00'),
(61, 23, 58, 47, 'Vela de Exu Preta e Vermelha', 'created', NULL, 2, NULL, 11.90, 1, '2026-09-01 10:20:00'),
(62, 23, 59, 4, 'Pano da Costa Estampado', 'created', NULL, 3, NULL, 79.90, 1, '2026-09-01 10:20:00'),
(63, 24, 60, 24, 'Guia de Pombagira', 'created', NULL, 1, NULL, 99.90, 1, '2026-09-02 18:20:00'),
(64, 25, 61, 44, 'Vela Amarela', 'created', NULL, 1, NULL, 8.90, 1, '2026-09-03 16:20:00'),
(65, 25, 62, 10, 'Ametista Ponta Natural', 'created', NULL, 1, NULL, 34.90, 1, '2026-09-03 16:20:00'),
(66, 26, 63, 7, 'Saia Rodada Sete Cores', 'created', NULL, 2, NULL, 99.90, 1, '2026-09-05 00:20:00'),
(67, 26, 64, 8, 'Blusa Ciganinha Vermelha', 'created', NULL, 1, NULL, 64.90, 1, '2026-09-05 00:20:00'),
(68, 26, 65, 40, 'Banho de Folha de Louro', 'created', NULL, 1, NULL, 14.90, 1, '2026-09-05 00:20:00'),
(69, 27, 66, 6, 'Camisa de Alinhavo Masculina', 'created', NULL, 1, NULL, 74.90, 1, '2026-09-06 08:20:00'),
(70, 27, 67, 35, 'Banho de Rosas Brancas', 'created', NULL, 1, NULL, 16.90, 1, '2026-09-06 08:20:00'),
(71, 27, 68, 46, 'Vela Roxa', 'created', NULL, 3, NULL, 9.90, 1, '2026-09-06 08:20:00'),
(72, 27, 69, 42, 'Vela Vermelha', 'created', NULL, 1, NULL, 8.90, 1, '2026-09-06 08:20:00'),
(73, 28, 70, 40, 'Banho de Folha de Louro', 'created', NULL, 1, NULL, 14.90, 1, '2026-09-07 16:20:00'),
(74, 29, 71, 6, 'Camisa de Alinhavo Masculina', 'created', NULL, 2, NULL, 74.90, 1, '2026-09-09 00:20:00'),
(75, 29, 72, 3, 'Turbante Branco Amarrado', 'created', NULL, 2, NULL, 39.90, 1, '2026-09-09 00:20:00'),
(76, 30, 73, 19, 'Guia de Ogum', 'created', NULL, 1, NULL, 109.90, 1, '2026-09-09 22:20:00'),
(77, 30, 74, 20, 'Guia de Oxóssi', 'created', NULL, 2, NULL, 114.90, 1, '2026-09-09 22:20:00'),
(78, 30, 75, 47, 'Vela de Exu Preta e Vermelha', 'created', NULL, 2, NULL, 11.90, 1, '2026-09-09 22:20:00'),
(79, 30, 73, 19, 'Guia de Ogum', 'updated', 1, 2, 109.90, 109.90, 1, '2026-09-09 22:45:00'),
(80, 31, 76, 17, 'Guia de Oxalá', 'created', NULL, 2, NULL, 119.90, 1, '2026-09-11 06:20:00'),
(81, 31, 77, 23, 'Guia de Oxum', 'created', NULL, 1, NULL, 119.90, 1, '2026-09-11 06:20:00'),
(82, 31, 78, 8, 'Blusa Ciganinha Vermelha', 'created', NULL, 1, NULL, 64.90, 1, '2026-09-11 06:20:00'),
(83, 31, 79, 35, 'Banho de Rosas Brancas', 'created', NULL, 2, NULL, 16.90, 1, '2026-09-11 06:20:00'),
(84, 32, 80, 7, 'Saia Rodada Sete Cores', 'created', NULL, 2, NULL, 99.90, 1, '2026-09-12 14:20:00'),
(85, 33, 81, 14, 'Citrino Ponta Natural', 'created', NULL, 1, NULL, 32.90, 1, '2026-09-13 22:20:00'),
(86, 33, 82, 11, 'Olho de Tigre Rolado', 'created', NULL, 2, NULL, 19.90, 1, '2026-09-13 22:20:00'),
(87, 34, 83, 41, 'Vela Branca 7 Dias', 'created', NULL, 3, NULL, 12.90, 1, '2026-09-15 06:20:00'),
(88, 34, 84, 24, 'Guia de Pombagira', 'created', NULL, 3, NULL, 99.90, 1, '2026-09-15 06:20:00'),
(89, 34, 85, 18, 'Guia de Iemanjá', 'created', NULL, 2, NULL, 129.90, 1, '2026-09-15 06:20:00'),
(90, 35, 86, 34, 'Banho de Alecrim', 'created', NULL, 2, NULL, 15.90, 1, '2026-09-16 04:20:00'),
(91, 35, 87, 44, 'Vela Amarela', 'created', NULL, 2, NULL, 8.90, 1, '2026-09-16 04:20:00'),
(92, 35, 88, 18, 'Guia de Iemanjá', 'created', NULL, 2, NULL, 129.90, 1, '2026-09-16 04:20:00'),
(93, 35, 89, 37, 'Banho de Manjericão', 'created', NULL, 3, NULL, 15.90, 1, '2026-09-16 04:20:00'),
(94, 36, 90, 14, 'Citrino Ponta Natural', 'created', NULL, 2, NULL, 32.90, 1, '2026-09-17 12:20:00');

INSERT INTO `activity_logs` (`id`,`record_type`,`record_id`,`record_label`,`action`,`user_id`,`created_at`) VALUES
(1, 'sale', 1, 'Venda #1', 'create', 1, '2026-08-04 18:20:00'),
(2, 'sale', 2, 'Venda #2', 'create', 1, '2026-08-06 02:20:00'),
(3, 'sale', 3, 'Venda #3', 'create', 1, '2026-08-07 10:20:00'),
(4, 'sale', 4, 'Venda #4', 'create', 1, '2026-08-08 18:20:00'),
(5, 'sale', 5, 'Venda #5', 'create', 1, '2026-08-09 16:20:00'),
(6, 'sale', 6, 'Venda #6', 'create', 1, '2026-08-11 00:20:00'),
(7, 'sale', 6, 'Venda #6', 'update', 1, '2026-08-11 00:45:00'),
(8, 'sale', 7, 'Venda #7', 'create', 1, '2026-08-12 08:20:00'),
(9, 'sale', 8, 'Venda #8', 'create', 1, '2026-08-13 16:20:00'),
(10, 'sale', 9, 'Venda #9', 'create', 1, '2026-08-15 00:20:00'),
(11, 'sale', 10, 'Venda #10', 'create', 1, '2026-08-15 22:20:00'),
(12, 'sale', 11, 'Venda #11', 'create', 1, '2026-08-17 06:20:00'),
(13, 'sale', 12, 'Venda #12', 'create', 1, '2026-08-18 14:20:00'),
(14, 'sale', 13, 'Venda #13', 'create', 1, '2026-08-19 22:20:00'),
(15, 'sale', 14, 'Venda #14', 'create', 1, '2026-08-21 06:20:00'),
(16, 'sale', 14, 'Venda #14', 'update', 1, '2026-08-21 06:45:00'),
(17, 'sale', 15, 'Venda #15', 'create', 1, '2026-08-22 04:20:00'),
(18, 'sale', 16, 'Venda #16', 'create', 1, '2026-08-23 12:20:00'),
(19, 'sale', 17, 'Venda #17', 'create', 1, '2026-08-24 20:20:00'),
(20, 'sale', 18, 'Venda #18', 'create', 1, '2026-08-26 04:20:00'),
(21, 'sale', 19, 'Venda #19', 'create', 1, '2026-08-27 12:20:00'),
(22, 'sale', 20, 'Venda #20', 'create', 1, '2026-08-28 10:20:00'),
(23, 'sale', 21, 'Venda #21', 'create', 1, '2026-08-29 18:20:00'),
(24, 'sale', 22, 'Venda #22', 'create', 1, '2026-08-31 02:20:00'),
(25, 'sale', 22, 'Venda #22', 'update', 1, '2026-08-31 02:45:00'),
(26, 'sale', 23, 'Venda #23', 'create', 1, '2026-09-01 10:20:00'),
(27, 'sale', 24, 'Venda #24', 'create', 1, '2026-09-02 18:20:00'),
(28, 'sale', 25, 'Venda #25', 'create', 1, '2026-09-03 16:20:00'),
(29, 'sale', 26, 'Venda #26', 'create', 1, '2026-09-05 00:20:00'),
(30, 'sale', 27, 'Venda #27', 'create', 1, '2026-09-06 08:20:00'),
(31, 'sale', 28, 'Venda #28', 'create', 1, '2026-09-07 16:20:00'),
(32, 'sale', 29, 'Venda #29', 'create', 1, '2026-09-09 00:20:00'),
(33, 'sale', 30, 'Venda #30', 'create', 1, '2026-09-09 22:20:00'),
(34, 'sale', 30, 'Venda #30', 'update', 1, '2026-09-09 22:45:00'),
(35, 'sale', 31, 'Venda #31', 'create', 1, '2026-09-11 06:20:00'),
(36, 'sale', 32, 'Venda #32', 'create', 1, '2026-09-12 14:20:00'),
(37, 'sale', 33, 'Venda #33', 'create', 1, '2026-09-13 22:20:00'),
(38, 'sale', 34, 'Venda #34', 'create', 1, '2026-09-15 06:20:00'),
(39, 'sale', 35, 'Venda #35', 'create', 1, '2026-09-16 04:20:00'),
(40, 'sale', 36, 'Venda #36', 'create', 1, '2026-09-17 12:20:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_logs_user_id` (`user_id`),
  ADD KEY `idx_activity_logs_record` (`record_type`,`record_id`),
  ADD KEY `idx_activity_logs_created_at` (`created_at`);

--
-- Índices de tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `idx_products_name` (`name`);

--
-- Índices de tabela `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sales_customer` (`customer_name`),
  ADD KEY `idx_sales_status` (`status`),
  ADD KEY `fk_sales_user` (`user_id`);


--
-- Índices de tabela `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sale_items_sale` (`sale_id`),
  ADD KEY `idx_sale_items_product` (`product_id`);

--
-- Índices de tabela `sale_item_changes`
--
ALTER TABLE `sale_item_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sale_item_changes_sale` (`sale_id`),
  ADD KEY `idx_sale_item_changes_item` (`sale_item_id`),
  ADD KEY `idx_sale_item_changes_product` (`product_id`),
  ADD KEY `idx_sale_item_changes_user` (`user_id`),
  ADD KEY `idx_sale_item_changes_created_at` (`created_at`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;


--
-- AUTO_INCREMENT de tabela `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de tabela `sale_item_changes`
--
ALTER TABLE `sale_item_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;


--
-- Restrições para tabelas `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `fk_sale_items_sale`
    FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sale_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `sale_item_changes`
--
ALTER TABLE `sale_item_changes`
  ADD CONSTRAINT `fk_sale_item_changes_sale`
    FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sale_item_changes_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sale_item_changes_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
