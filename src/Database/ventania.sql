-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 17/09/2026 às 21:17
-- Versão do servidor: 11.8.8-MariaDB
-- Versão do PHP: 8.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ventania`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `record_type` varchar(50) NOT NULL,
  `record_id` int(10) UNSIGNED NOT NULL,
  `record_label` varchar(255) NOT NULL,
  `action` varchar(50) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Despejando dados para a tabela `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `record_type`, `record_id`, `record_label`, `action`, `user_id`, `created_at`) VALUES
(1, 'sale', 20, 'Venda #20', 'create', 1, '2026-09-15 15:19:45'),
(2, 'sale', 19, 'Venda #19', 'update', 1, '2026-09-15 15:19:53'),
(3, 'sale', 20, 'Venda #20', 'update', 1, '2026-09-15 15:20:06'),
(4, 'sale', 21, 'Venda #21', 'create', 1, '2026-09-15 15:20:31'),
(5, 'sale', 21, 'Venda #21', 'update', 1, '2026-09-15 15:20:34'),
(6, 'sale', 22, 'Venda #22', 'create', 1, '2026-09-17 02:46:03'),
(7, 'sale', 22, 'Venda #22', 'update', 1, '2026-09-17 02:46:58'),
(8, 'sale', 21, 'Venda #21', 'update', 1, '2026-09-17 02:47:20'),
(9, 'sale', 20, 'Venda #20', 'update', 1, '2026-09-17 02:48:31'),
(10, 'sale', 20, 'Venda #20', 'update', 1, '2026-09-17 02:48:55'),
(11, 'sale', 20, 'Venda #20', 'update', 1, '2026-09-17 02:49:46'),
(12, 'sale', 20, 'Venda #20', 'update', 1, '2026-09-17 03:10:15'),
(13, 'sale', 23, 'Venda #23', 'create', 1, '2026-09-17 04:18:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon_svg` mediumtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon_svg`, `created_at`, `updated_at`) VALUES
(1, 'Vestuários', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"12\" cy=\"4\" r=\"1.4\"/><path d=\"M12 5.4v2.2\"/><path d=\"M12 7.6l-9 6.4c-1 .7-.5 2.3.7 2.3h16.6c1.2 0 1.7-1.6.7-2.3l-9-6.4z\"/><line x1=\"4\" y1=\"20\" x2=\"20\" y2=\"20\"/></svg>', '2026-08-01 15:56:53', '2026-08-17 15:53:00'),
(2, 'Pedras', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M6 3h12l3 5-9 13L3 8z\"/><path d=\"M3 8h18\"/><path d=\"M9 3l-2 5 5 13 5-13-2-5\"/></svg>', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(3, 'Guias', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M4 6c2 6 6 9 8 9s6-3 8-9\"/><circle cx=\"4\" cy=\"6\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"7.5\" cy=\"11.5\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"12\" cy=\"14.5\" r=\"1.6\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"16.5\" cy=\"11.5\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"20\" cy=\"6\" r=\"1.4\" fill=\"currentColor\" stroke=\"none\"/></svg>', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(4, 'Livros', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M12 6c-1.5-1.3-3.6-2-6.5-2-1 0-1.5.3-1.5 1v13c0 .7.5 1 1.5 1 2.9 0 5 .7 6.5 2\"/><path d=\"M12 6c1.5-1.3 3.6-2 6.5-2 1 0 1.5.3 1.5 1v13c0 .7-.5 1-1.5 1-2.9 0-5 .7-6.5 2V6z\"/></svg>', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(5, 'Banhos', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M12 2s7 8.5 7 13a7 7 0 01-14 0c0-4.5 7-13 7-13z\"/></svg>', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(6, 'Velas', '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><rect x=\"9\" y=\"10\" width=\"6\" height=\"11\" rx=\"1\"/><path d=\"M12 10c-1.5-1.8-1.2-3.6 0-5.5C13.2 6.4 13.5 8.2 12 10z\"/></svg>', '2026-08-17 15:53:00', '2026-08-17 15:53:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

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

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `tag`, `price`, `stock`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(5, 'Saia Branca Rendada', 1, 'Ritual', 89.90, 18, 'Saia longa em tecido leve com barra rendada, ideal para gira e rituais de Umbanda.', 'vestuario-saia-branca-rendada.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(6, 'Blusa de Cetim Branca', 1, 'Gira', 69.90, 22, 'Blusa de manga longa em cetim branco, corte tradicional para trabalhos de terreiro.', 'vestuario-blusa-de-cetim-branca.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(7, 'Turbante Branco Amarrado', 1, 'Cabeça', 39.90, 30, 'Turbante em tecido de algodão já modelado, pronto para uso em rituais e giras.', 'vestuario-turbante-branco-amarrado.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(8, 'Pano da Costa Estampado', 1, 'Ombro', 79.90, 15, 'Pano da costa em algodão estampado, usado sobre o ombro em cerimônias de Umbanda.', 'vestuario-pano-da-costa-estampado.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(9, 'Anágua de Balão', 1, 'Rodada', 54.90, 20, 'Anágua rodada em tecido fresco, dá volume às saias rituais.', 'vestuario-anagua-de-balao.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(10, 'Camisa de Alinhavo Masculina', 1, 'Terno', 74.90, 14, 'Camisa branca de linho com alinhavo bordado à mão, uso masculino em cerimônias.', 'vestuario-camisa-de-alinhavo-masculina.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(11, 'Saia Rodada Sete Cores', 1, 'Pombagira', 99.90, 10, 'Saia rodada com fitas nas sete cores, referência às linhas de Pombagira.', 'vestuario-saia-rodada-sete-cores.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(12, 'Blusa Ciganinha Vermelha', 1, 'Cigana', 64.90, 16, 'Blusa ombro a ombro vermelha, modelo cigana usado em giras de Exu e Pombagira.', 'vestuario-blusa-ciganinha-vermelha.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(13, 'Quartzo Rosa Bruto', 2, 'Amor', 24.90, 40, 'Pedra bruta de quartzo rosa, associada ao amor próprio e à harmonia afetiva.', 'pedra-quartzo-rosa-bruto.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(14, 'Ametista Ponta Natural', 2, 'Proteção', 34.90, 35, 'Ponta natural de ametista, usada para proteção espiritual e elevação da energia.', 'pedra-ametista-ponta-natural.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(15, 'Olho de Tigre Rolado', 2, 'Prosperidade', 19.90, 45, 'Pedra rolada de olho de tigre, associada à prosperidade e à firmeza de propósito.', 'pedra-olho-de-tigre-rolado.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(16, 'Quartzo Branco Lapidado', 2, 'Limpeza', 22.90, 38, 'Quartzo branco lapidado, indicado para limpeza e equilíbrio energético do ambiente.', 'pedra-quartzo-branco-lapidado.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(17, 'Turmalina Negra Bruta', 2, 'Aterramento', 29.90, 28, 'Pedra bruta de turmalina negra, usada para aterramento e proteção contra energias densas.', 'pedra-turmalina-negra-bruta.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(18, 'Citrino Ponta Natural', 2, 'Abundância', 32.90, 25, 'Ponta natural de citrino, associada à abundância e à alegria.', 'pedra-citrino-ponta-natural.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(19, 'Ágata Azul Rolada', 2, 'Calma', 21.90, 24, 'Pedra rolada de ágata azul, indicada para trazer calma e clareza mental.', 'pedra-agata-azul-rolada.jpg', '2026-08-17 15:53:00', '2026-09-17 00:10:15'),
(20, 'Obsidiana Negra Polida', 2, 'Blindagem', 26.90, 30, 'Pedra polida de obsidiana negra, usada como blindagem energética.', 'pedra-obsidiana-negra-polida.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(21, 'Guia de Oxalá', 3, 'Paz', 119.90, 12, 'Guia de contas brancas dedicada a Oxalá, símbolo de paz e criação.', 'guia-guia-de-oxala.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(22, 'Guia de Iemanjá', 3, 'Mar', 129.90, 12, 'Guia de contas em tons de azul e cristal, dedicada a Iemanjá, rainha do mar.', 'guia-guia-de-iemanja.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(23, 'Guia de Ogum', 3, 'Vitória', 109.90, 14, 'Guia de contas azul-marinho, dedicada a Ogum, orixá guerreiro.', 'guia-guia-de-ogum.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(24, 'Guia de Oxóssi', 3, 'Mata', 114.90, 13, 'Guia de contas verdes, dedicada a Oxóssi, senhor das matas.', 'guia-guia-de-oxossi.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(25, 'Guia de Xangô', 3, 'Justiça', 124.90, 11, 'Guia de contas marrom e branco, dedicada a Xangô, orixá da justiça.', 'guia-guia-de-xango.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(26, 'Guia de Iansã', 3, 'Ventania', 109.90, 13, 'Guia de contas amarelas e vermelhas, dedicada a Iansã, senhora dos ventos.', 'guia-guia-de-iansa.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(27, 'Guia de Oxum', 3, 'Amor', 119.90, 12, 'Guia de contas douradas, dedicada a Oxum, orixá do amor e das águas doces.', 'guia-guia-de-oxum.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(28, 'Guia de Pombagira', 3, 'Mulher', 99.90, 15, 'Guia de contas coloridas, dedicada às Pombagiras, entidades femininas da Umbanda.', 'guia-guia-de-pombagira.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(29, 'Umbanda Sagrada', 4, 'Fundamentos', 49.90, 20, 'Livro introdutório sobre os fundamentos e a história da Umbanda.', 'livro-umbanda-sagrada.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(30, 'Pontos Cantados na Umbanda', 4, 'Cânticos', 39.90, 24, 'Coletânea de pontos cantados usados em giras e rituais.', 'livro-pontos-cantados-na-umbanda.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(31, 'As Sete Linhas de Umbanda', 4, 'Linhas', 44.90, 18, 'Estudo sobre as sete linhas de trabalho espiritual da Umbanda.', 'livro-as-sete-linhas-de-umbanda.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(32, 'Ervas Sagradas na Umbanda', 4, 'Ervas', 42.90, 19, 'Guia prático sobre o uso ritualístico de ervas na Umbanda.', 'livro-ervas-sagradas-na-umbanda.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(33, 'Orixás — Mitos e Mensagens', 4, 'Mitologia', 54.90, 16, 'Livro sobre a mitologia e as mensagens dos orixás africanos.', 'livro-orixas-mitos-e-mensagens.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(34, 'O Livro dos Médiuns Umbandistas', 4, 'Mediunidade', 47.90, 17, 'Obra dedicada ao desenvolvimento mediúnico dentro da Umbanda.', 'livro-o-livro-dos-mediuns-umbandistas.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(35, 'Exu — O Guardião das Encruzilhadas', 4, 'Exu', 45.90, 20, 'Estudo sobre a figura de Exu e seu papel como guardião espiritual.', 'livro-exu-o-guardiao-das-encruzilhadas.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(36, 'Preto Velho — Sabedoria Ancestral', 4, 'Preto Velho', 41.90, 21, 'Livro sobre a sabedoria e a história dos Pretos Velhos na Umbanda.', 'livro-preto-velho-sabedoria-ancestral.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(37, 'Banho de Arruda', 5, 'Descarrego', 14.90, 50, 'Banho ritual à base de arruda, indicado para descarrego energético.', 'banho-banho-de-arruda.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(38, 'Banho de Alecrim', 5, 'Proteção', 15.90, 43, 'Banho ritual à base de alecrim, indicado para proteção espiritual.', 'banho-banho-de-alecrim.jpg', '2026-08-17 15:53:00', '2026-09-17 01:18:46'),
(39, 'Banho de Rosas Brancas', 5, 'Amor Próprio', 16.90, 40, 'Banho ritual à base de pétalas de rosas brancas, indicado para amor próprio.', 'banho-banho-de-rosas-brancas.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(40, 'Banho de Sete Ervas', 5, 'Abertura de Caminhos', 18.90, 35, 'Banho ritual composto por sete ervas, indicado para abertura de caminhos.', 'banho-banho-de-sete-ervas.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(41, 'Banho de Manjericão', 5, 'Prosperidade', 15.90, 42, 'Banho ritual à base de manjericão, associado à prosperidade.', 'banho-banho-de-manjericao.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(42, 'Banho de Espada de Ogum', 5, 'Força', 17.90, 30, 'Banho ritual à base de espada-de-ogum, indicado para força e coragem.', 'banho-banho-de-espada-de-ogum.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(43, 'Banho de Alfazema', 5, 'Harmonia', 16.90, 38, 'Banho ritual à base de alfazema, indicado para harmonia no lar.', 'banho-banho-de-alfazema.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(44, 'Banho de Folha de Louro', 5, 'Sorte', 14.90, 45, 'Banho ritual à base de folhas de louro, associado à sorte.', 'banho-banho-de-folha-de-louro.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(45, 'Vela Branca 7 Dias', 6, 'Paz', 12.90, 60, 'Vela branca de sete dias, usada para pedidos de paz e harmonia.', 'vela-vela-branca-7-dias.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(46, 'Vela Vermelha', 6, 'Paixão', 8.90, 70, 'Vela vermelha, associada à paixão e à força vital.', 'vela-vela-vermelha.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(47, 'Vela Azul', 6, 'Cura', 8.90, 65, 'Vela azul, associada à cura e à tranquilidade.', 'vela-vela-azul.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(48, 'Vela Amarela', 6, 'Prosperidade', 8.90, 68, 'Vela amarela, associada à prosperidade e à conquista.', 'vela-vela-amarela.jpg', '2026-08-17 15:53:00', '2026-09-16 23:47:20'),
(49, 'Vela Verde', 6, 'Esperança', 8.90, 66, 'Vela verde, associada à esperança e à cura financeira.', 'vela-vela-verde.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(50, 'Vela Roxa', 6, 'Espiritualidade', 9.90, 55, 'Vela roxa, associada à espiritualidade e à elevação.', 'vela-vela-roxa.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(51, 'Vela de Exu Preta e Vermelha', 6, 'Exu', 11.90, 40, 'Vela bicolor preta e vermelha, usada em trabalhos para Exu.', 'vela-vela-de-exu-preta-e-vermelha.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00'),
(52, 'Vela de Pombagira Rosa', 6, 'Pombagira', 11.90, 40, 'Vela rosa, usada em trabalhos e oferendas para Pombagira.', 'vela-vela-de-pombagira-rosa.jpg', '2026-08-17 15:53:00', '2026-08-17 15:53:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sales`
--

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

--
-- Despejando dados para a tabela `sales`
--

INSERT INTO `sales` (`id`, `customer_name`, `discount_amount`, `total_amount`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Maria das Graças Souza', 15.00, 41.99, 'completed', NULL, '2026-08-16 15:53:00', '2026-08-16 15:53:00'),
(2, 'João Pedro Almeida', 0.00, 540.10, 'completed', NULL, '2026-08-15 15:53:00', '2026-08-15 15:53:00'),
(3, 'Fernanda Lima', 0.00, 82.42, 'completed', NULL, '2026-08-12 15:53:00', '2026-08-12 15:53:00'),
(4, 'Carlos Eduardo Santos', 15.00, 323.98, 'completed', NULL, '2026-08-09 15:53:00', '2026-08-09 15:53:00'),
(5, 'Aparecida Ferreira', 0.00, 74.70, 'completed', NULL, '2026-08-05 15:53:00', '2026-08-05 15:53:00'),
(6, 'Ricardo Nascimento', 0.00, 354.92, 'completed', NULL, '2026-08-03 15:53:00', '2026-08-03 15:53:00'),
(7, 'Juliana Costa', 0.00, 193.60, 'cancelled', NULL, '2026-08-02 15:53:00', '2026-08-02 15:53:00'),
(8, 'Antônio Carlos Pereira', 5.00, 409.60, 'completed', NULL, '2026-07-30 15:53:00', '2026-07-30 15:53:00'),
(9, 'Vera Lúcia Rodrigues', 5.00, 98.10, 'completed', NULL, '2026-07-27 15:53:00', '2026-07-27 15:53:00'),
(10, 'Marcos Vinícius Oliveira', 0.00, 372.38, 'completed', NULL, '2026-07-23 15:53:00', '2026-07-23 15:53:00'),
(11, 'Rosana Batista', 15.00, 329.20, 'completed', NULL, '2026-07-19 15:53:00', '2026-07-19 15:53:00'),
(12, 'Sebastião Duarte', 15.00, 20.70, 'completed', NULL, '2026-07-18 15:53:00', '2026-07-18 15:53:00'),
(13, 'Cíntia Barbosa', 0.00, 108.30, 'completed', NULL, '2026-07-17 15:53:00', '2026-07-17 15:53:00'),
(14, 'Eduardo Martins', 0.00, 99.90, 'completed', NULL, '2026-07-16 15:53:00', '2026-07-16 15:53:00'),
(15, 'Neide Ramos', 5.00, 30.70, 'completed', NULL, '2026-07-14 15:53:00', '2026-07-14 15:53:00'),
(16, 'Paulo Henrique Cardoso', 10.00, 476.00, 'completed', NULL, '2026-07-10 15:53:00', '2026-07-10 15:53:00'),
(17, 'Sandra Regina Melo', 0.00, 301.33, 'pending', NULL, '2026-07-06 15:53:00', '2026-08-23 14:32:29'),
(18, 'Wagner Teixeira', 15.00, 60.60, 'cancelled', NULL, '2026-07-02 15:53:00', '2026-07-02 15:53:00'),
(19, 'Venda teste', 5.00, 60.70, 'completed', 1, '2026-08-23 16:22:58', '2026-09-15 12:19:53'),
(20, 'Marcos da Silva', 0.00, 65.70, 'completed', 1, '2026-09-15 12:19:45', '2026-09-17 00:10:15'),
(21, 'Clara Menezes', 0.00, 44.50, 'cancelled', 1, '2026-09-15 12:20:31', '2026-09-16 23:47:20'),
(22, 'Renan', 0.00, 131.40, 'cancelled', 1, '2026-09-16 23:46:03', '2026-09-16 23:46:58'),
(23, 'Helena Vieira', 0.00, 79.50, 'completed', 1, '2026-09-17 01:18:46', '2026-09-17 01:18:46');

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Estrutura para tabela `sale_items`
--

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

--
-- Dados de teste para tabela `sale_items`
--

INSERT INTO `sale_items`
(`id`, `sale_id`, `product_id`, `product_name`, `original_price`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 20, 19, 'Ágata Azul Rolada', 21.90, 21.90, 3, 65.70, '2026-09-15 12:19:45', '2026-09-17 00:10:15'),
(2, 23, 38, 'Banho de Alecrim', 15.90, 15.90, 5, 79.50, '2026-09-17 01:18:46', '2026-09-17 01:18:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sale_item_changes`
--

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

--
-- Dados de teste para tabela `sale_item_changes`
--

INSERT INTO `sale_item_changes`
(`id`, `sale_id`, `sale_item_id`, `product_id`, `product_name`, `action`,
 `old_quantity`, `new_quantity`, `old_unit_price`, `new_unit_price`,
 `user_id`, `created_at`) VALUES
(1, 20, 1, 19, 'Ágata Azul Rolada', 'created', NULL, 2, NULL, 21.90, 1, '2026-09-15 12:19:45'),
(2, 20, 1, 19, 'Ágata Azul Rolada', 'updated', 2, 3, 21.90, 21.90, 1, '2026-09-17 00:10:15'),
(3, 23, 2, 38, 'Banho de Alecrim', 'created', NULL, 5, NULL, 15.90, 1, '2026-09-17 01:18:46'),
(4, 21, 3, 48, 'Vela Amarela', 'created', NULL, 5, NULL, 8.90, 1, '2026-09-15 12:20:31'),
(5, 21, 3, 48, 'Vela Amarela', 'removed', 5, NULL, 8.90, NULL, 1, '2026-09-16 23:47:20');

--
-- Estrutura para tabela `users`
--

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

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `is_active`, `last_ip`, `created_at`, `updated_at`) VALUES
(1, 'Renan Gonçalves', 'admin', 'rsgnan@proton.me', '$2y$10$5SADRdU961B7gcUhhFAHTu3SVyeBy8t4gjjGTZk.xHfGVtGk1NqNa', 'admin', 1, NULL, '2026-08-16 15:59:40', '2026-08-26 16:53:42');



-- --------------------------------------------------------
-- Massa adicional de dados de teste
-- --------------------------------------------------------

INSERT INTO `products` (`id`, `name`, `category_id`, `tag`, `price`, `stock`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(53, 'Vela Branca 001', 3, 'TESTE-53', 94.70, 113, 'Produto de teste gerado para desenvolvimento.', 'product-53.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(54, 'Vela Azul 002', 1, 'TESTE-54', 43.37, 60, 'Produto de teste gerado para desenvolvimento.', 'product-54.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(55, 'Vela Verde 003', 5, 'TESTE-55', 26.49, 77, 'Produto de teste gerado para desenvolvimento.', 'product-55.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(56, 'Vela Vermelha 004', 5, 'TESTE-56', 53.17, 99, 'Produto de teste gerado para desenvolvimento.', 'product-56.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(57, 'Vela Roxa 005', 4, 'TESTE-57', 35.97, 57, 'Produto de teste gerado para desenvolvimento.', 'product-57.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(58, 'Incenso de Alecrim 006', 1, 'TESTE-58', 135.14, 80, 'Produto de teste gerado para desenvolvimento.', 'product-58.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(59, 'Incenso de Arruda 007', 2, 'TESTE-59', 59.71, 35, 'Produto de teste gerado para desenvolvimento.', 'product-59.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(60, 'Incenso de Lavanda 008', 3, 'TESTE-60', 125.31, 45, 'Produto de teste gerado para desenvolvimento.', 'product-60.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(61, 'Incenso de Canela 009', 4, 'TESTE-61', 120.20, 33, 'Produto de teste gerado para desenvolvimento.', 'product-61.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(62, 'Banho de Guiné 010', 5, 'TESTE-62', 110.97, 93, 'Produto de teste gerado para desenvolvimento.', 'product-62.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(63, 'Banho de Arruda 011', 3, 'TESTE-63', 81.86, 108, 'Produto de teste gerado para desenvolvimento.', 'product-63.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(64, 'Banho de Manjericão 012', 4, 'TESTE-64', 42.51, 55, 'Produto de teste gerado para desenvolvimento.', 'product-64.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(65, 'Banho de Alfazema 013', 1, 'TESTE-65', 73.14, 55, 'Produto de teste gerado para desenvolvimento.', 'product-65.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(66, 'Quartzo Branco 014', 2, 'TESTE-66', 61.05, 60, 'Produto de teste gerado para desenvolvimento.', 'product-66.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(67, 'Quartzo Rosa 015', 6, 'TESTE-67', 51.20, 69, 'Produto de teste gerado para desenvolvimento.', 'product-67.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(68, 'Ametista 016', 1, 'TESTE-68', 21.47, 93, 'Produto de teste gerado para desenvolvimento.', 'product-68.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(69, 'Olho de Tigre 017', 1, 'TESTE-69', 20.65, 65, 'Produto de teste gerado para desenvolvimento.', 'product-69.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(70, 'Turmalina Negra 018', 5, 'TESTE-70', 85.04, 42, 'Produto de teste gerado para desenvolvimento.', 'product-70.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(71, 'Jaspe Vermelho 019', 2, 'TESTE-71', 35.17, 90, 'Produto de teste gerado para desenvolvimento.', 'product-71.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(72, 'Citrino 020', 1, 'TESTE-72', 105.14, 65, 'Produto de teste gerado para desenvolvimento.', 'product-72.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(73, 'Ágata Verde 021', 1, 'TESTE-73', 49.26, 44, 'Produto de teste gerado para desenvolvimento.', 'product-73.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(74, 'Pedra da Lua 022', 1, 'TESTE-74', 28.86, 35, 'Produto de teste gerado para desenvolvimento.', 'product-74.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(75, 'Sodalita 023', 2, 'TESTE-75', 77.95, 38, 'Produto de teste gerado para desenvolvimento.', 'product-75.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(76, 'Guia Branca 024', 5, 'TESTE-76', 36.78, 42, 'Produto de teste gerado para desenvolvimento.', 'product-76.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(77, 'Guia Azul 025', 5, 'TESTE-77', 59.98, 87, 'Produto de teste gerado para desenvolvimento.', 'product-77.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(78, 'Guia Verde 026', 5, 'TESTE-78', 80.45, 100, 'Produto de teste gerado para desenvolvimento.', 'product-78.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(79, 'Guia Vermelha 027', 4, 'TESTE-79', 13.37, 80, 'Produto de teste gerado para desenvolvimento.', 'product-79.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(80, 'Guia Preta 028', 2, 'TESTE-80', 26.22, 65, 'Produto de teste gerado para desenvolvimento.', 'product-80.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(81, 'Defumador de Alecrim 029', 2, 'TESTE-81', 73.37, 89, 'Produto de teste gerado para desenvolvimento.', 'product-81.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(82, 'Defumador de Arruda 030', 6, 'TESTE-82', 99.34, 78, 'Produto de teste gerado para desenvolvimento.', 'product-82.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(83, 'Defumador de Benjoim 031', 5, 'TESTE-83', 129.64, 48, 'Produto de teste gerado para desenvolvimento.', 'product-83.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(84, 'Erva Alecrim 032', 1, 'TESTE-84', 57.96, 33, 'Produto de teste gerado para desenvolvimento.', 'product-84.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(85, 'Erva Arruda 033', 2, 'TESTE-85', 50.22, 103, 'Produto de teste gerado para desenvolvimento.', 'product-85.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(86, 'Erva Guiné 034', 3, 'TESTE-86', 121.21, 65, 'Produto de teste gerado para desenvolvimento.', 'product-86.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(87, 'Erva Manjericão 035', 5, 'TESTE-87', 50.08, 43, 'Produto de teste gerado para desenvolvimento.', 'product-87.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(88, 'Erva Alfazema 036', 5, 'TESTE-88', 67.29, 63, 'Produto de teste gerado para desenvolvimento.', 'product-88.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(89, 'Pano de Cabeça Branco 037', 5, 'TESTE-89', 78.96, 111, 'Produto de teste gerado para desenvolvimento.', 'product-89.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(90, 'Pano de Cabeça Vermelho 038', 6, 'TESTE-90', 31.49, 64, 'Produto de teste gerado para desenvolvimento.', 'product-90.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(91, 'Pano de Cabeça Azul 039', 5, 'TESTE-91', 112.26, 70, 'Produto de teste gerado para desenvolvimento.', 'product-91.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(92, 'Porta Guia Branco 040', 2, 'TESTE-92', 123.34, 67, 'Produto de teste gerado para desenvolvimento.', 'product-92.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(93, 'Porta Guia Preto 041', 6, 'TESTE-93', 61.76, 92, 'Produto de teste gerado para desenvolvimento.', 'product-93.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(94, 'Porta Guia Vermelho 042', 5, 'TESTE-94', 105.90, 60, 'Produto de teste gerado para desenvolvimento.', 'product-94.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(95, 'Saia Branca 043', 1, 'TESTE-95', 79.30, 117, 'Produto de teste gerado para desenvolvimento.', 'product-95.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(96, 'Saia Preta 044', 4, 'TESTE-96', 133.21, 55, 'Produto de teste gerado para desenvolvimento.', 'product-96.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(97, 'Saia Vermelha 045', 2, 'TESTE-97', 66.18, 38, 'Produto de teste gerado para desenvolvimento.', 'product-97.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(98, 'Calça Branca 046', 2, 'TESTE-98', 112.44, 69, 'Produto de teste gerado para desenvolvimento.', 'product-98.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(99, 'Camiseta Branca 047', 6, 'TESTE-99', 21.39, 102, 'Produto de teste gerado para desenvolvimento.', 'product-99.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(100, 'Camiseta Preta 048', 5, 'TESTE-100', 118.37, 88, 'Produto de teste gerado para desenvolvimento.', 'product-100.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(101, 'Fio de Contas Branco 049', 1, 'TESTE-101', 65.87, 48, 'Produto de teste gerado para desenvolvimento.', 'product-101.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(102, 'Fio de Contas Azul 050', 4, 'TESTE-102', 57.56, 45, 'Produto de teste gerado para desenvolvimento.', 'product-102.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(103, 'Vela Branca 051', 2, 'TESTE-103', 86.79, 30, 'Produto de teste gerado para desenvolvimento.', 'product-103.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(104, 'Vela Azul 052', 3, 'TESTE-104', 6.21, 82, 'Produto de teste gerado para desenvolvimento.', 'product-104.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(105, 'Vela Verde 053', 6, 'TESTE-105', 83.47, 31, 'Produto de teste gerado para desenvolvimento.', 'product-105.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(106, 'Vela Vermelha 054', 1, 'TESTE-106', 19.75, 99, 'Produto de teste gerado para desenvolvimento.', 'product-106.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(107, 'Vela Roxa 055', 6, 'TESTE-107', 147.58, 117, 'Produto de teste gerado para desenvolvimento.', 'product-107.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(108, 'Incenso de Alecrim 056', 1, 'TESTE-108', 115.91, 66, 'Produto de teste gerado para desenvolvimento.', 'product-108.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(109, 'Incenso de Arruda 057', 1, 'TESTE-109', 39.84, 92, 'Produto de teste gerado para desenvolvimento.', 'product-109.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(110, 'Incenso de Lavanda 058', 4, 'TESTE-110', 114.60, 107, 'Produto de teste gerado para desenvolvimento.', 'product-110.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(111, 'Incenso de Canela 059', 2, 'TESTE-111', 32.94, 101, 'Produto de teste gerado para desenvolvimento.', 'product-111.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(112, 'Banho de Guiné 060', 4, 'TESTE-112', 56.65, 97, 'Produto de teste gerado para desenvolvimento.', 'product-112.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(113, 'Banho de Arruda 061', 4, 'TESTE-113', 48.35, 120, 'Produto de teste gerado para desenvolvimento.', 'product-113.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(114, 'Banho de Manjericão 062', 5, 'TESTE-114', 104.81, 37, 'Produto de teste gerado para desenvolvimento.', 'product-114.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(115, 'Banho de Alfazema 063', 6, 'TESTE-115', 131.01, 86, 'Produto de teste gerado para desenvolvimento.', 'product-115.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(116, 'Quartzo Branco 064', 5, 'TESTE-116', 18.33, 99, 'Produto de teste gerado para desenvolvimento.', 'product-116.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(117, 'Quartzo Rosa 065', 3, 'TESTE-117', 73.76, 88, 'Produto de teste gerado para desenvolvimento.', 'product-117.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(118, 'Ametista 066', 2, 'TESTE-118', 9.86, 101, 'Produto de teste gerado para desenvolvimento.', 'product-118.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(119, 'Olho de Tigre 067', 6, 'TESTE-119', 40.03, 105, 'Produto de teste gerado para desenvolvimento.', 'product-119.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(120, 'Turmalina Negra 068', 1, 'TESTE-120', 38.64, 111, 'Produto de teste gerado para desenvolvimento.', 'product-120.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(121, 'Jaspe Vermelho 069', 2, 'TESTE-121', 23.03, 119, 'Produto de teste gerado para desenvolvimento.', 'product-121.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(122, 'Citrino 070', 2, 'TESTE-122', 85.28, 42, 'Produto de teste gerado para desenvolvimento.', 'product-122.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(123, 'Ágata Verde 071', 1, 'TESTE-123', 61.77, 83, 'Produto de teste gerado para desenvolvimento.', 'product-123.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(124, 'Pedra da Lua 072', 5, 'TESTE-124', 38.02, 96, 'Produto de teste gerado para desenvolvimento.', 'product-124.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(125, 'Sodalita 073', 4, 'TESTE-125', 42.59, 90, 'Produto de teste gerado para desenvolvimento.', 'product-125.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(126, 'Guia Branca 074', 4, 'TESTE-126', 135.17, 67, 'Produto de teste gerado para desenvolvimento.', 'product-126.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(127, 'Guia Azul 075', 4, 'TESTE-127', 80.91, 57, 'Produto de teste gerado para desenvolvimento.', 'product-127.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(128, 'Guia Verde 076', 4, 'TESTE-128', 133.87, 52, 'Produto de teste gerado para desenvolvimento.', 'product-128.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(129, 'Guia Vermelha 077', 3, 'TESTE-129', 95.49, 93, 'Produto de teste gerado para desenvolvimento.', 'product-129.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(130, 'Guia Preta 078', 1, 'TESTE-130', 11.09, 40, 'Produto de teste gerado para desenvolvimento.', 'product-130.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(131, 'Defumador de Alecrim 079', 4, 'TESTE-131', 68.65, 68, 'Produto de teste gerado para desenvolvimento.', 'product-131.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(132, 'Defumador de Arruda 080', 4, 'TESTE-132', 72.52, 33, 'Produto de teste gerado para desenvolvimento.', 'product-132.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(133, 'Defumador de Benjoim 081', 3, 'TESTE-133', 129.33, 70, 'Produto de teste gerado para desenvolvimento.', 'product-133.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(134, 'Erva Alecrim 082', 6, 'TESTE-134', 34.39, 64, 'Produto de teste gerado para desenvolvimento.', 'product-134.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(135, 'Erva Arruda 083', 2, 'TESTE-135', 132.64, 77, 'Produto de teste gerado para desenvolvimento.', 'product-135.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(136, 'Erva Guiné 084', 5, 'TESTE-136', 64.18, 117, 'Produto de teste gerado para desenvolvimento.', 'product-136.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(137, 'Erva Manjericão 085', 5, 'TESTE-137', 134.37, 34, 'Produto de teste gerado para desenvolvimento.', 'product-137.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(138, 'Erva Alfazema 086', 6, 'TESTE-138', 39.61, 47, 'Produto de teste gerado para desenvolvimento.', 'product-138.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(139, 'Pano de Cabeça Branco 087', 1, 'TESTE-139', 123.09, 101, 'Produto de teste gerado para desenvolvimento.', 'product-139.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(140, 'Pano de Cabeça Vermelho 088', 3, 'TESTE-140', 9.20, 50, 'Produto de teste gerado para desenvolvimento.', 'product-140.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(141, 'Pano de Cabeça Azul 089', 1, 'TESTE-141', 70.94, 41, 'Produto de teste gerado para desenvolvimento.', 'product-141.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(142, 'Porta Guia Branco 090', 5, 'TESTE-142', 18.87, 52, 'Produto de teste gerado para desenvolvimento.', 'product-142.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(143, 'Porta Guia Preto 091', 5, 'TESTE-143', 126.21, 82, 'Produto de teste gerado para desenvolvimento.', 'product-143.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(144, 'Porta Guia Vermelho 092', 3, 'TESTE-144', 136.06, 93, 'Produto de teste gerado para desenvolvimento.', 'product-144.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(145, 'Saia Branca 093', 1, 'TESTE-145', 82.06, 84, 'Produto de teste gerado para desenvolvimento.', 'product-145.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(146, 'Saia Preta 094', 2, 'TESTE-146', 99.35, 75, 'Produto de teste gerado para desenvolvimento.', 'product-146.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(147, 'Saia Vermelha 095', 5, 'TESTE-147', 60.91, 57, 'Produto de teste gerado para desenvolvimento.', 'product-147.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(148, 'Calça Branca 096', 4, 'TESTE-148', 80.98, 103, 'Produto de teste gerado para desenvolvimento.', 'product-148.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(149, 'Camiseta Branca 097', 1, 'TESTE-149', 121.99, 32, 'Produto de teste gerado para desenvolvimento.', 'product-149.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(150, 'Camiseta Preta 098', 6, 'TESTE-150', 111.60, 61, 'Produto de teste gerado para desenvolvimento.', 'product-150.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(151, 'Fio de Contas Branco 099', 4, 'TESTE-151', 15.46, 108, 'Produto de teste gerado para desenvolvimento.', 'product-151.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00'),
(152, 'Fio de Contas Azul 100', 4, 'TESTE-152', 128.20, 102, 'Produto de teste gerado para desenvolvimento.', 'product-152.jpg', '2026-09-17 18:00:00', '2026-09-17 18:00:00');

INSERT INTO `sales` (`id`, `customer_name`, `discount_amount`, `total_amount`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(24, 'Cliente Teste 001', 0.00, 648.20, 'pending', 1, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(25, 'Cliente Teste 002', 0.00, 1685.71, 'pending', 1, '2026-06-02 03:00:00', '2026-06-02 03:00:00'),
(26, 'Cliente Teste 003', 0.00, 1117.29, 'pending', 1, '2026-06-02 21:00:00', '2026-06-02 21:00:00'),
(27, 'Cliente Teste 004', 15.00, 706.53, 'pending', 1, '2026-06-03 15:00:00', '2026-06-03 15:00:00'),
(28, 'Cliente Teste 005', 10.00, 615.51, 'pending', 1, '2026-06-04 09:00:00', '2026-06-04 09:00:00'),
(29, 'Cliente Teste 006', 0.00, 1456.80, 'pending', 1, '2026-06-05 03:00:00', '2026-06-05 03:00:00'),
(30, 'Cliente Teste 007', 15.00, 1096.67, 'pending', 1, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(31, 'Cliente Teste 008', 0.00, 268.74, 'completed', 1, '2026-06-06 15:00:00', '2026-06-06 15:00:00'),
(32, 'Cliente Teste 009', 15.00, 1252.35, 'pending', 1, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(33, 'Cliente Teste 010', 5.00, 342.73, 'completed', 1, '2026-06-08 03:00:00', '2026-06-08 03:00:00'),
(34, 'Cliente Teste 011', 0.00, 588.49, 'pending', 1, '2026-06-08 21:00:00', '2026-06-08 21:00:00'),
(35, 'Cliente Teste 012', 0.00, 468.26, 'pending', 1, '2026-06-09 15:00:00', '2026-06-09 15:00:00'),
(36, 'Cliente Teste 013', 5.00, 1127.43, 'pending', 1, '2026-06-10 09:00:00', '2026-06-10 09:00:00'),
(37, 'Cliente Teste 014', 0.00, 579.55, 'completed', 1, '2026-06-11 03:00:00', '2026-06-11 03:00:00'),
(38, 'Cliente Teste 015', 10.00, 927.45, 'completed', 1, '2026-06-11 21:00:00', '2026-06-11 21:00:00'),
(39, 'Cliente Teste 016', 5.00, 110.44, 'pending', 1, '2026-06-12 15:00:00', '2026-06-12 15:00:00'),
(40, 'Cliente Teste 017', 0.00, 1204.57, 'pending', 1, '2026-06-13 09:00:00', '2026-06-13 09:00:00'),
(41, 'Cliente Teste 018', 10.00, 19.58, 'cancelled', 1, '2026-06-14 03:00:00', '2026-06-14 03:00:00'),
(42, 'Cliente Teste 019', 5.00, 250.84, 'pending', 1, '2026-06-14 21:00:00', '2026-06-14 21:00:00'),
(43, 'Cliente Teste 020', 15.00, 296.75, 'completed', 1, '2026-06-15 15:00:00', '2026-06-15 15:00:00'),
(44, 'Cliente Teste 021', 0.00, 268.74, 'completed', 1, '2026-06-16 09:00:00', '2026-06-16 09:00:00'),
(45, 'Cliente Teste 022', 0.00, 731.03, 'pending', 1, '2026-06-17 03:00:00', '2026-06-17 03:00:00'),
(46, 'Cliente Teste 023', 15.00, 105.60, 'pending', 1, '2026-06-17 21:00:00', '2026-06-17 21:00:00'),
(47, 'Cliente Teste 024', 10.00, 594.00, 'completed', 1, '2026-06-18 15:00:00', '2026-06-18 15:00:00'),
(48, 'Cliente Teste 025', 0.00, 1452.31, 'completed', 1, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(49, 'Cliente Teste 026', 0.00, 646.65, 'completed', 1, '2026-06-20 03:00:00', '2026-06-20 03:00:00'),
(50, 'Cliente Teste 027', 0.00, 1135.59, 'pending', 1, '2026-06-20 21:00:00', '2026-06-20 21:00:00'),
(51, 'Cliente Teste 028', 15.00, 258.68, 'pending', 1, '2026-06-21 15:00:00', '2026-06-21 15:00:00'),
(52, 'Cliente Teste 029', 0.00, 916.84, 'pending', 1, '2026-06-22 09:00:00', '2026-06-22 09:00:00'),
(53, 'Cliente Teste 030', 15.00, 665.73, 'completed', 1, '2026-06-23 03:00:00', '2026-06-23 03:00:00'),
(54, 'Cliente Teste 031', 10.00, 1341.60, 'pending', 1, '2026-06-23 21:00:00', '2026-06-23 21:00:00'),
(55, 'Cliente Teste 032', 0.00, 559.50, 'pending', 1, '2026-06-24 15:00:00', '2026-06-24 15:00:00'),
(56, 'Cliente Teste 033', 10.00, 822.74, 'cancelled', 1, '2026-06-25 09:00:00', '2026-06-25 09:00:00'),
(57, 'Cliente Teste 034', 10.00, 560.93, 'completed', 1, '2026-06-26 03:00:00', '2026-06-26 03:00:00'),
(58, 'Cliente Teste 035', 15.00, 929.13, 'completed', 1, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(59, 'Cliente Teste 036', 0.00, 1066.72, 'completed', 1, '2026-06-27 15:00:00', '2026-06-27 15:00:00'),
(60, 'Cliente Teste 037', 15.00, 189.48, 'pending', 1, '2026-06-28 09:00:00', '2026-06-28 09:00:00'),
(61, 'Cliente Teste 038', 5.00, 224.20, 'pending', 1, '2026-06-29 03:00:00', '2026-06-29 03:00:00'),
(62, 'Cliente Teste 039', 0.00, 946.92, 'pending', 1, '2026-06-29 21:00:00', '2026-06-29 21:00:00'),
(63, 'Cliente Teste 040', 0.00, 517.09, 'pending', 1, '2026-06-30 15:00:00', '2026-06-30 15:00:00'),
(64, 'Cliente Teste 041', 10.00, 252.22, 'pending', 1, '2026-07-01 09:00:00', '2026-07-01 09:00:00'),
(65, 'Cliente Teste 042', 0.00, 603.66, 'completed', 1, '2026-07-02 03:00:00', '2026-07-02 03:00:00'),
(66, 'Cliente Teste 043', 15.00, 384.63, 'cancelled', 1, '2026-07-02 21:00:00', '2026-07-02 21:00:00'),
(67, 'Cliente Teste 044', 0.00, 199.20, 'pending', 1, '2026-07-03 15:00:00', '2026-07-03 15:00:00'),
(68, 'Cliente Teste 045', 5.00, 300.50, 'cancelled', 1, '2026-07-04 09:00:00', '2026-07-04 09:00:00'),
(69, 'Cliente Teste 046', 0.00, 995.25, 'pending', 1, '2026-07-05 03:00:00', '2026-07-05 03:00:00'),
(70, 'Cliente Teste 047', 0.00, 990.80, 'completed', 1, '2026-07-05 21:00:00', '2026-07-05 21:00:00'),
(71, 'Cliente Teste 048', 15.00, 999.73, 'pending', 1, '2026-07-06 15:00:00', '2026-07-06 15:00:00'),
(72, 'Cliente Teste 049', 0.00, 1350.69, 'pending', 1, '2026-07-07 09:00:00', '2026-07-07 09:00:00'),
(73, 'Cliente Teste 050', 5.00, 701.77, 'completed', 1, '2026-07-08 03:00:00', '2026-07-08 03:00:00'),
(74, 'Cliente Teste 051', 0.00, 113.30, 'pending', 1, '2026-07-08 21:00:00', '2026-07-08 21:00:00'),
(75, 'Cliente Teste 052', 0.00, 756.85, 'pending', 1, '2026-07-09 15:00:00', '2026-07-09 15:00:00'),
(76, 'Cliente Teste 053', 0.00, 1218.27, 'cancelled', 1, '2026-07-10 09:00:00', '2026-07-10 09:00:00'),
(77, 'Cliente Teste 054', 10.00, 210.68, 'pending', 1, '2026-07-11 03:00:00', '2026-07-11 03:00:00'),
(78, 'Cliente Teste 055', 0.00, 580.76, 'cancelled', 1, '2026-07-11 21:00:00', '2026-07-11 21:00:00'),
(79, 'Cliente Teste 056', 15.00, 1152.76, 'pending', 1, '2026-07-12 15:00:00', '2026-07-12 15:00:00'),
(80, 'Cliente Teste 057', 0.00, 866.85, 'pending', 1, '2026-07-13 09:00:00', '2026-07-13 09:00:00'),
(81, 'Cliente Teste 058', 15.00, 2089.15, 'pending', 1, '2026-07-14 03:00:00', '2026-07-14 03:00:00'),
(82, 'Cliente Teste 059', 5.00, 1174.92, 'completed', 1, '2026-07-14 21:00:00', '2026-07-14 21:00:00'),
(83, 'Cliente Teste 060', 15.00, 592.83, 'pending', 1, '2026-07-15 15:00:00', '2026-07-15 15:00:00'),
(84, 'Cliente Teste 061', 15.00, 330.08, 'completed', 1, '2026-07-16 09:00:00', '2026-07-16 09:00:00'),
(85, 'Cliente Teste 062', 15.00, 1335.88, 'pending', 1, '2026-07-17 03:00:00', '2026-07-17 03:00:00'),
(86, 'Cliente Teste 063', 0.00, 411.98, 'pending', 1, '2026-07-17 21:00:00', '2026-07-17 21:00:00'),
(87, 'Cliente Teste 064', 0.00, 341.12, 'completed', 1, '2026-07-18 15:00:00', '2026-07-18 15:00:00'),
(88, 'Cliente Teste 065', 0.00, 1150.20, 'pending', 1, '2026-07-19 09:00:00', '2026-07-19 09:00:00'),
(89, 'Cliente Teste 066', 0.00, 563.80, 'pending', 1, '2026-07-20 03:00:00', '2026-07-20 03:00:00'),
(90, 'Cliente Teste 067', 15.00, 71.58, 'completed', 1, '2026-07-20 21:00:00', '2026-07-20 21:00:00'),
(91, 'Cliente Teste 068', 0.00, 167.33, 'completed', 1, '2026-07-21 15:00:00', '2026-07-21 15:00:00'),
(92, 'Cliente Teste 069', 0.00, 172.65, 'completed', 1, '2026-07-22 09:00:00', '2026-07-22 09:00:00'),
(93, 'Cliente Teste 070', 0.00, 493.36, 'pending', 1, '2026-07-23 03:00:00', '2026-07-23 03:00:00'),
(94, 'Cliente Teste 071', 0.00, 237.90, 'completed', 1, '2026-07-23 21:00:00', '2026-07-23 21:00:00'),
(95, 'Cliente Teste 072', 15.00, 718.07, 'pending', 1, '2026-07-24 15:00:00', '2026-07-24 15:00:00'),
(96, 'Cliente Teste 073', 10.00, 886.10, 'pending', 1, '2026-07-25 09:00:00', '2026-07-25 09:00:00'),
(97, 'Cliente Teste 074', 0.00, 271.95, 'completed', 1, '2026-07-26 03:00:00', '2026-07-26 03:00:00'),
(98, 'Cliente Teste 075', 5.00, 573.29, 'pending', 1, '2026-07-26 21:00:00', '2026-07-26 21:00:00'),
(99, 'Cliente Teste 076', 0.00, 736.49, 'completed', 1, '2026-07-27 15:00:00', '2026-07-27 15:00:00'),
(100, 'Cliente Teste 077', 15.00, 868.20, 'completed', 1, '2026-07-28 09:00:00', '2026-07-28 09:00:00'),
(101, 'Cliente Teste 078', 10.00, 68.96, 'pending', 1, '2026-07-29 03:00:00', '2026-07-29 03:00:00'),
(102, 'Cliente Teste 079', 0.00, 179.85, 'completed', 1, '2026-07-29 21:00:00', '2026-07-29 21:00:00'),
(103, 'Cliente Teste 080', 5.00, 487.91, 'completed', 1, '2026-07-30 15:00:00', '2026-07-30 15:00:00'),
(104, 'Cliente Teste 081', 0.00, 761.07, 'pending', 1, '2026-07-31 09:00:00', '2026-07-31 09:00:00'),
(105, 'Cliente Teste 082', 0.00, 1139.15, 'completed', 1, '2026-08-01 03:00:00', '2026-08-01 03:00:00'),
(106, 'Cliente Teste 083', 10.00, 242.59, 'pending', 1, '2026-08-01 21:00:00', '2026-08-01 21:00:00'),
(107, 'Cliente Teste 084', 5.00, 778.94, 'pending', 1, '2026-08-02 15:00:00', '2026-08-02 15:00:00'),
(108, 'Cliente Teste 085', 0.00, 82.06, 'pending', 1, '2026-08-03 09:00:00', '2026-08-03 09:00:00'),
(109, 'Cliente Teste 086', 10.00, 908.81, 'pending', 1, '2026-08-04 03:00:00', '2026-08-04 03:00:00'),
(110, 'Cliente Teste 087', 0.00, 675.87, 'completed', 1, '2026-08-04 21:00:00', '2026-08-04 21:00:00'),
(111, 'Cliente Teste 088', 15.00, 1053.87, 'cancelled', 1, '2026-08-05 15:00:00', '2026-08-05 15:00:00'),
(112, 'Cliente Teste 089', 5.00, 850.15, 'cancelled', 1, '2026-08-06 09:00:00', '2026-08-06 09:00:00'),
(113, 'Cliente Teste 090', 5.00, 1452.81, 'pending', 1, '2026-08-07 03:00:00', '2026-08-07 03:00:00'),
(114, 'Cliente Teste 091', 0.00, 231.82, 'pending', 1, '2026-08-07 21:00:00', '2026-08-07 21:00:00'),
(115, 'Cliente Teste 092', 0.00, 918.82, 'cancelled', 1, '2026-08-08 15:00:00', '2026-08-08 15:00:00'),
(116, 'Cliente Teste 093', 0.00, 679.20, 'pending', 1, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(117, 'Cliente Teste 094', 10.00, 18.86, 'completed', 1, '2026-08-10 03:00:00', '2026-08-10 03:00:00'),
(118, 'Cliente Teste 095', 0.00, 80.45, 'completed', 1, '2026-08-10 21:00:00', '2026-08-10 21:00:00'),
(119, 'Cliente Teste 096', 5.00, 182.30, 'completed', 1, '2026-08-11 15:00:00', '2026-08-11 15:00:00'),
(120, 'Cliente Teste 097', 0.00, 127.53, 'pending', 1, '2026-08-12 09:00:00', '2026-08-12 09:00:00'),
(121, 'Cliente Teste 098', 0.00, 75.48, 'completed', 1, '2026-08-13 03:00:00', '2026-08-13 03:00:00'),
(122, 'Cliente Teste 099', 5.00, 359.00, 'pending', 1, '2026-08-13 21:00:00', '2026-08-13 21:00:00'),
(123, 'Cliente Teste 100', 5.00, 81.79, 'pending', 1, '2026-08-14 15:00:00', '2026-08-14 15:00:00');

INSERT INTO `sale_items`
(`id`, `sale_id`, `product_id`, `product_name`, `original_price`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(3, 24, 83, 'Defumador de Benjoim 031', 129.64, 129.64, 5, 648.20, '2026-06-01 09:00:00', '2026-06-01 09:00:00'),
(4, 25, 117, 'Quartzo Rosa 065', 73.76, 73.76, 3, 221.28, '2026-06-02 03:00:00', '2026-06-02 03:00:00'),
(5, 25, 139, 'Pano de Cabeça Branco 087', 123.09, 123.09, 1, 123.09, '2026-06-02 03:00:00', '2026-06-02 03:30:00'),
(6, 25, 115, 'Banho de Alfazema 063', 131.01, 131.01, 2, 262.02, '2026-06-02 03:00:00', '2026-06-02 03:30:00'),
(7, 25, 101, 'Fio de Contas Branco 049', 65.87, 65.87, 3, 197.61, '2026-06-02 03:00:00', '2026-06-02 03:30:00'),
(8, 25, 93, 'Porta Guia Preto 041', 61.76, 61.76, 6, 370.56, '2026-06-02 03:00:00', '2026-06-02 03:30:00'),
(9, 26, 61, 'Incenso de Canela 009', 120.20, 120.20, 4, 480.80, '2026-06-02 21:00:00', '2026-06-02 21:00:00'),
(10, 26, 114, 'Banho de Manjericão 062', 104.81, 104.81, 5, 524.05, '2026-06-02 21:00:00', '2026-06-02 21:00:00'),
(11, 26, 98, 'Calça Branca 046', 112.44, 112.44, 5, 562.20, '2026-06-02 21:00:00', '2026-06-02 21:30:00'),
(12, 27, 67, 'Quartzo Rosa 015', 51.20, 51.20, 2, 102.40, '2026-06-03 15:00:00', '2026-06-03 15:00:00'),
(13, 27, 124, 'Pedra da Lua 072', 38.02, 38.02, 5, 190.10, '2026-06-03 15:00:00', '2026-06-03 15:00:00'),
(14, 27, 79, 'Guia Vermelha 027', 13.37, 13.37, 3, 40.11, '2026-06-03 15:00:00', '2026-06-03 15:00:00'),
(15, 27, 83, 'Defumador de Benjoim 031', 129.64, 129.64, 3, 388.92, '2026-06-03 15:00:00', '2026-06-03 15:00:00'),
(16, 28, 128, 'Guia Verde 076', 133.87, 133.87, 2, 267.74, '2026-06-04 09:00:00', '2026-06-04 09:00:00'),
(17, 28, 102, 'Fio de Contas Azul 050', 57.56, 57.56, 6, 345.36, '2026-06-04 09:00:00', '2026-06-04 09:30:00'),
(18, 28, 64, 'Banho de Manjericão 012', 42.51, 42.51, 3, 127.53, '2026-06-04 09:00:00', '2026-06-04 09:00:00'),
(19, 29, 137, 'Erva Manjericão 085', 134.37, 134.37, 3, 403.11, '2026-06-05 03:00:00', '2026-06-05 03:00:00'),
(20, 29, 62, 'Banho de Guiné 010', 110.97, 110.97, 2, 221.94, '2026-06-05 03:00:00', '2026-06-05 03:00:00'),
(21, 29, 133, 'Defumador de Benjoim 081', 129.33, 129.33, 4, 517.32, '2026-06-05 03:00:00', '2026-06-05 03:00:00'),
(22, 29, 114, 'Banho de Manjericão 062', 104.81, 104.81, 3, 314.43, '2026-06-05 03:00:00', '2026-06-05 03:00:00'),
(23, 30, 136, 'Erva Guiné 084', 64.18, 64.18, 4, 256.72, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(24, 30, 59, 'Incenso de Arruda 007', 59.71, 59.71, 5, 298.55, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(25, 30, 105, 'Vela Verde 053', 83.47, 83.47, 2, 166.94, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(26, 30, 65, 'Banho de Alfazema 013', 73.14, 73.14, 3, 219.42, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(27, 30, 64, 'Banho de Manjericão 012', 42.51, 42.51, 4, 170.04, '2026-06-05 21:00:00', '2026-06-05 21:00:00'),
(28, 31, 137, 'Erva Manjericão 085', 134.37, 134.37, 2, 268.74, '2026-06-06 15:00:00', '2026-06-06 15:00:00'),
(29, 32, 66, 'Quartzo Branco 014', 61.05, 61.05, 4, 244.20, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(30, 32, 141, 'Pano de Cabeça Azul 089', 70.94, 70.94, 4, 283.76, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(31, 32, 80, 'Guia Preta 028', 26.22, 26.22, 3, 78.66, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(32, 32, 62, 'Banho de Guiné 010', 110.97, 110.97, 4, 443.88, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(33, 32, 54, 'Vela Azul 002', 43.37, 43.37, 5, 216.85, '2026-06-07 09:00:00', '2026-06-07 09:00:00'),
(34, 33, 108, 'Incenso de Alecrim 056', 115.91, 115.91, 3, 347.73, '2026-06-08 03:00:00', '2026-06-08 03:00:00'),
(35, 34, 84, 'Erva Alecrim 032', 57.96, 57.96, 3, 173.88, '2026-06-08 21:00:00', '2026-06-08 21:30:00'),
(36, 34, 90, 'Pano de Cabeça Vermelho 038', 31.49, 31.49, 5, 157.45, '2026-06-08 21:00:00', '2026-06-08 21:00:00'),
(37, 34, 109, 'Incenso de Arruda 057', 39.84, 39.84, 5, 199.20, '2026-06-08 21:00:00', '2026-06-08 21:00:00'),
(38, 35, 118, 'Ametista 066', 9.86, 9.86, 1, 9.86, '2026-06-09 15:00:00', '2026-06-09 15:00:00'),
(39, 35, 110, 'Incenso de Lavanda 058', 114.60, 114.60, 4, 458.40, '2026-06-09 15:00:00', '2026-06-09 15:00:00'),
(40, 36, 65, 'Banho de Alfazema 013', 73.14, 73.14, 2, 146.28, '2026-06-10 09:00:00', '2026-06-10 09:00:00'),
(41, 36, 99, 'Camiseta Branca 047', 21.39, 21.39, 2, 42.78, '2026-06-10 09:00:00', '2026-06-10 09:00:00'),
(42, 36, 59, 'Incenso de Arruda 007', 59.71, 59.71, 5, 298.55, '2026-06-10 09:00:00', '2026-06-10 09:00:00'),
(43, 36, 148, 'Calça Branca 096', 80.98, 80.98, 5, 404.90, '2026-06-10 09:00:00', '2026-06-10 09:00:00'),
(44, 36, 77, 'Guia Azul 025', 59.98, 59.98, 5, 299.90, '2026-06-10 09:00:00', '2026-06-10 09:30:00'),
(45, 37, 108, 'Incenso de Alecrim 056', 115.91, 115.91, 5, 579.55, '2026-06-11 03:00:00', '2026-06-11 03:00:00'),
(46, 38, 61, 'Incenso de Canela 009', 120.20, 120.20, 5, 601.00, '2026-06-11 21:00:00', '2026-06-11 21:00:00'),
(47, 38, 88, 'Erva Alfazema 036', 67.29, 67.29, 5, 336.45, '2026-06-11 21:00:00', '2026-06-11 21:00:00'),
(48, 39, 74, 'Pedra da Lua 022', 28.86, 28.86, 4, 115.44, '2026-06-12 15:00:00', '2026-06-12 15:00:00'),
(49, 40, 152, 'Fio de Contas Azul 100', 128.20, 128.20, 4, 512.80, '2026-06-13 09:00:00', '2026-06-13 09:00:00'),
(50, 40, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 4, 315.84, '2026-06-13 09:00:00', '2026-06-13 09:00:00'),
(51, 40, 60, 'Incenso de Lavanda 008', 125.31, 125.31, 3, 375.93, '2026-06-13 09:00:00', '2026-06-13 09:00:00'),
(52, 41, 118, 'Ametista 066', 9.86, 9.86, 3, 29.58, '2026-06-14 03:00:00', '2026-06-14 03:00:00'),
(53, 42, 122, 'Citrino 070', 85.28, 85.28, 3, 255.84, '2026-06-14 21:00:00', '2026-06-14 21:00:00'),
(54, 43, 143, 'Porta Guia Preto 091', 126.21, 126.21, 1, 126.21, '2026-06-15 15:00:00', '2026-06-15 15:00:00'),
(55, 43, 106, 'Vela Vermelha 054', 19.75, 19.75, 5, 98.75, '2026-06-15 15:00:00', '2026-06-15 15:00:00'),
(56, 43, 103, 'Vela Branca 051', 86.79, 86.79, 1, 86.79, '2026-06-15 15:00:00', '2026-06-15 15:00:00'),
(57, 44, 137, 'Erva Manjericão 085', 134.37, 134.37, 2, 268.74, '2026-06-16 09:00:00', '2026-06-16 09:00:00'),
(58, 45, 91, 'Pano de Cabeça Azul 039', 112.26, 112.26, 4, 449.04, '2026-06-17 03:00:00', '2026-06-17 03:00:00'),
(59, 45, 131, 'Defumador de Alecrim 079', 68.65, 68.65, 3, 205.95, '2026-06-17 03:00:00', '2026-06-17 03:00:00'),
(60, 45, 124, 'Pedra da Lua 072', 38.02, 38.02, 2, 76.04, '2026-06-17 03:00:00', '2026-06-17 03:00:00'),
(61, 46, 69, 'Olho de Tigre 017', 20.65, 20.65, 2, 41.30, '2026-06-17 21:00:00', '2026-06-17 21:00:00'),
(62, 46, 95, 'Saia Branca 043', 79.30, 79.30, 1, 79.30, '2026-06-17 21:00:00', '2026-06-17 21:00:00'),
(63, 47, 134, 'Erva Alecrim 082', 34.39, 34.39, 2, 68.78, '2026-06-18 15:00:00', '2026-06-18 15:00:00'),
(64, 47, 53, 'Vela Branca 001', 94.70, 94.70, 1, 94.70, '2026-06-18 15:00:00', '2026-06-18 15:00:00'),
(65, 47, 127, 'Guia Azul 075', 80.91, 80.91, 5, 404.55, '2026-06-18 15:00:00', '2026-06-18 15:00:00'),
(66, 47, 57, 'Vela Roxa 005', 35.97, 35.97, 1, 35.97, '2026-06-18 15:00:00', '2026-06-18 15:00:00'),
(67, 48, 107, 'Vela Roxa 055', 147.58, 147.58, 5, 737.90, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(68, 48, 59, 'Incenso de Arruda 007', 59.71, 59.71, 5, 298.55, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(69, 48, 131, 'Defumador de Alecrim 079', 68.65, 68.65, 4, 274.60, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(70, 48, 151, 'Fio de Contas Branco 099', 15.46, 15.46, 2, 30.92, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(71, 48, 76, 'Guia Branca 024', 36.78, 36.78, 3, 110.34, '2026-06-19 09:00:00', '2026-06-19 09:00:00'),
(72, 49, 133, 'Defumador de Benjoim 081', 129.33, 129.33, 6, 775.98, '2026-06-20 03:00:00', '2026-06-20 03:30:00'),
(73, 50, 75, 'Sodalita 023', 77.95, 77.95, 5, 389.75, '2026-06-20 21:00:00', '2026-06-20 21:00:00'),
(74, 50, 99, 'Camiseta Branca 047', 21.39, 21.39, 3, 64.17, '2026-06-20 21:00:00', '2026-06-20 21:00:00'),
(75, 50, 106, 'Vela Vermelha 054', 19.75, 19.75, 6, 118.50, '2026-06-20 21:00:00', '2026-06-20 21:30:00'),
(76, 50, 87, 'Erva Manjericão 035', 50.08, 50.08, 1, 50.08, '2026-06-20 21:00:00', '2026-06-20 21:00:00'),
(77, 50, 96, 'Saia Preta 044', 133.21, 133.21, 2, 266.42, '2026-06-20 21:00:00', '2026-06-20 21:30:00'),
(78, 51, 140, 'Pano de Cabeça Vermelho 088', 9.20, 9.20, 4, 36.80, '2026-06-21 15:00:00', '2026-06-21 15:00:00'),
(79, 51, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 3, 236.88, '2026-06-21 15:00:00', '2026-06-21 15:00:00'),
(80, 52, 104, 'Vela Azul 052', 6.21, 6.21, 3, 18.63, '2026-06-22 09:00:00', '2026-06-22 09:00:00'),
(81, 52, 72, 'Citrino 020', 105.14, 105.14, 3, 315.42, '2026-06-22 09:00:00', '2026-06-22 09:30:00'),
(82, 52, 100, 'Camiseta Preta 048', 118.37, 118.37, 1, 118.37, '2026-06-22 09:00:00', '2026-06-22 09:00:00'),
(83, 52, 131, 'Defumador de Alecrim 079', 68.65, 68.65, 4, 274.60, '2026-06-22 09:00:00', '2026-06-22 09:30:00'),
(84, 52, 86, 'Erva Guiné 034', 121.21, 121.21, 2, 242.42, '2026-06-22 09:00:00', '2026-06-22 09:30:00'),
(85, 53, 96, 'Saia Preta 044', 133.21, 133.21, 1, 133.21, '2026-06-23 03:00:00', '2026-06-23 03:00:00'),
(86, 53, 102, 'Fio de Contas Azul 050', 57.56, 57.56, 2, 115.12, '2026-06-23 03:00:00', '2026-06-23 03:00:00'),
(87, 53, 73, 'Ágata Verde 021', 49.26, 49.26, 2, 98.52, '2026-06-23 03:00:00', '2026-06-23 03:00:00'),
(88, 53, 105, 'Vela Verde 053', 83.47, 83.47, 4, 333.88, '2026-06-23 03:00:00', '2026-06-23 03:00:00'),
(89, 54, 135, 'Erva Arruda 083', 132.64, 132.64, 5, 663.20, '2026-06-23 21:00:00', '2026-06-23 21:30:00'),
(90, 54, 103, 'Vela Branca 051', 86.79, 86.79, 4, 347.16, '2026-06-23 21:00:00', '2026-06-23 21:00:00'),
(91, 54, 140, 'Pano de Cabeça Vermelho 088', 9.20, 9.20, 5, 46.00, '2026-06-23 21:00:00', '2026-06-23 21:00:00'),
(92, 54, 76, 'Guia Branca 024', 36.78, 36.78, 6, 220.68, '2026-06-23 21:00:00', '2026-06-23 21:30:00'),
(93, 54, 149, 'Camiseta Branca 097', 121.99, 121.99, 2, 243.98, '2026-06-23 21:00:00', '2026-06-23 21:00:00'),
(94, 55, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 4, 315.84, '2026-06-24 15:00:00', '2026-06-24 15:00:00'),
(95, 55, 136, 'Erva Guiné 084', 64.18, 64.18, 6, 385.08, '2026-06-24 15:00:00', '2026-06-24 15:30:00'),
(96, 55, 141, 'Pano de Cabeça Azul 089', 70.94, 70.94, 1, 70.94, '2026-06-24 15:00:00', '2026-06-24 15:00:00'),
(97, 55, 130, 'Guia Preta 078', 11.09, 11.09, 4, 44.36, '2026-06-24 15:00:00', '2026-06-24 15:00:00'),
(98, 56, 96, 'Saia Preta 044', 133.21, 133.21, 4, 532.84, '2026-06-25 09:00:00', '2026-06-25 09:00:00'),
(99, 56, 77, 'Guia Azul 025', 59.98, 59.98, 5, 299.90, '2026-06-25 09:00:00', '2026-06-25 09:00:00'),
(100, 57, 90, 'Pano de Cabeça Vermelho 038', 31.49, 31.49, 1, 31.49, '2026-06-26 03:00:00', '2026-06-26 03:00:00'),
(101, 57, 103, 'Vela Branca 051', 86.79, 86.79, 5, 433.95, '2026-06-26 03:00:00', '2026-06-26 03:00:00'),
(102, 57, 111, 'Incenso de Canela 059', 32.94, 32.94, 2, 65.88, '2026-06-26 03:00:00', '2026-06-26 03:00:00'),
(103, 57, 138, 'Erva Alfazema 086', 39.61, 39.61, 1, 39.61, '2026-06-26 03:00:00', '2026-06-26 03:00:00'),
(104, 58, 146, 'Saia Preta 094', 99.35, 99.35, 4, 397.40, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(105, 58, 105, 'Vela Verde 053', 83.47, 83.47, 5, 417.35, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(106, 58, 79, 'Guia Vermelha 027', 13.37, 13.37, 3, 40.11, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(107, 58, 141, 'Pano de Cabeça Azul 089', 70.94, 70.94, 1, 70.94, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(108, 58, 116, 'Quartzo Branco 064', 18.33, 18.33, 1, 18.33, '2026-06-26 21:00:00', '2026-06-26 21:00:00'),
(109, 59, 94, 'Porta Guia Vermelho 042', 105.90, 105.90, 5, 529.50, '2026-06-27 15:00:00', '2026-06-27 15:00:00'),
(110, 59, 141, 'Pano de Cabeça Azul 089', 70.94, 70.94, 4, 283.76, '2026-06-27 15:00:00', '2026-06-27 15:00:00'),
(111, 59, 100, 'Camiseta Preta 048', 118.37, 118.37, 1, 118.37, '2026-06-27 15:00:00', '2026-06-27 15:00:00'),
(112, 59, 54, 'Vela Azul 002', 43.37, 43.37, 2, 86.74, '2026-06-27 15:00:00', '2026-06-27 15:00:00'),
(113, 59, 113, 'Banho de Arruda 061', 48.35, 48.35, 6, 290.10, '2026-06-27 15:00:00', '2026-06-27 15:30:00'),
(114, 60, 113, 'Banho de Arruda 061', 48.35, 48.35, 2, 96.70, '2026-06-28 09:00:00', '2026-06-28 09:00:00'),
(115, 60, 68, 'Ametista 016', 21.47, 21.47, 3, 64.41, '2026-06-28 09:00:00', '2026-06-28 09:00:00'),
(116, 60, 54, 'Vela Azul 002', 43.37, 43.37, 1, 43.37, '2026-06-28 09:00:00', '2026-06-28 09:00:00'),
(117, 61, 110, 'Incenso de Lavanda 058', 114.60, 114.60, 2, 229.20, '2026-06-29 03:00:00', '2026-06-29 03:00:00'),
(118, 62, 105, 'Vela Verde 053', 83.47, 83.47, 4, 333.88, '2026-06-29 21:00:00', '2026-06-29 21:00:00'),
(119, 62, 98, 'Calça Branca 046', 112.44, 112.44, 2, 224.88, '2026-06-29 21:00:00', '2026-06-29 21:00:00'),
(120, 62, 111, 'Incenso de Canela 059', 32.94, 32.94, 4, 131.76, '2026-06-29 21:00:00', '2026-06-29 21:00:00'),
(121, 62, 152, 'Fio de Contas Azul 100', 128.20, 128.20, 2, 256.40, '2026-06-29 21:00:00', '2026-06-29 21:00:00'),
(122, 63, 134, 'Erva Alecrim 082', 34.39, 34.39, 4, 137.56, '2026-06-30 15:00:00', '2026-06-30 15:30:00'),
(123, 63, 91, 'Pano de Cabeça Azul 039', 112.26, 112.26, 1, 112.26, '2026-06-30 15:00:00', '2026-06-30 15:00:00'),
(124, 63, 80, 'Guia Preta 028', 26.22, 26.22, 4, 104.88, '2026-06-30 15:00:00', '2026-06-30 15:00:00'),
(125, 63, 150, 'Camiseta Preta 098', 111.60, 111.60, 4, 446.40, '2026-06-30 15:00:00', '2026-06-30 15:30:00'),
(126, 63, 125, 'Sodalita 073', 42.59, 42.59, 2, 85.18, '2026-06-30 15:00:00', '2026-06-30 15:00:00'),
(127, 64, 123, 'Ágata Verde 071', 61.77, 61.77, 5, 308.85, '2026-07-01 09:00:00', '2026-07-01 09:30:00'),
(128, 64, 79, 'Guia Vermelha 027', 13.37, 13.37, 1, 13.37, '2026-07-01 09:00:00', '2026-07-01 09:00:00'),
(129, 64, 60, 'Incenso de Lavanda 008', 125.31, 125.31, 1, 125.31, '2026-07-01 09:00:00', '2026-07-01 09:00:00'),
(130, 65, 97, 'Saia Vermelha 045', 66.18, 66.18, 1, 66.18, '2026-07-02 03:00:00', '2026-07-02 03:00:00'),
(131, 65, 137, 'Erva Manjericão 085', 134.37, 134.37, 4, 537.48, '2026-07-02 03:00:00', '2026-07-02 03:00:00'),
(132, 66, 96, 'Saia Preta 044', 133.21, 133.21, 3, 399.63, '2026-07-02 21:00:00', '2026-07-02 21:00:00'),
(133, 67, 109, 'Incenso de Arruda 057', 39.84, 39.84, 5, 199.20, '2026-07-03 15:00:00', '2026-07-03 15:00:00'),
(134, 68, 125, 'Sodalita 073', 42.59, 42.59, 4, 170.36, '2026-07-04 09:00:00', '2026-07-04 09:00:00'),
(135, 68, 58, 'Incenso de Alecrim 006', 135.14, 135.14, 1, 135.14, '2026-07-04 09:00:00', '2026-07-04 09:00:00'),
(136, 69, 91, 'Pano de Cabeça Azul 039', 112.26, 112.26, 5, 561.30, '2026-07-05 03:00:00', '2026-07-05 03:00:00'),
(137, 69, 103, 'Vela Branca 051', 86.79, 86.79, 5, 433.95, '2026-07-05 03:00:00', '2026-07-05 03:00:00'),
(138, 70, 83, 'Defumador de Benjoim 031', 129.64, 129.64, 1, 129.64, '2026-07-05 21:00:00', '2026-07-05 21:00:00'),
(139, 70, 82, 'Defumador de Arruda 030', 99.34, 99.34, 4, 397.36, '2026-07-05 21:00:00', '2026-07-05 21:00:00'),
(140, 70, 96, 'Saia Preta 044', 133.21, 133.21, 3, 399.63, '2026-07-05 21:00:00', '2026-07-05 21:00:00'),
(141, 70, 99, 'Camiseta Branca 047', 21.39, 21.39, 5, 106.95, '2026-07-05 21:00:00', '2026-07-05 21:30:00'),
(142, 71, 105, 'Vela Verde 053', 83.47, 83.47, 3, 250.41, '2026-07-06 15:00:00', '2026-07-06 15:00:00'),
(143, 71, 117, 'Quartzo Rosa 065', 73.76, 73.76, 5, 368.80, '2026-07-06 15:00:00', '2026-07-06 15:00:00'),
(144, 71, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 6, 473.76, '2026-07-06 15:00:00', '2026-07-06 15:30:00'),
(145, 71, 109, 'Incenso de Arruda 057', 39.84, 39.84, 2, 79.68, '2026-07-06 15:00:00', '2026-07-06 15:00:00'),
(146, 72, 59, 'Incenso de Arruda 007', 59.71, 59.71, 4, 238.84, '2026-07-07 09:00:00', '2026-07-07 09:00:00'),
(147, 72, 143, 'Porta Guia Preto 091', 126.21, 126.21, 5, 631.05, '2026-07-07 09:00:00', '2026-07-07 09:00:00'),
(148, 72, 61, 'Incenso de Canela 009', 120.20, 120.20, 4, 480.80, '2026-07-07 09:00:00', '2026-07-07 09:00:00'),
(149, 73, 135, 'Erva Arruda 083', 132.64, 132.64, 3, 397.92, '2026-07-08 03:00:00', '2026-07-08 03:00:00'),
(150, 73, 123, 'Ágata Verde 071', 61.77, 61.77, 5, 308.85, '2026-07-08 03:00:00', '2026-07-08 03:00:00'),
(151, 74, 112, 'Banho de Guiné 060', 56.65, 56.65, 2, 113.30, '2026-07-08 21:00:00', '2026-07-08 21:00:00'),
(152, 75, 88, 'Erva Alfazema 036', 67.29, 67.29, 1, 67.29, '2026-07-09 15:00:00', '2026-07-09 15:00:00'),
(153, 75, 97, 'Saia Vermelha 045', 66.18, 66.18, 1, 66.18, '2026-07-09 15:00:00', '2026-07-09 15:30:00'),
(154, 75, 120, 'Turmalina Negra 068', 38.64, 38.64, 4, 154.56, '2026-07-09 15:00:00', '2026-07-09 15:00:00'),
(155, 75, 58, 'Incenso de Alecrim 006', 135.14, 135.14, 3, 405.42, '2026-07-09 15:00:00', '2026-07-09 15:30:00'),
(156, 76, 81, 'Defumador de Alecrim 029', 73.37, 73.37, 3, 220.11, '2026-07-10 09:00:00', '2026-07-10 09:00:00'),
(157, 76, 75, 'Sodalita 023', 77.95, 77.95, 2, 155.90, '2026-07-10 09:00:00', '2026-07-10 09:00:00'),
(158, 76, 148, 'Calça Branca 096', 80.98, 80.98, 2, 161.96, '2026-07-10 09:00:00', '2026-07-10 09:00:00'),
(159, 76, 144, 'Porta Guia Vermelho 092', 136.06, 136.06, 5, 680.30, '2026-07-10 09:00:00', '2026-07-10 09:00:00'),
(160, 77, 90, 'Pano de Cabeça Vermelho 038', 31.49, 31.49, 1, 31.49, '2026-07-11 03:00:00', '2026-07-11 03:30:00'),
(161, 77, 143, 'Porta Guia Preto 091', 126.21, 126.21, 2, 252.42, '2026-07-11 03:00:00', '2026-07-11 03:30:00'),
(162, 78, 134, 'Erva Alecrim 082', 34.39, 34.39, 1, 34.39, '2026-07-11 21:00:00', '2026-07-11 21:00:00'),
(163, 78, 81, 'Defumador de Alecrim 029', 73.37, 73.37, 2, 146.74, '2026-07-11 21:00:00', '2026-07-11 21:00:00'),
(164, 78, 96, 'Saia Preta 044', 133.21, 133.21, 3, 399.63, '2026-07-11 21:00:00', '2026-07-11 21:00:00'),
(165, 79, 72, 'Citrino 020', 105.14, 105.14, 2, 210.28, '2026-07-12 15:00:00', '2026-07-12 15:00:00'),
(166, 79, 105, 'Vela Verde 053', 83.47, 83.47, 3, 250.41, '2026-07-12 15:00:00', '2026-07-12 15:00:00'),
(167, 79, 79, 'Guia Vermelha 027', 13.37, 13.37, 3, 40.11, '2026-07-12 15:00:00', '2026-07-12 15:00:00'),
(168, 79, 86, 'Erva Guiné 034', 121.21, 121.21, 5, 606.05, '2026-07-12 15:00:00', '2026-07-12 15:00:00'),
(169, 79, 147, 'Saia Vermelha 095', 60.91, 60.91, 5, 304.55, '2026-07-12 15:00:00', '2026-07-12 15:30:00'),
(170, 80, 94, 'Porta Guia Vermelho 042', 105.90, 105.90, 2, 211.80, '2026-07-13 09:00:00', '2026-07-13 09:00:00'),
(171, 80, 115, 'Banho de Alfazema 063', 131.01, 131.01, 5, 655.05, '2026-07-13 09:00:00', '2026-07-13 09:00:00'),
(172, 81, 107, 'Vela Roxa 055', 147.58, 147.58, 2, 295.16, '2026-07-14 03:00:00', '2026-07-14 03:30:00'),
(173, 81, 126, 'Guia Branca 074', 135.17, 135.17, 4, 540.68, '2026-07-14 03:00:00', '2026-07-14 03:00:00'),
(174, 81, 84, 'Erva Alecrim 032', 57.96, 57.96, 3, 173.88, '2026-07-14 03:00:00', '2026-07-14 03:00:00'),
(175, 81, 133, 'Defumador de Benjoim 081', 129.33, 129.33, 2, 258.66, '2026-07-14 03:00:00', '2026-07-14 03:00:00'),
(176, 81, 115, 'Banho de Alfazema 063', 131.01, 131.01, 3, 393.03, '2026-07-14 03:00:00', '2026-07-14 03:00:00'),
(177, 82, 120, 'Turmalina Negra 068', 38.64, 38.64, 5, 193.20, '2026-07-14 21:00:00', '2026-07-14 21:30:00'),
(178, 82, 148, 'Calça Branca 096', 80.98, 80.98, 2, 161.96, '2026-07-14 21:00:00', '2026-07-14 21:00:00'),
(179, 82, 126, 'Guia Branca 074', 135.17, 135.17, 4, 540.68, '2026-07-14 21:00:00', '2026-07-14 21:00:00'),
(180, 82, 54, 'Vela Azul 002', 43.37, 43.37, 5, 216.85, '2026-07-14 21:00:00', '2026-07-14 21:00:00'),
(181, 82, 66, 'Quartzo Branco 014', 61.05, 61.05, 3, 183.15, '2026-07-14 21:00:00', '2026-07-14 21:00:00'),
(182, 83, 84, 'Erva Alecrim 032', 57.96, 57.96, 5, 289.80, '2026-07-15 15:00:00', '2026-07-15 15:30:00'),
(183, 83, 103, 'Vela Branca 051', 86.79, 86.79, 4, 347.16, '2026-07-15 15:00:00', '2026-07-15 15:30:00'),
(184, 84, 56, 'Vela Vermelha 004', 53.17, 53.17, 1, 53.17, '2026-07-16 09:00:00', '2026-07-16 09:00:00'),
(185, 84, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 1, 78.96, '2026-07-16 09:00:00', '2026-07-16 09:00:00'),
(186, 84, 125, 'Sodalita 073', 42.59, 42.59, 5, 212.95, '2026-07-16 09:00:00', '2026-07-16 09:00:00'),
(187, 85, 100, 'Camiseta Preta 048', 118.37, 118.37, 5, 591.85, '2026-07-17 03:00:00', '2026-07-17 03:30:00'),
(188, 85, 137, 'Erva Manjericão 085', 134.37, 134.37, 5, 671.85, '2026-07-17 03:00:00', '2026-07-17 03:00:00'),
(189, 85, 148, 'Calça Branca 096', 80.98, 80.98, 4, 323.92, '2026-07-17 03:00:00', '2026-07-17 03:00:00'),
(190, 86, 66, 'Quartzo Branco 014', 61.05, 61.05, 5, 305.25, '2026-07-17 21:00:00', '2026-07-17 21:00:00'),
(191, 86, 112, 'Banho de Guiné 060', 56.65, 56.65, 1, 56.65, '2026-07-17 21:00:00', '2026-07-17 21:00:00'),
(192, 86, 87, 'Erva Manjericão 035', 50.08, 50.08, 1, 50.08, '2026-07-17 21:00:00', '2026-07-17 21:00:00'),
(193, 87, 122, 'Citrino 070', 85.28, 85.28, 4, 341.12, '2026-07-18 15:00:00', '2026-07-18 15:00:00'),
(194, 88, 98, 'Calça Branca 046', 112.44, 112.44, 5, 562.20, '2026-07-19 09:00:00', '2026-07-19 09:00:00'),
(195, 88, 150, 'Camiseta Preta 098', 111.60, 111.60, 2, 223.20, '2026-07-19 09:00:00', '2026-07-19 09:00:00'),
(196, 88, 85, 'Erva Arruda 033', 50.22, 50.22, 3, 150.66, '2026-07-19 09:00:00', '2026-07-19 09:00:00'),
(197, 88, 58, 'Incenso de Alecrim 006', 135.14, 135.14, 4, 540.56, '2026-07-19 09:00:00', '2026-07-19 09:30:00'),
(198, 88, 106, 'Vela Vermelha 054', 19.75, 19.75, 4, 79.00, '2026-07-19 09:00:00', '2026-07-19 09:00:00'),
(199, 89, 137, 'Erva Manjericão 085', 134.37, 134.37, 4, 537.48, '2026-07-20 03:00:00', '2026-07-20 03:30:00'),
(200, 89, 111, 'Incenso de Canela 059', 32.94, 32.94, 5, 164.70, '2026-07-20 03:00:00', '2026-07-20 03:00:00'),
(201, 89, 97, 'Saia Vermelha 045', 66.18, 66.18, 3, 198.54, '2026-07-20 03:00:00', '2026-07-20 03:30:00'),
(202, 89, 136, 'Erva Guiné 084', 64.18, 64.18, 1, 64.18, '2026-07-20 03:00:00', '2026-07-20 03:00:00'),
(203, 90, 74, 'Pedra da Lua 022', 28.86, 28.86, 3, 86.58, '2026-07-20 21:00:00', '2026-07-20 21:00:00'),
(204, 91, 77, 'Guia Azul 025', 59.98, 59.98, 4, 239.92, '2026-07-21 15:00:00', '2026-07-21 15:30:00'),
(205, 91, 68, 'Ametista 016', 21.47, 21.47, 5, 107.35, '2026-07-21 15:00:00', '2026-07-21 15:00:00'),
(206, 92, 66, 'Quartzo Branco 014', 61.05, 61.05, 1, 61.05, '2026-07-22 09:00:00', '2026-07-22 09:00:00'),
(207, 92, 150, 'Camiseta Preta 098', 111.60, 111.60, 1, 111.60, '2026-07-22 09:00:00', '2026-07-22 09:00:00'),
(208, 93, 92, 'Porta Guia Branco 040', 123.34, 123.34, 4, 493.36, '2026-07-23 03:00:00', '2026-07-23 03:00:00'),
(209, 94, 95, 'Saia Branca 043', 79.30, 79.30, 3, 237.90, '2026-07-23 21:00:00', '2026-07-23 21:00:00'),
(210, 95, 65, 'Banho de Alfazema 013', 73.14, 73.14, 3, 219.42, '2026-07-24 15:00:00', '2026-07-24 15:00:00'),
(211, 95, 67, 'Quartzo Rosa 015', 51.20, 51.20, 3, 153.60, '2026-07-24 15:00:00', '2026-07-24 15:30:00'),
(212, 95, 123, 'Ágata Verde 071', 61.77, 61.77, 4, 247.08, '2026-07-24 15:00:00', '2026-07-24 15:30:00'),
(213, 96, 76, 'Guia Branca 024', 36.78, 36.78, 2, 73.56, '2026-07-25 09:00:00', '2026-07-25 09:00:00'),
(214, 96, 143, 'Porta Guia Preto 091', 126.21, 126.21, 4, 504.84, '2026-07-25 09:00:00', '2026-07-25 09:00:00'),
(215, 96, 94, 'Porta Guia Vermelho 042', 105.90, 105.90, 4, 423.60, '2026-07-25 09:00:00', '2026-07-25 09:30:00'),
(216, 97, 56, 'Vela Vermelha 004', 53.17, 53.17, 6, 319.02, '2026-07-26 03:00:00', '2026-07-26 03:30:00'),
(217, 97, 98, 'Calça Branca 046', 112.44, 112.44, 1, 112.44, '2026-07-26 03:00:00', '2026-07-26 03:00:00'),
(218, 98, 93, 'Porta Guia Preto 041', 61.76, 61.76, 4, 247.04, '2026-07-26 21:00:00', '2026-07-26 21:00:00'),
(219, 98, 131, 'Defumador de Alecrim 079', 68.65, 68.65, 4, 274.60, '2026-07-26 21:00:00', '2026-07-26 21:00:00'),
(220, 98, 112, 'Banho de Guiné 060', 56.65, 56.65, 1, 56.65, '2026-07-26 21:00:00', '2026-07-26 21:00:00'),
(221, 99, 116, 'Quartzo Branco 064', 18.33, 18.33, 4, 73.32, '2026-07-27 15:00:00', '2026-07-27 15:00:00'),
(222, 99, 82, 'Defumador de Arruda 030', 99.34, 99.34, 5, 496.70, '2026-07-27 15:00:00', '2026-07-27 15:00:00'),
(223, 99, 151, 'Fio de Contas Branco 099', 15.46, 15.46, 3, 46.38, '2026-07-27 15:00:00', '2026-07-27 15:00:00'),
(224, 99, 119, 'Olho de Tigre 067', 40.03, 40.03, 3, 120.09, '2026-07-27 15:00:00', '2026-07-27 15:00:00'),
(225, 100, 125, 'Sodalita 073', 42.59, 42.59, 4, 170.36, '2026-07-28 09:00:00', '2026-07-28 09:00:00'),
(226, 100, 97, 'Saia Vermelha 045', 66.18, 66.18, 1, 66.18, '2026-07-28 09:00:00', '2026-07-28 09:30:00'),
(227, 100, 71, 'Jaspe Vermelho 019', 35.17, 35.17, 1, 35.17, '2026-07-28 09:00:00', '2026-07-28 09:00:00'),
(228, 100, 66, 'Quartzo Branco 014', 61.05, 61.05, 6, 366.30, '2026-07-28 09:00:00', '2026-07-28 09:30:00'),
(229, 100, 84, 'Erva Alecrim 032', 57.96, 57.96, 5, 289.80, '2026-07-28 09:00:00', '2026-07-28 09:30:00'),
(230, 101, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 1, 78.96, '2026-07-29 03:00:00', '2026-07-29 03:00:00'),
(231, 102, 57, 'Vela Roxa 005', 35.97, 35.97, 4, 143.88, '2026-07-29 21:00:00', '2026-07-29 21:30:00'),
(232, 103, 88, 'Erva Alfazema 036', 67.29, 67.29, 4, 269.16, '2026-07-30 15:00:00', '2026-07-30 15:00:00'),
(233, 103, 120, 'Turmalina Negra 068', 38.64, 38.64, 4, 154.56, '2026-07-30 15:00:00', '2026-07-30 15:00:00'),
(234, 103, 118, 'Ametista 066', 9.86, 9.86, 3, 29.58, '2026-07-30 15:00:00', '2026-07-30 15:00:00'),
(235, 103, 138, 'Erva Alfazema 086', 39.61, 39.61, 1, 39.61, '2026-07-30 15:00:00', '2026-07-30 15:00:00'),
(236, 104, 127, 'Guia Azul 075', 80.91, 80.91, 4, 323.64, '2026-07-31 09:00:00', '2026-07-31 09:30:00'),
(237, 104, 103, 'Vela Branca 051', 86.79, 86.79, 2, 173.58, '2026-07-31 09:00:00', '2026-07-31 09:00:00'),
(238, 104, 138, 'Erva Alfazema 086', 39.61, 39.61, 2, 79.22, '2026-07-31 09:00:00', '2026-07-31 09:00:00'),
(239, 104, 84, 'Erva Alecrim 032', 57.96, 57.96, 5, 289.80, '2026-07-31 09:00:00', '2026-07-31 09:00:00'),
(240, 104, 112, 'Banho de Guiné 060', 56.65, 56.65, 1, 56.65, '2026-07-31 09:00:00', '2026-07-31 09:00:00'),
(241, 105, 106, 'Vela Vermelha 054', 19.75, 19.75, 3, 59.25, '2026-08-01 03:00:00', '2026-08-01 03:00:00'),
(242, 105, 65, 'Banho de Alfazema 013', 73.14, 73.14, 5, 365.70, '2026-08-01 03:00:00', '2026-08-01 03:00:00'),
(243, 105, 91, 'Pano de Cabeça Azul 039', 112.26, 112.26, 6, 673.56, '2026-08-01 03:00:00', '2026-08-01 03:30:00'),
(244, 105, 148, 'Calça Branca 096', 80.98, 80.98, 5, 404.90, '2026-08-01 03:00:00', '2026-08-01 03:00:00'),
(245, 105, 73, 'Ágata Verde 021', 49.26, 49.26, 4, 197.04, '2026-08-01 03:00:00', '2026-08-01 03:00:00'),
(246, 106, 89, 'Pano de Cabeça Branco 037', 78.96, 78.96, 1, 78.96, '2026-08-01 21:00:00', '2026-08-01 21:00:00'),
(247, 106, 106, 'Vela Vermelha 054', 19.75, 19.75, 2, 39.50, '2026-08-01 21:00:00', '2026-08-01 21:00:00'),
(248, 106, 80, 'Guia Preta 028', 26.22, 26.22, 1, 26.22, '2026-08-01 21:00:00', '2026-08-01 21:00:00'),
(249, 106, 57, 'Vela Roxa 005', 35.97, 35.97, 3, 107.91, '2026-08-01 21:00:00', '2026-08-01 21:00:00'),
(250, 107, 113, 'Banho de Arruda 061', 48.35, 48.35, 1, 48.35, '2026-08-02 15:00:00', '2026-08-02 15:00:00'),
(251, 107, 146, 'Saia Preta 094', 99.35, 99.35, 5, 496.75, '2026-08-02 15:00:00', '2026-08-02 15:00:00'),
(252, 107, 59, 'Incenso de Arruda 007', 59.71, 59.71, 4, 238.84, '2026-08-02 15:00:00', '2026-08-02 15:00:00'),
(253, 108, 145, 'Saia Branca 093', 82.06, 82.06, 1, 82.06, '2026-08-03 09:00:00', '2026-08-03 09:00:00'),
(254, 109, 117, 'Quartzo Rosa 065', 73.76, 73.76, 5, 368.80, '2026-08-04 03:00:00', '2026-08-04 03:00:00'),
(255, 109, 74, 'Pedra da Lua 022', 28.86, 28.86, 2, 57.72, '2026-08-04 03:00:00', '2026-08-04 03:00:00'),
(256, 109, 115, 'Banho de Alfazema 063', 131.01, 131.01, 3, 393.03, '2026-08-04 03:00:00', '2026-08-04 03:00:00'),
(257, 109, 69, 'Olho de Tigre 017', 20.65, 20.65, 2, 41.30, '2026-08-04 03:00:00', '2026-08-04 03:00:00'),
(258, 109, 84, 'Erva Alecrim 032', 57.96, 57.96, 6, 347.76, '2026-08-04 03:00:00', '2026-08-04 03:30:00'),
(259, 110, 79, 'Guia Vermelha 027', 13.37, 13.37, 4, 53.48, '2026-08-04 21:00:00', '2026-08-04 21:30:00'),
(260, 110, 106, 'Vela Vermelha 054', 19.75, 19.75, 1, 19.75, '2026-08-04 21:00:00', '2026-08-04 21:00:00'),
(261, 110, 88, 'Erva Alfazema 036', 67.29, 67.29, 4, 269.16, '2026-08-04 21:00:00', '2026-08-04 21:00:00'),
(262, 110, 77, 'Guia Azul 025', 59.98, 59.98, 2, 119.96, '2026-08-04 21:00:00', '2026-08-04 21:00:00'),
(263, 110, 119, 'Olho de Tigre 067', 40.03, 40.03, 5, 200.15, '2026-08-04 21:00:00', '2026-08-04 21:00:00'),
(264, 111, 128, 'Guia Verde 076', 133.87, 133.87, 4, 535.48, '2026-08-05 15:00:00', '2026-08-05 15:00:00'),
(265, 111, 119, 'Olho de Tigre 067', 40.03, 40.03, 2, 80.06, '2026-08-05 15:00:00', '2026-08-05 15:30:00'),
(266, 111, 92, 'Porta Guia Branco 040', 123.34, 123.34, 4, 493.36, '2026-08-05 15:00:00', '2026-08-05 15:00:00'),
(267, 112, 146, 'Saia Preta 094', 99.35, 99.35, 2, 198.70, '2026-08-06 09:00:00', '2026-08-06 09:00:00'),
(268, 112, 80, 'Guia Preta 028', 26.22, 26.22, 2, 52.44, '2026-08-06 09:00:00', '2026-08-06 09:00:00'),
(269, 112, 107, 'Vela Roxa 055', 147.58, 147.58, 2, 295.16, '2026-08-06 09:00:00', '2026-08-06 09:00:00'),
(270, 112, 123, 'Ágata Verde 071', 61.77, 61.77, 5, 308.85, '2026-08-06 09:00:00', '2026-08-06 09:00:00'),
(271, 113, 92, 'Porta Guia Branco 040', 123.34, 123.34, 5, 616.70, '2026-08-07 03:00:00', '2026-08-07 03:30:00'),
(272, 113, 149, 'Camiseta Branca 097', 121.99, 121.99, 2, 243.98, '2026-08-07 03:00:00', '2026-08-07 03:30:00'),
(273, 113, 122, 'Citrino 070', 85.28, 85.28, 2, 170.56, '2026-08-07 03:00:00', '2026-08-07 03:00:00'),
(274, 113, 96, 'Saia Preta 044', 133.21, 133.21, 2, 266.42, '2026-08-07 03:00:00', '2026-08-07 03:00:00'),
(275, 113, 144, 'Porta Guia Vermelho 092', 136.06, 136.06, 3, 408.18, '2026-08-07 03:00:00', '2026-08-07 03:00:00'),
(276, 114, 108, 'Incenso de Alecrim 056', 115.91, 115.91, 4, 463.64, '2026-08-07 21:00:00', '2026-08-07 21:30:00'),
(277, 115, 112, 'Banho de Guiné 060', 56.65, 56.65, 4, 226.60, '2026-08-08 15:00:00', '2026-08-08 15:00:00'),
(278, 115, 129, 'Guia Vermelha 077', 95.49, 95.49, 6, 572.94, '2026-08-08 15:00:00', '2026-08-08 15:30:00'),
(279, 115, 60, 'Incenso de Lavanda 008', 125.31, 125.31, 4, 501.24, '2026-08-08 15:00:00', '2026-08-08 15:00:00'),
(280, 116, 87, 'Erva Manjericão 035', 50.08, 50.08, 1, 50.08, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(281, 116, 122, 'Citrino 070', 85.28, 85.28, 3, 255.84, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(282, 116, 107, 'Vela Roxa 055', 147.58, 147.58, 1, 147.58, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(283, 116, 71, 'Jaspe Vermelho 019', 35.17, 35.17, 4, 140.68, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(284, 116, 64, 'Banho de Manjericão 012', 42.51, 42.51, 2, 85.02, '2026-08-09 09:00:00', '2026-08-09 09:00:00'),
(285, 117, 74, 'Pedra da Lua 022', 28.86, 28.86, 1, 28.86, '2026-08-10 03:00:00', '2026-08-10 03:00:00'),
(286, 118, 78, 'Guia Verde 026', 80.45, 80.45, 1, 80.45, '2026-08-10 21:00:00', '2026-08-10 21:00:00'),
(287, 119, 74, 'Pedra da Lua 022', 28.86, 28.86, 1, 28.86, '2026-08-11 15:00:00', '2026-08-11 15:00:00'),
(288, 119, 138, 'Erva Alfazema 086', 39.61, 39.61, 4, 158.44, '2026-08-11 15:00:00', '2026-08-11 15:00:00'),
(289, 120, 64, 'Banho de Manjericão 012', 42.51, 42.51, 3, 127.53, '2026-08-12 09:00:00', '2026-08-12 09:00:00'),
(290, 121, 142, 'Porta Guia Branco 090', 18.87, 18.87, 1, 18.87, '2026-08-13 03:00:00', '2026-08-13 03:30:00'),
(291, 122, 85, 'Erva Arruda 033', 50.22, 50.22, 2, 100.44, '2026-08-13 21:00:00', '2026-08-13 21:30:00'),
(292, 122, 139, 'Pano de Cabeça Branco 087', 123.09, 123.09, 1, 123.09, '2026-08-13 21:00:00', '2026-08-13 21:00:00'),
(293, 122, 119, 'Olho de Tigre 067', 40.03, 40.03, 1, 40.03, '2026-08-13 21:00:00', '2026-08-13 21:00:00'),
(294, 123, 103, 'Vela Branca 051', 86.79, 86.79, 1, 86.79, '2026-08-14 15:00:00', '2026-08-14 15:00:00');

INSERT INTO `sale_item_changes`
(`id`, `sale_id`, `sale_item_id`, `product_id`, `product_name`, `action`, `old_quantity`, `new_quantity`, `old_unit_price`, `new_unit_price`, `user_id`, `created_at`) VALUES
(6, 24, 3, 83, 'Defumador de Benjoim 031', 'created', NULL, 5, NULL, 129.64, 1, '2026-06-01 09:00:00'),
(7, 25, 4, 117, 'Quartzo Rosa 065', 'created', NULL, 3, NULL, 73.76, 1, '2026-06-02 03:00:00'),
(8, 25, 5, 139, 'Pano de Cabeça Branco 087', 'created', NULL, 4, NULL, 123.09, 1, '2026-06-02 03:00:00'),
(9, 25, 5, 139, 'Pano de Cabeça Branco 087', 'updated', 4, 1, 123.09, 123.09, 1, '2026-06-02 03:30:00'),
(10, 25, 6, 115, 'Banho de Alfazema 063', 'created', NULL, 5, NULL, 131.01, 1, '2026-06-02 03:00:00'),
(11, 25, 6, 115, 'Banho de Alfazema 063', 'updated', 5, 2, 131.01, 131.01, 1, '2026-06-02 03:30:00'),
(12, 25, 7, 101, 'Fio de Contas Branco 049', 'created', NULL, 2, NULL, 65.87, 1, '2026-06-02 03:00:00'),
(13, 25, 7, 101, 'Fio de Contas Branco 049', 'updated', 2, 3, 65.87, 65.87, 1, '2026-06-02 03:30:00'),
(14, 25, 8, 93, 'Porta Guia Preto 041', 'created', NULL, 3, NULL, 61.76, 1, '2026-06-02 03:00:00'),
(15, 25, 8, 93, 'Porta Guia Preto 041', 'updated', 3, 6, 61.76, 61.76, 1, '2026-06-02 03:30:00'),
(16, 26, 9, 61, 'Incenso de Canela 009', 'created', NULL, 4, NULL, 120.20, 1, '2026-06-02 21:00:00'),
(17, 26, 10, 114, 'Banho de Manjericão 062', 'created', NULL, 5, NULL, 104.81, 1, '2026-06-02 21:00:00'),
(18, 26, 11, 98, 'Calça Branca 046', 'created', NULL, 1, NULL, 112.44, 1, '2026-06-02 21:00:00'),
(19, 26, 11, 98, 'Calça Branca 046', 'updated', 1, 5, 112.44, 112.44, 1, '2026-06-02 21:30:00'),
(20, 27, 12, 67, 'Quartzo Rosa 015', 'created', NULL, 2, NULL, 51.20, 1, '2026-06-03 15:00:00'),
(21, 27, 13, 124, 'Pedra da Lua 072', 'created', NULL, 5, NULL, 38.02, 1, '2026-06-03 15:00:00'),
(22, 27, 14, 79, 'Guia Vermelha 027', 'created', NULL, 3, NULL, 13.37, 1, '2026-06-03 15:00:00'),
(23, 27, 15, 83, 'Defumador de Benjoim 031', 'created', NULL, 3, NULL, 129.64, 1, '2026-06-03 15:00:00'),
(24, 28, 16, 128, 'Guia Verde 076', 'created', NULL, 2, NULL, 133.87, 1, '2026-06-04 09:00:00'),
(25, 28, 17, 102, 'Fio de Contas Azul 050', 'created', NULL, 4, NULL, 57.56, 1, '2026-06-04 09:00:00'),
(26, 28, 17, 102, 'Fio de Contas Azul 050', 'updated', 4, 6, 57.56, 57.56, 1, '2026-06-04 09:30:00'),
(27, 28, 18, 64, 'Banho de Manjericão 012', 'created', NULL, 3, NULL, 42.51, 1, '2026-06-04 09:00:00'),
(28, 29, 19, 137, 'Erva Manjericão 085', 'created', NULL, 3, NULL, 134.37, 1, '2026-06-05 03:00:00'),
(29, 29, 20, 62, 'Banho de Guiné 010', 'created', NULL, 2, NULL, 110.97, 1, '2026-06-05 03:00:00'),
(30, 29, 21, 133, 'Defumador de Benjoim 081', 'created', NULL, 4, NULL, 129.33, 1, '2026-06-05 03:00:00'),
(31, 29, 22, 114, 'Banho de Manjericão 062', 'created', NULL, 3, NULL, 104.81, 1, '2026-06-05 03:00:00'),
(32, 30, 23, 136, 'Erva Guiné 084', 'created', NULL, 4, NULL, 64.18, 1, '2026-06-05 21:00:00'),
(33, 30, 24, 59, 'Incenso de Arruda 007', 'created', NULL, 5, NULL, 59.71, 1, '2026-06-05 21:00:00'),
(34, 30, 25, 105, 'Vela Verde 053', 'created', NULL, 2, NULL, 83.47, 1, '2026-06-05 21:00:00'),
(35, 30, 26, 65, 'Banho de Alfazema 013', 'created', NULL, 3, NULL, 73.14, 1, '2026-06-05 21:00:00'),
(36, 30, 27, 64, 'Banho de Manjericão 012', 'created', NULL, 4, NULL, 42.51, 1, '2026-06-05 21:00:00'),
(37, 31, 28, 137, 'Erva Manjericão 085', 'created', NULL, 2, NULL, 134.37, 1, '2026-06-06 15:00:00'),
(38, 32, 29, 66, 'Quartzo Branco 014', 'created', NULL, 4, NULL, 61.05, 1, '2026-06-07 09:00:00'),
(39, 32, 30, 141, 'Pano de Cabeça Azul 089', 'created', NULL, 4, NULL, 70.94, 1, '2026-06-07 09:00:00'),
(40, 32, 31, 80, 'Guia Preta 028', 'created', NULL, 3, NULL, 26.22, 1, '2026-06-07 09:00:00'),
(41, 32, 32, 62, 'Banho de Guiné 010', 'created', NULL, 4, NULL, 110.97, 1, '2026-06-07 09:00:00'),
(42, 32, 33, 54, 'Vela Azul 002', 'created', NULL, 5, NULL, 43.37, 1, '2026-06-07 09:00:00'),
(43, 33, 34, 108, 'Incenso de Alecrim 056', 'created', NULL, 3, NULL, 115.91, 1, '2026-06-08 03:00:00'),
(44, 34, 35, 84, 'Erva Alecrim 032', 'created', NULL, 4, NULL, 57.96, 1, '2026-06-08 21:00:00'),
(45, 34, 35, 84, 'Erva Alecrim 032', 'updated', 4, 3, 57.96, 57.96, 1, '2026-06-08 21:30:00'),
(46, 34, 36, 90, 'Pano de Cabeça Vermelho 038', 'created', NULL, 5, NULL, 31.49, 1, '2026-06-08 21:00:00'),
(47, 34, 37, 109, 'Incenso de Arruda 057', 'created', NULL, 5, NULL, 39.84, 1, '2026-06-08 21:00:00'),
(48, 35, 38, 118, 'Ametista 066', 'created', NULL, 1, NULL, 9.86, 1, '2026-06-09 15:00:00'),
(49, 35, 39, 110, 'Incenso de Lavanda 058', 'created', NULL, 4, NULL, 114.60, 1, '2026-06-09 15:00:00'),
(50, 36, 40, 65, 'Banho de Alfazema 013', 'created', NULL, 2, NULL, 73.14, 1, '2026-06-10 09:00:00'),
(51, 36, 41, 99, 'Camiseta Branca 047', 'created', NULL, 2, NULL, 21.39, 1, '2026-06-10 09:00:00'),
(52, 36, 42, 59, 'Incenso de Arruda 007', 'created', NULL, 5, NULL, 59.71, 1, '2026-06-10 09:00:00'),
(53, 36, 43, 148, 'Calça Branca 096', 'created', NULL, 5, NULL, 80.98, 1, '2026-06-10 09:00:00'),
(54, 36, 44, 77, 'Guia Azul 025', 'created', NULL, 4, NULL, 59.98, 1, '2026-06-10 09:00:00'),
(55, 36, 44, 77, 'Guia Azul 025', 'updated', 4, 5, 59.98, 59.98, 1, '2026-06-10 09:30:00'),
(56, 37, 45, 108, 'Incenso de Alecrim 056', 'created', NULL, 5, NULL, 115.91, 1, '2026-06-11 03:00:00'),
(57, 38, 46, 61, 'Incenso de Canela 009', 'created', NULL, 5, NULL, 120.20, 1, '2026-06-11 21:00:00'),
(58, 38, 47, 88, 'Erva Alfazema 036', 'created', NULL, 5, NULL, 67.29, 1, '2026-06-11 21:00:00'),
(59, 39, 48, 74, 'Pedra da Lua 022', 'created', NULL, 4, NULL, 28.86, 1, '2026-06-12 15:00:00'),
(60, 40, 49, 152, 'Fio de Contas Azul 100', 'created', NULL, 4, NULL, 128.20, 1, '2026-06-13 09:00:00'),
(61, 40, 50, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 4, NULL, 78.96, 1, '2026-06-13 09:00:00'),
(62, 40, 51, 60, 'Incenso de Lavanda 008', 'created', NULL, 3, NULL, 125.31, 1, '2026-06-13 09:00:00'),
(63, 41, 52, 118, 'Ametista 066', 'created', NULL, 3, NULL, 9.86, 1, '2026-06-14 03:00:00'),
(64, 42, 53, 122, 'Citrino 070', 'created', NULL, 3, NULL, 85.28, 1, '2026-06-14 21:00:00'),
(65, 43, 54, 143, 'Porta Guia Preto 091', 'created', NULL, 1, NULL, 126.21, 1, '2026-06-15 15:00:00'),
(66, 43, 55, 106, 'Vela Vermelha 054', 'created', NULL, 5, NULL, 19.75, 1, '2026-06-15 15:00:00'),
(67, 43, 56, 103, 'Vela Branca 051', 'created', NULL, 1, NULL, 86.79, 1, '2026-06-15 15:00:00'),
(68, 44, 57, 137, 'Erva Manjericão 085', 'created', NULL, 2, NULL, 134.37, 1, '2026-06-16 09:00:00'),
(69, 45, 58, 91, 'Pano de Cabeça Azul 039', 'created', NULL, 4, NULL, 112.26, 1, '2026-06-17 03:00:00'),
(70, 45, 59, 131, 'Defumador de Alecrim 079', 'created', NULL, 3, NULL, 68.65, 1, '2026-06-17 03:00:00'),
(71, 45, 60, 124, 'Pedra da Lua 072', 'created', NULL, 2, NULL, 38.02, 1, '2026-06-17 03:00:00'),
(72, 46, 61, 69, 'Olho de Tigre 017', 'created', NULL, 2, NULL, 20.65, 1, '2026-06-17 21:00:00'),
(73, 46, 62, 95, 'Saia Branca 043', 'created', NULL, 1, NULL, 79.30, 1, '2026-06-17 21:00:00'),
(74, 47, 63, 134, 'Erva Alecrim 082', 'created', NULL, 2, NULL, 34.39, 1, '2026-06-18 15:00:00'),
(75, 47, 64, 53, 'Vela Branca 001', 'created', NULL, 1, NULL, 94.70, 1, '2026-06-18 15:00:00'),
(76, 47, 65, 127, 'Guia Azul 075', 'created', NULL, 5, NULL, 80.91, 1, '2026-06-18 15:00:00'),
(77, 47, 66, 57, 'Vela Roxa 005', 'created', NULL, 1, NULL, 35.97, 1, '2026-06-18 15:00:00'),
(78, 48, 67, 107, 'Vela Roxa 055', 'created', NULL, 5, NULL, 147.58, 1, '2026-06-19 09:00:00'),
(79, 48, 68, 59, 'Incenso de Arruda 007', 'created', NULL, 5, NULL, 59.71, 1, '2026-06-19 09:00:00'),
(80, 48, 69, 131, 'Defumador de Alecrim 079', 'created', NULL, 4, NULL, 68.65, 1, '2026-06-19 09:00:00'),
(81, 48, 70, 151, 'Fio de Contas Branco 099', 'created', NULL, 2, NULL, 15.46, 1, '2026-06-19 09:00:00'),
(82, 48, 71, 76, 'Guia Branca 024', 'created', NULL, 3, NULL, 36.78, 1, '2026-06-19 09:00:00'),
(83, 49, 72, 133, 'Defumador de Benjoim 081', 'created', NULL, 5, NULL, 129.33, 1, '2026-06-20 03:00:00'),
(84, 49, 72, 133, 'Defumador de Benjoim 081', 'updated', 5, 6, 129.33, 129.33, 1, '2026-06-20 03:30:00'),
(85, 50, 73, 75, 'Sodalita 023', 'created', NULL, 5, NULL, 77.95, 1, '2026-06-20 21:00:00'),
(86, 50, 74, 99, 'Camiseta Branca 047', 'created', NULL, 3, NULL, 21.39, 1, '2026-06-20 21:00:00'),
(87, 50, 75, 106, 'Vela Vermelha 054', 'created', NULL, 5, NULL, 19.75, 1, '2026-06-20 21:00:00'),
(88, 50, 75, 106, 'Vela Vermelha 054', 'updated', 5, 6, 19.75, 19.75, 1, '2026-06-20 21:30:00'),
(89, 50, 76, 87, 'Erva Manjericão 035', 'created', NULL, 1, NULL, 50.08, 1, '2026-06-20 21:00:00'),
(90, 50, 77, 96, 'Saia Preta 044', 'created', NULL, 4, NULL, 133.21, 1, '2026-06-20 21:00:00'),
(91, 50, 77, 96, 'Saia Preta 044', 'updated', 4, 2, 133.21, 133.21, 1, '2026-06-20 21:30:00'),
(92, 51, 78, 140, 'Pano de Cabeça Vermelho 088', 'created', NULL, 4, NULL, 9.20, 1, '2026-06-21 15:00:00'),
(93, 51, 79, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 3, NULL, 78.96, 1, '2026-06-21 15:00:00'),
(94, 52, 80, 104, 'Vela Azul 052', 'created', NULL, 3, NULL, 6.21, 1, '2026-06-22 09:00:00'),
(95, 52, 81, 72, 'Citrino 020', 'created', NULL, 1, NULL, 105.14, 1, '2026-06-22 09:00:00'),
(96, 52, 81, 72, 'Citrino 020', 'updated', 1, 3, 105.14, 105.14, 1, '2026-06-22 09:30:00'),
(97, 52, 82, 100, 'Camiseta Preta 048', 'created', NULL, 1, NULL, 118.37, 1, '2026-06-22 09:00:00'),
(98, 52, 83, 131, 'Defumador de Alecrim 079', 'created', NULL, 1, NULL, 68.65, 1, '2026-06-22 09:00:00'),
(99, 52, 83, 131, 'Defumador de Alecrim 079', 'updated', 1, 4, 68.65, 68.65, 1, '2026-06-22 09:30:00'),
(100, 52, 84, 86, 'Erva Guiné 034', 'created', NULL, 5, NULL, 121.21, 1, '2026-06-22 09:00:00'),
(101, 52, 84, 86, 'Erva Guiné 034', 'updated', 5, 2, 121.21, 121.21, 1, '2026-06-22 09:30:00'),
(102, 53, 85, 96, 'Saia Preta 044', 'created', NULL, 1, NULL, 133.21, 1, '2026-06-23 03:00:00'),
(103, 53, 86, 102, 'Fio de Contas Azul 050', 'created', NULL, 2, NULL, 57.56, 1, '2026-06-23 03:00:00'),
(104, 53, 87, 73, 'Ágata Verde 021', 'created', NULL, 2, NULL, 49.26, 1, '2026-06-23 03:00:00'),
(105, 53, 88, 105, 'Vela Verde 053', 'created', NULL, 4, NULL, 83.47, 1, '2026-06-23 03:00:00'),
(106, 54, 89, 135, 'Erva Arruda 083', 'created', NULL, 4, NULL, 132.64, 1, '2026-06-23 21:00:00'),
(107, 54, 89, 135, 'Erva Arruda 083', 'updated', 4, 5, 132.64, 132.64, 1, '2026-06-23 21:30:00'),
(108, 54, 90, 103, 'Vela Branca 051', 'created', NULL, 4, NULL, 86.79, 1, '2026-06-23 21:00:00'),
(109, 54, 91, 140, 'Pano de Cabeça Vermelho 088', 'created', NULL, 5, NULL, 9.20, 1, '2026-06-23 21:00:00'),
(110, 54, 92, 76, 'Guia Branca 024', 'created', NULL, 5, NULL, 36.78, 1, '2026-06-23 21:00:00'),
(111, 54, 92, 76, 'Guia Branca 024', 'updated', 5, 6, 36.78, 36.78, 1, '2026-06-23 21:30:00'),
(112, 54, 93, 149, 'Camiseta Branca 097', 'created', NULL, 2, NULL, 121.99, 1, '2026-06-23 21:00:00'),
(113, 55, 94, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 4, NULL, 78.96, 1, '2026-06-24 15:00:00'),
(114, 55, 95, 136, 'Erva Guiné 084', 'created', NULL, 2, NULL, 64.18, 1, '2026-06-24 15:00:00'),
(115, 55, 95, 136, 'Erva Guiné 084', 'updated', 2, 6, 64.18, 64.18, 1, '2026-06-24 15:30:00'),
(116, 55, 96, 141, 'Pano de Cabeça Azul 089', 'created', NULL, 1, NULL, 70.94, 1, '2026-06-24 15:00:00'),
(117, 55, 97, 130, 'Guia Preta 078', 'created', NULL, 4, NULL, 11.09, 1, '2026-06-24 15:00:00'),
(118, 56, 98, 96, 'Saia Preta 044', 'created', NULL, 4, NULL, 133.21, 1, '2026-06-25 09:00:00'),
(119, 56, 99, 77, 'Guia Azul 025', 'created', NULL, 5, NULL, 59.98, 1, '2026-06-25 09:00:00'),
(120, 57, 100, 90, 'Pano de Cabeça Vermelho 038', 'created', NULL, 1, NULL, 31.49, 1, '2026-06-26 03:00:00'),
(121, 57, 101, 103, 'Vela Branca 051', 'created', NULL, 5, NULL, 86.79, 1, '2026-06-26 03:00:00'),
(122, 57, 102, 111, 'Incenso de Canela 059', 'created', NULL, 2, NULL, 32.94, 1, '2026-06-26 03:00:00'),
(123, 57, 103, 138, 'Erva Alfazema 086', 'created', NULL, 1, NULL, 39.61, 1, '2026-06-26 03:00:00'),
(124, 58, 104, 146, 'Saia Preta 094', 'created', NULL, 4, NULL, 99.35, 1, '2026-06-26 21:00:00'),
(125, 58, 105, 105, 'Vela Verde 053', 'created', NULL, 5, NULL, 83.47, 1, '2026-06-26 21:00:00'),
(126, 58, 106, 79, 'Guia Vermelha 027', 'created', NULL, 3, NULL, 13.37, 1, '2026-06-26 21:00:00'),
(127, 58, 107, 141, 'Pano de Cabeça Azul 089', 'created', NULL, 1, NULL, 70.94, 1, '2026-06-26 21:00:00'),
(128, 58, 108, 116, 'Quartzo Branco 064', 'created', NULL, 1, NULL, 18.33, 1, '2026-06-26 21:00:00'),
(129, 59, 109, 94, 'Porta Guia Vermelho 042', 'created', NULL, 5, NULL, 105.90, 1, '2026-06-27 15:00:00'),
(130, 59, 110, 141, 'Pano de Cabeça Azul 089', 'created', NULL, 4, NULL, 70.94, 1, '2026-06-27 15:00:00'),
(131, 59, 111, 100, 'Camiseta Preta 048', 'created', NULL, 1, NULL, 118.37, 1, '2026-06-27 15:00:00'),
(132, 59, 112, 54, 'Vela Azul 002', 'created', NULL, 2, NULL, 43.37, 1, '2026-06-27 15:00:00'),
(133, 59, 113, 113, 'Banho de Arruda 061', 'created', NULL, 1, NULL, 48.35, 1, '2026-06-27 15:00:00'),
(134, 59, 113, 113, 'Banho de Arruda 061', 'updated', 1, 6, 48.35, 48.35, 1, '2026-06-27 15:30:00'),
(135, 60, 114, 113, 'Banho de Arruda 061', 'created', NULL, 2, NULL, 48.35, 1, '2026-06-28 09:00:00'),
(136, 60, 115, 68, 'Ametista 016', 'created', NULL, 3, NULL, 21.47, 1, '2026-06-28 09:00:00'),
(137, 60, 116, 54, 'Vela Azul 002', 'created', NULL, 1, NULL, 43.37, 1, '2026-06-28 09:00:00'),
(138, 61, 117, 110, 'Incenso de Lavanda 058', 'created', NULL, 2, NULL, 114.60, 1, '2026-06-29 03:00:00'),
(139, 62, 118, 105, 'Vela Verde 053', 'created', NULL, 4, NULL, 83.47, 1, '2026-06-29 21:00:00'),
(140, 62, 119, 98, 'Calça Branca 046', 'created', NULL, 2, NULL, 112.44, 1, '2026-06-29 21:00:00'),
(141, 62, 120, 111, 'Incenso de Canela 059', 'created', NULL, 4, NULL, 32.94, 1, '2026-06-29 21:00:00'),
(142, 62, 121, 152, 'Fio de Contas Azul 100', 'created', NULL, 2, NULL, 128.20, 1, '2026-06-29 21:00:00'),
(143, 63, 122, 134, 'Erva Alecrim 082', 'created', NULL, 3, NULL, 34.39, 1, '2026-06-30 15:00:00'),
(144, 63, 122, 134, 'Erva Alecrim 082', 'updated', 3, 4, 34.39, 34.39, 1, '2026-06-30 15:30:00'),
(145, 63, 123, 91, 'Pano de Cabeça Azul 039', 'created', NULL, 1, NULL, 112.26, 1, '2026-06-30 15:00:00'),
(146, 63, 124, 80, 'Guia Preta 028', 'created', NULL, 4, NULL, 26.22, 1, '2026-06-30 15:00:00'),
(147, 63, 125, 150, 'Camiseta Preta 098', 'created', NULL, 1, NULL, 111.60, 1, '2026-06-30 15:00:00'),
(148, 63, 125, 150, 'Camiseta Preta 098', 'updated', 1, 4, 111.60, 111.60, 1, '2026-06-30 15:30:00'),
(149, 63, 126, 125, 'Sodalita 073', 'created', NULL, 2, NULL, 42.59, 1, '2026-06-30 15:00:00'),
(150, 64, 127, 123, 'Ágata Verde 071', 'created', NULL, 2, NULL, 61.77, 1, '2026-07-01 09:00:00'),
(151, 64, 127, 123, 'Ágata Verde 071', 'updated', 2, 5, 61.77, 61.77, 1, '2026-07-01 09:30:00'),
(152, 64, 128, 79, 'Guia Vermelha 027', 'created', NULL, 1, NULL, 13.37, 1, '2026-07-01 09:00:00'),
(153, 64, 129, 60, 'Incenso de Lavanda 008', 'created', NULL, 1, NULL, 125.31, 1, '2026-07-01 09:00:00'),
(154, 65, 130, 97, 'Saia Vermelha 045', 'created', NULL, 1, NULL, 66.18, 1, '2026-07-02 03:00:00'),
(155, 65, 131, 137, 'Erva Manjericão 085', 'created', NULL, 4, NULL, 134.37, 1, '2026-07-02 03:00:00'),
(156, 66, 132, 96, 'Saia Preta 044', 'created', NULL, 3, NULL, 133.21, 1, '2026-07-02 21:00:00'),
(157, 67, 133, 109, 'Incenso de Arruda 057', 'created', NULL, 5, NULL, 39.84, 1, '2026-07-03 15:00:00'),
(158, 68, 134, 125, 'Sodalita 073', 'created', NULL, 4, NULL, 42.59, 1, '2026-07-04 09:00:00'),
(159, 68, 135, 58, 'Incenso de Alecrim 006', 'created', NULL, 1, NULL, 135.14, 1, '2026-07-04 09:00:00'),
(160, 69, 136, 91, 'Pano de Cabeça Azul 039', 'created', NULL, 5, NULL, 112.26, 1, '2026-07-05 03:00:00'),
(161, 69, 137, 103, 'Vela Branca 051', 'created', NULL, 5, NULL, 86.79, 1, '2026-07-05 03:00:00'),
(162, 70, 138, 83, 'Defumador de Benjoim 031', 'created', NULL, 1, NULL, 129.64, 1, '2026-07-05 21:00:00'),
(163, 70, 139, 82, 'Defumador de Arruda 030', 'created', NULL, 4, NULL, 99.34, 1, '2026-07-05 21:00:00'),
(164, 70, 140, 96, 'Saia Preta 044', 'created', NULL, 3, NULL, 133.21, 1, '2026-07-05 21:00:00'),
(165, 70, 141, 99, 'Camiseta Branca 047', 'created', NULL, 3, NULL, 21.39, 1, '2026-07-05 21:00:00'),
(166, 70, 141, 99, 'Camiseta Branca 047', 'updated', 3, 5, 21.39, 21.39, 1, '2026-07-05 21:30:00'),
(167, 71, 142, 105, 'Vela Verde 053', 'created', NULL, 3, NULL, 83.47, 1, '2026-07-06 15:00:00'),
(168, 71, 143, 117, 'Quartzo Rosa 065', 'created', NULL, 5, NULL, 73.76, 1, '2026-07-06 15:00:00'),
(169, 71, 144, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 4, NULL, 78.96, 1, '2026-07-06 15:00:00'),
(170, 71, 144, 89, 'Pano de Cabeça Branco 037', 'updated', 4, 6, 78.96, 78.96, 1, '2026-07-06 15:30:00'),
(171, 71, 145, 109, 'Incenso de Arruda 057', 'created', NULL, 2, NULL, 39.84, 1, '2026-07-06 15:00:00'),
(172, 72, 146, 59, 'Incenso de Arruda 007', 'created', NULL, 4, NULL, 59.71, 1, '2026-07-07 09:00:00'),
(173, 72, 147, 143, 'Porta Guia Preto 091', 'created', NULL, 5, NULL, 126.21, 1, '2026-07-07 09:00:00'),
(174, 72, 148, 61, 'Incenso de Canela 009', 'created', NULL, 4, NULL, 120.20, 1, '2026-07-07 09:00:00'),
(175, 73, 149, 135, 'Erva Arruda 083', 'created', NULL, 3, NULL, 132.64, 1, '2026-07-08 03:00:00'),
(176, 73, 150, 123, 'Ágata Verde 071', 'created', NULL, 5, NULL, 61.77, 1, '2026-07-08 03:00:00'),
(177, 74, 151, 112, 'Banho de Guiné 060', 'created', NULL, 2, NULL, 56.65, 1, '2026-07-08 21:00:00'),
(178, 75, 152, 88, 'Erva Alfazema 036', 'created', NULL, 1, NULL, 67.29, 1, '2026-07-09 15:00:00'),
(179, 75, 153, 97, 'Saia Vermelha 045', 'created', NULL, 4, NULL, 66.18, 1, '2026-07-09 15:00:00'),
(180, 75, 153, 97, 'Saia Vermelha 045', 'updated', 4, 1, 66.18, 66.18, 1, '2026-07-09 15:30:00'),
(181, 75, 154, 120, 'Turmalina Negra 068', 'created', NULL, 4, NULL, 38.64, 1, '2026-07-09 15:00:00'),
(182, 75, 155, 58, 'Incenso de Alecrim 006', 'created', NULL, 2, NULL, 135.14, 1, '2026-07-09 15:00:00'),
(183, 75, 155, 58, 'Incenso de Alecrim 006', 'updated', 2, 3, 135.14, 135.14, 1, '2026-07-09 15:30:00'),
(184, 76, 156, 81, 'Defumador de Alecrim 029', 'created', NULL, 3, NULL, 73.37, 1, '2026-07-10 09:00:00'),
(185, 76, 157, 75, 'Sodalita 023', 'created', NULL, 2, NULL, 77.95, 1, '2026-07-10 09:00:00'),
(186, 76, 158, 148, 'Calça Branca 096', 'created', NULL, 2, NULL, 80.98, 1, '2026-07-10 09:00:00'),
(187, 76, 159, 144, 'Porta Guia Vermelho 092', 'created', NULL, 5, NULL, 136.06, 1, '2026-07-10 09:00:00'),
(188, 77, 160, 90, 'Pano de Cabeça Vermelho 038', 'created', NULL, 3, NULL, 31.49, 1, '2026-07-11 03:00:00'),
(189, 77, 160, 90, 'Pano de Cabeça Vermelho 038', 'updated', 3, 1, 31.49, 31.49, 1, '2026-07-11 03:30:00'),
(190, 77, 161, 143, 'Porta Guia Preto 091', 'created', NULL, 1, NULL, 126.21, 1, '2026-07-11 03:00:00'),
(191, 77, 161, 143, 'Porta Guia Preto 091', 'updated', 1, 2, 126.21, 126.21, 1, '2026-07-11 03:30:00'),
(192, 78, 162, 134, 'Erva Alecrim 082', 'created', NULL, 1, NULL, 34.39, 1, '2026-07-11 21:00:00'),
(193, 78, 163, 81, 'Defumador de Alecrim 029', 'created', NULL, 2, NULL, 73.37, 1, '2026-07-11 21:00:00'),
(194, 78, 164, 96, 'Saia Preta 044', 'created', NULL, 3, NULL, 133.21, 1, '2026-07-11 21:00:00'),
(195, 79, 165, 72, 'Citrino 020', 'created', NULL, 2, NULL, 105.14, 1, '2026-07-12 15:00:00'),
(196, 79, 166, 105, 'Vela Verde 053', 'created', NULL, 3, NULL, 83.47, 1, '2026-07-12 15:00:00'),
(197, 79, 167, 79, 'Guia Vermelha 027', 'created', NULL, 3, NULL, 13.37, 1, '2026-07-12 15:00:00'),
(198, 79, 168, 86, 'Erva Guiné 034', 'created', NULL, 5, NULL, 121.21, 1, '2026-07-12 15:00:00'),
(199, 79, 169, 147, 'Saia Vermelha 095', 'created', NULL, 1, NULL, 60.91, 1, '2026-07-12 15:00:00'),
(200, 79, 169, 147, 'Saia Vermelha 095', 'updated', 1, 5, 60.91, 60.91, 1, '2026-07-12 15:30:00'),
(201, 80, 170, 94, 'Porta Guia Vermelho 042', 'created', NULL, 2, NULL, 105.90, 1, '2026-07-13 09:00:00'),
(202, 80, 171, 115, 'Banho de Alfazema 063', 'created', NULL, 5, NULL, 131.01, 1, '2026-07-13 09:00:00'),
(203, 81, 172, 107, 'Vela Roxa 055', 'created', NULL, 5, NULL, 147.58, 1, '2026-07-14 03:00:00'),
(204, 81, 172, 107, 'Vela Roxa 055', 'updated', 5, 2, 147.58, 147.58, 1, '2026-07-14 03:30:00'),
(205, 81, 173, 126, 'Guia Branca 074', 'created', NULL, 4, NULL, 135.17, 1, '2026-07-14 03:00:00'),
(206, 81, 174, 84, 'Erva Alecrim 032', 'created', NULL, 3, NULL, 57.96, 1, '2026-07-14 03:00:00'),
(207, 81, 175, 133, 'Defumador de Benjoim 081', 'created', NULL, 2, NULL, 129.33, 1, '2026-07-14 03:00:00'),
(208, 81, 176, 115, 'Banho de Alfazema 063', 'created', NULL, 3, NULL, 131.01, 1, '2026-07-14 03:00:00'),
(209, 82, 177, 120, 'Turmalina Negra 068', 'created', NULL, 2, NULL, 38.64, 1, '2026-07-14 21:00:00'),
(210, 82, 177, 120, 'Turmalina Negra 068', 'updated', 2, 5, 38.64, 38.64, 1, '2026-07-14 21:30:00'),
(211, 82, 178, 148, 'Calça Branca 096', 'created', NULL, 2, NULL, 80.98, 1, '2026-07-14 21:00:00'),
(212, 82, 179, 126, 'Guia Branca 074', 'created', NULL, 4, NULL, 135.17, 1, '2026-07-14 21:00:00'),
(213, 82, 180, 54, 'Vela Azul 002', 'created', NULL, 5, NULL, 43.37, 1, '2026-07-14 21:00:00'),
(214, 82, 181, 66, 'Quartzo Branco 014', 'created', NULL, 3, NULL, 61.05, 1, '2026-07-14 21:00:00'),
(215, 83, 182, 84, 'Erva Alecrim 032', 'created', NULL, 3, NULL, 57.96, 1, '2026-07-15 15:00:00'),
(216, 83, 182, 84, 'Erva Alecrim 032', 'updated', 3, 5, 57.96, 57.96, 1, '2026-07-15 15:30:00'),
(217, 83, 183, 103, 'Vela Branca 051', 'created', NULL, 5, NULL, 86.79, 1, '2026-07-15 15:00:00'),
(218, 83, 183, 103, 'Vela Branca 051', 'updated', 5, 4, 86.79, 86.79, 1, '2026-07-15 15:30:00'),
(219, 84, 184, 56, 'Vela Vermelha 004', 'created', NULL, 1, NULL, 53.17, 1, '2026-07-16 09:00:00'),
(220, 84, 185, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 1, NULL, 78.96, 1, '2026-07-16 09:00:00'),
(221, 84, 186, 125, 'Sodalita 073', 'created', NULL, 5, NULL, 42.59, 1, '2026-07-16 09:00:00'),
(222, 85, 187, 100, 'Camiseta Preta 048', 'created', NULL, 3, NULL, 118.37, 1, '2026-07-17 03:00:00'),
(223, 85, 187, 100, 'Camiseta Preta 048', 'updated', 3, 5, 118.37, 118.37, 1, '2026-07-17 03:30:00'),
(224, 85, 188, 137, 'Erva Manjericão 085', 'created', NULL, 5, NULL, 134.37, 1, '2026-07-17 03:00:00'),
(225, 85, 189, 148, 'Calça Branca 096', 'created', NULL, 4, NULL, 80.98, 1, '2026-07-17 03:00:00'),
(226, 86, 190, 66, 'Quartzo Branco 014', 'created', NULL, 5, NULL, 61.05, 1, '2026-07-17 21:00:00'),
(227, 86, 191, 112, 'Banho de Guiné 060', 'created', NULL, 1, NULL, 56.65, 1, '2026-07-17 21:00:00'),
(228, 86, 192, 87, 'Erva Manjericão 035', 'created', NULL, 1, NULL, 50.08, 1, '2026-07-17 21:00:00'),
(229, 87, 193, 122, 'Citrino 070', 'created', NULL, 4, NULL, 85.28, 1, '2026-07-18 15:00:00'),
(230, 88, 194, 98, 'Calça Branca 046', 'created', NULL, 5, NULL, 112.44, 1, '2026-07-19 09:00:00'),
(231, 88, 195, 150, 'Camiseta Preta 098', 'created', NULL, 2, NULL, 111.60, 1, '2026-07-19 09:00:00'),
(232, 88, 196, 85, 'Erva Arruda 033', 'created', NULL, 3, NULL, 50.22, 1, '2026-07-19 09:00:00'),
(233, 88, 197, 58, 'Incenso de Alecrim 006', 'created', NULL, 1, NULL, 135.14, 1, '2026-07-19 09:00:00'),
(234, 88, 197, 58, 'Incenso de Alecrim 006', 'updated', 1, 4, 135.14, 135.14, 1, '2026-07-19 09:30:00'),
(235, 88, 198, 106, 'Vela Vermelha 054', 'created', NULL, 4, NULL, 19.75, 1, '2026-07-19 09:00:00'),
(236, 89, 199, 137, 'Erva Manjericão 085', 'created', NULL, 2, NULL, 134.37, 1, '2026-07-20 03:00:00'),
(237, 89, 199, 137, 'Erva Manjericão 085', 'updated', 2, 4, 134.37, 134.37, 1, '2026-07-20 03:30:00'),
(238, 89, 200, 111, 'Incenso de Canela 059', 'created', NULL, 5, NULL, 32.94, 1, '2026-07-20 03:00:00'),
(239, 89, 201, 97, 'Saia Vermelha 045', 'created', NULL, 1, NULL, 66.18, 1, '2026-07-20 03:00:00'),
(240, 89, 201, 97, 'Saia Vermelha 045', 'updated', 1, 3, 66.18, 66.18, 1, '2026-07-20 03:30:00'),
(241, 89, 202, 136, 'Erva Guiné 084', 'created', NULL, 1, NULL, 64.18, 1, '2026-07-20 03:00:00'),
(242, 90, 203, 74, 'Pedra da Lua 022', 'created', NULL, 3, NULL, 28.86, 1, '2026-07-20 21:00:00'),
(243, 91, 204, 77, 'Guia Azul 025', 'created', NULL, 1, NULL, 59.98, 1, '2026-07-21 15:00:00'),
(244, 91, 204, 77, 'Guia Azul 025', 'updated', 1, 4, 59.98, 59.98, 1, '2026-07-21 15:30:00'),
(245, 91, 205, 68, 'Ametista 016', 'created', NULL, 5, NULL, 21.47, 1, '2026-07-21 15:00:00'),
(246, 92, 206, 66, 'Quartzo Branco 014', 'created', NULL, 1, NULL, 61.05, 1, '2026-07-22 09:00:00'),
(247, 92, 207, 150, 'Camiseta Preta 098', 'created', NULL, 1, NULL, 111.60, 1, '2026-07-22 09:00:00'),
(248, 93, 208, 92, 'Porta Guia Branco 040', 'created', NULL, 4, NULL, 123.34, 1, '2026-07-23 03:00:00'),
(249, 94, 209, 95, 'Saia Branca 043', 'created', NULL, 3, NULL, 79.30, 1, '2026-07-23 21:00:00'),
(250, 95, 210, 65, 'Banho de Alfazema 013', 'created', NULL, 3, NULL, 73.14, 1, '2026-07-24 15:00:00'),
(251, 95, 211, 67, 'Quartzo Rosa 015', 'created', NULL, 4, NULL, 51.20, 1, '2026-07-24 15:00:00'),
(252, 95, 211, 67, 'Quartzo Rosa 015', 'updated', 4, 3, 51.20, 51.20, 1, '2026-07-24 15:30:00'),
(253, 95, 212, 123, 'Ágata Verde 071', 'created', NULL, 5, NULL, 61.77, 1, '2026-07-24 15:00:00'),
(254, 95, 212, 123, 'Ágata Verde 071', 'updated', 5, 4, 61.77, 61.77, 1, '2026-07-24 15:30:00'),
(255, 96, 213, 76, 'Guia Branca 024', 'created', NULL, 2, NULL, 36.78, 1, '2026-07-25 09:00:00'),
(256, 96, 214, 143, 'Porta Guia Preto 091', 'created', NULL, 4, NULL, 126.21, 1, '2026-07-25 09:00:00'),
(257, 96, 215, 94, 'Porta Guia Vermelho 042', 'created', NULL, 3, NULL, 105.90, 1, '2026-07-25 09:00:00'),
(258, 96, 215, 94, 'Porta Guia Vermelho 042', 'updated', 3, 4, 105.90, 105.90, 1, '2026-07-25 09:30:00'),
(259, 97, 216, 56, 'Vela Vermelha 004', 'created', NULL, 3, NULL, 53.17, 1, '2026-07-26 03:00:00'),
(260, 97, 216, 56, 'Vela Vermelha 004', 'updated', 3, 6, 53.17, 53.17, 1, '2026-07-26 03:30:00'),
(261, 97, 217, 98, 'Calça Branca 046', 'created', NULL, 1, NULL, 112.44, 1, '2026-07-26 03:00:00'),
(262, 98, 218, 93, 'Porta Guia Preto 041', 'created', NULL, 4, NULL, 61.76, 1, '2026-07-26 21:00:00'),
(263, 98, 219, 131, 'Defumador de Alecrim 079', 'created', NULL, 4, NULL, 68.65, 1, '2026-07-26 21:00:00'),
(264, 98, 220, 112, 'Banho de Guiné 060', 'created', NULL, 1, NULL, 56.65, 1, '2026-07-26 21:00:00'),
(265, 99, 221, 116, 'Quartzo Branco 064', 'created', NULL, 4, NULL, 18.33, 1, '2026-07-27 15:00:00'),
(266, 99, 222, 82, 'Defumador de Arruda 030', 'created', NULL, 5, NULL, 99.34, 1, '2026-07-27 15:00:00'),
(267, 99, 223, 151, 'Fio de Contas Branco 099', 'created', NULL, 3, NULL, 15.46, 1, '2026-07-27 15:00:00'),
(268, 99, 224, 119, 'Olho de Tigre 067', 'created', NULL, 3, NULL, 40.03, 1, '2026-07-27 15:00:00'),
(269, 100, 225, 125, 'Sodalita 073', 'created', NULL, 4, NULL, 42.59, 1, '2026-07-28 09:00:00'),
(270, 100, 226, 97, 'Saia Vermelha 045', 'created', NULL, 3, NULL, 66.18, 1, '2026-07-28 09:00:00'),
(271, 100, 226, 97, 'Saia Vermelha 045', 'updated', 3, 1, 66.18, 66.18, 1, '2026-07-28 09:30:00'),
(272, 100, 227, 71, 'Jaspe Vermelho 019', 'created', NULL, 1, NULL, 35.17, 1, '2026-07-28 09:00:00'),
(273, 100, 228, 66, 'Quartzo Branco 014', 'created', NULL, 5, NULL, 61.05, 1, '2026-07-28 09:00:00'),
(274, 100, 228, 66, 'Quartzo Branco 014', 'updated', 5, 6, 61.05, 61.05, 1, '2026-07-28 09:30:00'),
(275, 100, 229, 84, 'Erva Alecrim 032', 'created', NULL, 3, NULL, 57.96, 1, '2026-07-28 09:00:00'),
(276, 100, 229, 84, 'Erva Alecrim 032', 'updated', 3, 5, 57.96, 57.96, 1, '2026-07-28 09:30:00'),
(277, 101, 230, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 1, NULL, 78.96, 1, '2026-07-29 03:00:00'),
(278, 102, 231, 57, 'Vela Roxa 005', 'created', NULL, 5, NULL, 35.97, 1, '2026-07-29 21:00:00'),
(279, 102, 231, 57, 'Vela Roxa 005', 'updated', 5, 4, 35.97, 35.97, 1, '2026-07-29 21:30:00'),
(280, 103, 232, 88, 'Erva Alfazema 036', 'created', NULL, 4, NULL, 67.29, 1, '2026-07-30 15:00:00'),
(281, 103, 233, 120, 'Turmalina Negra 068', 'created', NULL, 4, NULL, 38.64, 1, '2026-07-30 15:00:00'),
(282, 103, 234, 118, 'Ametista 066', 'created', NULL, 3, NULL, 9.86, 1, '2026-07-30 15:00:00'),
(283, 103, 235, 138, 'Erva Alfazema 086', 'created', NULL, 1, NULL, 39.61, 1, '2026-07-30 15:00:00'),
(284, 104, 236, 127, 'Guia Azul 075', 'created', NULL, 2, NULL, 80.91, 1, '2026-07-31 09:00:00'),
(285, 104, 236, 127, 'Guia Azul 075', 'updated', 2, 4, 80.91, 80.91, 1, '2026-07-31 09:30:00'),
(286, 104, 237, 103, 'Vela Branca 051', 'created', NULL, 2, NULL, 86.79, 1, '2026-07-31 09:00:00'),
(287, 104, 238, 138, 'Erva Alfazema 086', 'created', NULL, 2, NULL, 39.61, 1, '2026-07-31 09:00:00'),
(288, 104, 239, 84, 'Erva Alecrim 032', 'created', NULL, 5, NULL, 57.96, 1, '2026-07-31 09:00:00'),
(289, 104, 240, 112, 'Banho de Guiné 060', 'created', NULL, 1, NULL, 56.65, 1, '2026-07-31 09:00:00'),
(290, 105, 241, 106, 'Vela Vermelha 054', 'created', NULL, 3, NULL, 19.75, 1, '2026-08-01 03:00:00'),
(291, 105, 242, 65, 'Banho de Alfazema 013', 'created', NULL, 5, NULL, 73.14, 1, '2026-08-01 03:00:00'),
(292, 105, 243, 91, 'Pano de Cabeça Azul 039', 'created', NULL, 1, NULL, 112.26, 1, '2026-08-01 03:00:00'),
(293, 105, 243, 91, 'Pano de Cabeça Azul 039', 'updated', 1, 6, 112.26, 112.26, 1, '2026-08-01 03:30:00'),
(294, 105, 244, 148, 'Calça Branca 096', 'created', NULL, 5, NULL, 80.98, 1, '2026-08-01 03:00:00'),
(295, 105, 245, 73, 'Ágata Verde 021', 'created', NULL, 4, NULL, 49.26, 1, '2026-08-01 03:00:00'),
(296, 106, 246, 89, 'Pano de Cabeça Branco 037', 'created', NULL, 1, NULL, 78.96, 1, '2026-08-01 21:00:00'),
(297, 106, 247, 106, 'Vela Vermelha 054', 'created', NULL, 2, NULL, 19.75, 1, '2026-08-01 21:00:00'),
(298, 106, 248, 80, 'Guia Preta 028', 'created', NULL, 1, NULL, 26.22, 1, '2026-08-01 21:00:00'),
(299, 106, 249, 57, 'Vela Roxa 005', 'created', NULL, 3, NULL, 35.97, 1, '2026-08-01 21:00:00'),
(300, 107, 250, 113, 'Banho de Arruda 061', 'created', NULL, 1, NULL, 48.35, 1, '2026-08-02 15:00:00'),
(301, 107, 251, 146, 'Saia Preta 094', 'created', NULL, 5, NULL, 99.35, 1, '2026-08-02 15:00:00'),
(302, 107, 252, 59, 'Incenso de Arruda 007', 'created', NULL, 4, NULL, 59.71, 1, '2026-08-02 15:00:00'),
(303, 108, 253, 145, 'Saia Branca 093', 'created', NULL, 1, NULL, 82.06, 1, '2026-08-03 09:00:00'),
(304, 109, 254, 117, 'Quartzo Rosa 065', 'created', NULL, 5, NULL, 73.76, 1, '2026-08-04 03:00:00'),
(305, 109, 255, 74, 'Pedra da Lua 022', 'created', NULL, 2, NULL, 28.86, 1, '2026-08-04 03:00:00'),
(306, 109, 256, 115, 'Banho de Alfazema 063', 'created', NULL, 3, NULL, 131.01, 1, '2026-08-04 03:00:00'),
(307, 109, 257, 69, 'Olho de Tigre 017', 'created', NULL, 2, NULL, 20.65, 1, '2026-08-04 03:00:00'),
(308, 109, 258, 84, 'Erva Alecrim 032', 'created', NULL, 1, NULL, 57.96, 1, '2026-08-04 03:00:00'),
(309, 109, 258, 84, 'Erva Alecrim 032', 'updated', 1, 6, 57.96, 57.96, 1, '2026-08-04 03:30:00'),
(310, 110, 259, 79, 'Guia Vermelha 027', 'created', NULL, 5, NULL, 13.37, 1, '2026-08-04 21:00:00'),
(311, 110, 259, 79, 'Guia Vermelha 027', 'updated', 5, 4, 13.37, 13.37, 1, '2026-08-04 21:30:00'),
(312, 110, 260, 106, 'Vela Vermelha 054', 'created', NULL, 1, NULL, 19.75, 1, '2026-08-04 21:00:00'),
(313, 110, 261, 88, 'Erva Alfazema 036', 'created', NULL, 4, NULL, 67.29, 1, '2026-08-04 21:00:00'),
(314, 110, 262, 77, 'Guia Azul 025', 'created', NULL, 2, NULL, 59.98, 1, '2026-08-04 21:00:00'),
(315, 110, 263, 119, 'Olho de Tigre 067', 'created', NULL, 5, NULL, 40.03, 1, '2026-08-04 21:00:00'),
(316, 111, 264, 128, 'Guia Verde 076', 'created', NULL, 4, NULL, 133.87, 1, '2026-08-05 15:00:00'),
(317, 111, 265, 119, 'Olho de Tigre 067', 'created', NULL, 1, NULL, 40.03, 1, '2026-08-05 15:00:00'),
(318, 111, 265, 119, 'Olho de Tigre 067', 'updated', 1, 2, 40.03, 40.03, 1, '2026-08-05 15:30:00'),
(319, 111, 266, 92, 'Porta Guia Branco 040', 'created', NULL, 4, NULL, 123.34, 1, '2026-08-05 15:00:00'),
(320, 112, 267, 146, 'Saia Preta 094', 'created', NULL, 2, NULL, 99.35, 1, '2026-08-06 09:00:00'),
(321, 112, 268, 80, 'Guia Preta 028', 'created', NULL, 2, NULL, 26.22, 1, '2026-08-06 09:00:00'),
(322, 112, 269, 107, 'Vela Roxa 055', 'created', NULL, 2, NULL, 147.58, 1, '2026-08-06 09:00:00'),
(323, 112, 270, 123, 'Ágata Verde 071', 'created', NULL, 5, NULL, 61.77, 1, '2026-08-06 09:00:00'),
(324, 113, 271, 92, 'Porta Guia Branco 040', 'created', NULL, 2, NULL, 123.34, 1, '2026-08-07 03:00:00'),
(325, 113, 271, 92, 'Porta Guia Branco 040', 'updated', 2, 5, 123.34, 123.34, 1, '2026-08-07 03:30:00'),
(326, 113, 272, 149, 'Camiseta Branca 097', 'created', NULL, 3, NULL, 121.99, 1, '2026-08-07 03:00:00'),
(327, 113, 272, 149, 'Camiseta Branca 097', 'updated', 3, 2, 121.99, 121.99, 1, '2026-08-07 03:30:00'),
(328, 113, 273, 122, 'Citrino 070', 'created', NULL, 2, NULL, 85.28, 1, '2026-08-07 03:00:00'),
(329, 113, 274, 96, 'Saia Preta 044', 'created', NULL, 2, NULL, 133.21, 1, '2026-08-07 03:00:00'),
(330, 113, 275, 144, 'Porta Guia Vermelho 092', 'created', NULL, 3, NULL, 136.06, 1, '2026-08-07 03:00:00'),
(331, 114, 276, 108, 'Incenso de Alecrim 056', 'created', NULL, 2, NULL, 115.91, 1, '2026-08-07 21:00:00'),
(332, 114, 276, 108, 'Incenso de Alecrim 056', 'updated', 2, 4, 115.91, 115.91, 1, '2026-08-07 21:30:00'),
(333, 115, 277, 112, 'Banho de Guiné 060', 'created', NULL, 4, NULL, 56.65, 1, '2026-08-08 15:00:00'),
(334, 115, 278, 129, 'Guia Vermelha 077', 'created', NULL, 2, NULL, 95.49, 1, '2026-08-08 15:00:00'),
(335, 115, 278, 129, 'Guia Vermelha 077', 'updated', 2, 6, 95.49, 95.49, 1, '2026-08-08 15:30:00'),
(336, 115, 279, 60, 'Incenso de Lavanda 008', 'created', NULL, 4, NULL, 125.31, 1, '2026-08-08 15:00:00'),
(337, 116, 280, 87, 'Erva Manjericão 035', 'created', NULL, 1, NULL, 50.08, 1, '2026-08-09 09:00:00'),
(338, 116, 281, 122, 'Citrino 070', 'created', NULL, 3, NULL, 85.28, 1, '2026-08-09 09:00:00'),
(339, 116, 282, 107, 'Vela Roxa 055', 'created', NULL, 1, NULL, 147.58, 1, '2026-08-09 09:00:00'),
(340, 116, 283, 71, 'Jaspe Vermelho 019', 'created', NULL, 4, NULL, 35.17, 1, '2026-08-09 09:00:00'),
(341, 116, 284, 64, 'Banho de Manjericão 012', 'created', NULL, 2, NULL, 42.51, 1, '2026-08-09 09:00:00'),
(342, 117, 285, 74, 'Pedra da Lua 022', 'created', NULL, 1, NULL, 28.86, 1, '2026-08-10 03:00:00'),
(343, 118, 286, 78, 'Guia Verde 026', 'created', NULL, 1, NULL, 80.45, 1, '2026-08-10 21:00:00'),
(344, 119, 287, 74, 'Pedra da Lua 022', 'created', NULL, 1, NULL, 28.86, 1, '2026-08-11 15:00:00'),
(345, 119, 288, 138, 'Erva Alfazema 086', 'created', NULL, 4, NULL, 39.61, 1, '2026-08-11 15:00:00'),
(346, 120, 289, 64, 'Banho de Manjericão 012', 'created', NULL, 3, NULL, 42.51, 1, '2026-08-12 09:00:00'),
(347, 121, 290, 142, 'Porta Guia Branco 090', 'created', NULL, 4, NULL, 18.87, 1, '2026-08-13 03:00:00'),
(348, 121, 290, 142, 'Porta Guia Branco 090', 'updated', 4, 1, 18.87, 18.87, 1, '2026-08-13 03:30:00'),
(349, 122, 291, 85, 'Erva Arruda 033', 'created', NULL, 4, NULL, 50.22, 1, '2026-08-13 21:00:00'),
(350, 122, 291, 85, 'Erva Arruda 033', 'updated', 4, 2, 50.22, 50.22, 1, '2026-08-13 21:30:00'),
(351, 122, 292, 139, 'Pano de Cabeça Branco 087', 'created', NULL, 1, NULL, 123.09, 1, '2026-08-13 21:00:00'),
(352, 122, 293, 119, 'Olho de Tigre 067', 'created', NULL, 1, NULL, 40.03, 1, '2026-08-13 21:00:00'),
(353, 123, 294, 103, 'Vela Branca 051', 'created', NULL, 1, NULL, 86.79, 1, '2026-08-14 15:00:00');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;


--
-- AUTO_INCREMENT de tabela `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT de tabela `sale_item_changes`
--
ALTER TABLE `sale_item_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

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
