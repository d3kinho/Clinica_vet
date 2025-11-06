-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/11/2025 às 21:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clinicavet`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `animais`
--

CREATE TABLE `animais` (
  `id_animal` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `especie` varchar(50) DEFAULT NULL,
  `raca` varchar(80) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `sexo` enum('M','F','I') DEFAULT 'I',
  `id_cliente` int(11) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `status` enum('Liberado','Em Tratamento','Internado') NOT NULL DEFAULT 'Liberado',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `animais`
--

INSERT INTO `animais` (`id_animal`, `nome`, `especie`, `raca`, `idade`, `sexo`, `id_cliente`, `foto_url`, `status`, `criado_em`) VALUES
(1, 'Tunico', 'Gato', '', 4, 'M', NULL, 'uploads/animais/1762458138_64e1da42-a60d-4811-a753-21b87b0846a5.jfif', 'Liberado', '2025-11-06 19:42:18'),
(2, 'zé paçoca', 'Cachorro', '', 1, 'M', 6, 'uploads/animais/1762458521_f602cf28-247a-4c55-8dcd-23d4020fe0ac.jfif', 'Liberado', '2025-11-06 19:48:41'),
(3, 'Thorzin', 'Cachorro', 'Vira lata', 3, 'M', 8, 'uploads/animais/1762458619_762f7646-eb4e-4353-a639-0bbf7891c396.jfif', 'Liberado', '2025-11-06 19:50:19'),
(4, 'Luna', 'Gato', 'Alguma', 4, 'F', 7, 'uploads/animais/1762458701_84026bf8-dc57-49ee-9c1e-b65594826599.jfif', 'Liberado', '2025-11-06 19:51:41'),
(6, 'Garfieldd', 'Gato', 'Laranja', 4, 'M', 7, 'uploads/animais/1762458772_eedc8857-c267-486a-af56-0fbf9c1494ac.jfif', 'Liberado', '2025-11-06 19:52:52'),
(7, 'Snoopy', 'Gato', 'Fake siames', 4, 'M', 7, 'uploads/animais/1762458819_274f534c-8c8a-41a4-8b55-f6a548590579.jfif', 'Liberado', '2025-11-06 19:53:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `criado_em`) VALUES
(4, 'testee', '123.456.789-10', '(21) 12345-6789', '123@hotmail.com', 'Rua adajkag, 17', '2025-11-06 17:37:48'),
(5, 'Zé pequeno', '123.456.789-10', '(21) 94002-8922', 'ze@gmail.com', 'CDD', '2025-11-06 19:44:40'),
(6, 'Kauã', '000.000.000-00', '(00) 00000-0000', 'kaua@gmail.com', 'Rua ali de cima, 67', '2025-11-06 19:46:08'),
(7, 'Yasmin ', '000.000.000-00', '(00) 00000-0000', 'yasmin@gmail.com', 'Muito longe, 72', '2025-11-06 19:47:46'),
(8, 'Adriel', '123.456.789-10', '(21) 12345-6789', 'adriel@hotmail.com', 'Topo da vila aliança, 17', '2025-11-06 19:49:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id_consulta` int(11) NOT NULL,
  `data_consulta` date NOT NULL,
  `hora_consulta` time DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `id_animal` int(11) DEFAULT NULL,
  `id_vet` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `veterinarios`
--

CREATE TABLE `veterinarios` (
  `id_vet` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `crmv` varchar(40) DEFAULT NULL,
  `especialidade` varchar(120) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`id_animal`),
  ADD KEY `fk_animais_cliente` (`id_cliente`),
  ADD KEY `idx_animais_nome` (`nome`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `idx_clientes_nome` (`nome`);

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `fk_consulta_animal` (`id_animal`),
  ADD KEY `fk_consulta_vet` (`id_vet`),
  ADD KEY `idx_consultas_data` (`data_consulta`);

--
-- Índices de tabela `veterinarios`
--
ALTER TABLE `veterinarios`
  ADD PRIMARY KEY (`id_vet`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `veterinarios`
--
ALTER TABLE `veterinarios`
  MODIFY `id_vet` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `animais`
--
ALTER TABLE `animais`
  ADD CONSTRAINT `fk_animais_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `fk_consulta_animal` FOREIGN KEY (`id_animal`) REFERENCES `animais` (`id_animal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_consulta_vet` FOREIGN KEY (`id_vet`) REFERENCES `veterinarios` (`id_vet`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
