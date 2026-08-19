-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 02/08/2024 às 09:43
-- Versão do servidor: 8.0.39-0ubuntu0.22.04.1
-- Versão do PHP: 8.1.2-1ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `new_agenda`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_contatos`
--

CREATE TABLE `tb_contatos` (
  `id_contatos` int NOT NULL,
  `nome_contatos` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fone_contatos` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_contatos` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_contatos` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_contatos`
--

INSERT INTO `tb_contatos` (`id_contatos`, `nome_contatos`, `fone_contatos`, `email_contatos`, `foto_contatos`, `id_user`) VALUES
(24, 'Leandro Costa', '85991446499', 'leandro@gmail.com', '6671adc5b0274.jpg', 12),
(25, 'Maria Ester', '88998654321', 'maria@yahoo.com.br', '6671b0a1c2a98.jpg', 11),
(26, 'Leandro Costa', '85991446499', 'leandro@gmail.com', '6672e94be6ea9.jpg', 13),
(27, 'Raposo', '88998654321', 'lucas@gmail.com', '6672e978854aa.jpg', 13),
(28, 'Vinicius da Silva Martins', '85988963257', 'vini@gmail.com', '66abdfa9a71ce.jpg', 14),
(29, 'Yohana Laysa', '8591569875', 'yohana@yahoo.com.br', '66abe0954ee12.jpg', 15),
(30, 'Mário Farney', '85988563256', 'mario@gmail.com', '66acd25577f92.jpg', 14);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `foto_user` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nome_user` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha_user` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `foto_user`, `nome_user`, `email_user`, `senha_user`) VALUES
(11, '6671a58f7d486.jpg', 'Leandro Costa', 'leandro@gmail.com', '$2y$10$oJtpLVHZVymX224N2dfM.ud14mLImZtCJKQMl.IRQIrWT1NljcO3q'),
(12, '6671a859b1046.jpg', 'Ana', 'ana@gmail.com', '$2y$10$/NMHwdnJ6wzjsc3lNDzu6ejxqFEzMNz4ajrXKSDPxjEJv4lXKDZGS'),
(13, '6672e8f568319.jpg', 'Zé Brain', 'zeb@gmail.com', '$2y$10$G6Tc6ZuSNlqECBfZQ3sad.UtlD4uOtpoJ3q0XwBoEhV334Rs55rbm'),
(14, '66abdf184fa75.jpg', 'Leandro Costa', 'leandro@outlook.com', '$2y$10$4l32kJNJqsAw.SiSIFXrFOdHeuND/78zG1uQTFdGdBOlXmlZt.bSG'),
(15, '66abe031583e5.jpg', 'Hugo', 'pedroh@gmail.com', '$2y$10$W1E7RmdnWjBxpO1lkA2Zme/sXsucAW5iLtQjRKrptqGKWkuyGiZRW');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_contatos`
--
ALTER TABLE `tb_contatos`
  ADD PRIMARY KEY (`id_contatos`),
  ADD KEY `fk_id_usuario` (`id_user`);

--
-- Índices de tabela `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_contatos`
--
ALTER TABLE `tb_contatos`
  MODIFY `id_contatos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_contatos`
--
ALTER TABLE `tb_contatos`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
