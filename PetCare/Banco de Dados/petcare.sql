-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 03-Nov-2024 às 22:55
-- Versão do servidor: 5.7.36
-- versão do PHP: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `petcare`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alimentacao`
--

CREATE TABLE `alimentacao` (
  `idAlimentacao` int(11) NOT NULL,
  `marcaRacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtdDiaria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horariosRefeicoes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anotacoes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `idPet` int(11) DEFAULT NULL,
  `dataCadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `alimentacao`
--

INSERT INTO `alimentacao` (`idAlimentacao`, `marcaRacao`, `qtdDiaria`, `horariosRefeicoes`, `anotacoes`, `idTutor`, `idPet`, `dataCadastro`) VALUES
(14, 'teste', '200', 'teste', 'teste', 40, 63, '2024-11-03 18:47:38');

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

CREATE TABLE `animal` (
  `idPet` int(11) NOT NULL,
  `nomePet` varchar(255) NOT NULL,
  `dataNascimentoPet` date DEFAULT NULL,
  `especie` varchar(100) DEFAULT NULL,
  `raca` varchar(100) DEFAULT NULL,
  `sexo` tinyint(1) DEFAULT NULL,
  `microchip` tinyint(1) DEFAULT NULL,
  `castracao` tinyint(1) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `animal`
--

INSERT INTO `animal` (`idPet`, `nomePet`, `dataNascimentoPet`, `especie`, `raca`, `sexo`, `microchip`, `castracao`, `idTutor`) VALUES
(63, 'teste', '2020-01-01', 'Cão', 'Akita', 0, 0, 0, 40);

-- --------------------------------------------------------

--
-- Estrutura da tabela `controleparasitario`
--

CREATE TABLE `controleparasitario` (
  `idControle` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `nomeMedicacao` varchar(255) DEFAULT NULL,
  `dataAdministrada` date DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `frequenciaUso` varchar(255) DEFAULT NULL,
  `anotacoes` text,
  `dataCadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `controleparasitario`
--

INSERT INTO `controleparasitario` (`idControle`, `idPet`, `idTutor`, `nomeMedicacao`, `dataAdministrada`, `categoria`, `frequenciaUso`, `anotacoes`, `dataCadastro`) VALUES
(5, 63, 40, 'teste', '2020-01-01', 'interno', 'continuo', 'teste', '2024-11-03 18:48:37'),
(6, 63, 40, 'teste', '2020-03-02', 'externo', 'naoContinuo', '', '2024-11-03 22:03:09'),
(7, 63, 40, 'testa', '2023-03-02', 'interno', 'continuo', 'teste', '2024-11-03 22:03:54');

-- --------------------------------------------------------

--
-- Estrutura da tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `idExercicios` int(11) NOT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `idPet` int(11) DEFAULT NULL,
  `tipoExercicio` varchar(255) DEFAULT NULL,
  `dataExercicio` date DEFAULT NULL,
  `qtdVezesDia` int(11) DEFAULT NULL,
  `tempoMedio` int(11) DEFAULT NULL,
  `observacoes` text,
  `dataCadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `exercicios`
--

INSERT INTO `exercicios` (`idExercicios`, `idTutor`, `idPet`, `tipoExercicio`, `dataExercicio`, `qtdVezesDia`, `tempoMedio`, `observacoes`, `dataCadastro`) VALUES
(8, 40, 63, 'passeio', '2020-02-01', 2, 20, 'teste', '2024-11-03 18:52:02'),
(10, 40, 63, '0', '2020-01-01', 2, 20, 'testando', '2024-11-03 22:44:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `higiene`
--

CREATE TABLE `higiene` (
  `igHigiene` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `dataHigiene` date DEFAULT NULL,
  `anotacoes` text,
  `dataCadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tutor`
--

CREATE TABLE `tutor` (
  `idTutor` int(11) NOT NULL,
  `nomeTutor` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tutor`
--

INSERT INTO `tutor` (`idTutor`, `nomeTutor`, `email`, `senha`) VALUES
(40, 'teste', 'teste@email.com', 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vacinas`
--

CREATE TABLE `vacinas` (
  `idVacinas` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `nomeVacina` varchar(255) DEFAULT NULL,
  `dataAdministrada` date DEFAULT NULL,
  `laboratorio` varchar(255) DEFAULT NULL,
  `lote` varchar(255) DEFAULT NULL,
  `anotacoes` text,
  `dataCadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alimentacao`
--
ALTER TABLE `alimentacao`
  ADD PRIMARY KEY (`idAlimentacao`),
  ADD KEY `idTutor` (`idTutor`),
  ADD KEY `idPet` (`idPet`);

--
-- Índices para tabela `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Índices para tabela `controleparasitario`
--
ALTER TABLE `controleparasitario`
  ADD PRIMARY KEY (`idControle`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Índices para tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`idExercicios`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Índices para tabela `higiene`
--
ALTER TABLE `higiene`
  ADD PRIMARY KEY (`igHigiene`),
  ADD KEY `idTutor` (`idTutor`),
  ADD KEY `idPet` (`idPet`);

--
-- Índices para tabela `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`idTutor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `vacinas`
--
ALTER TABLE `vacinas`
  ADD PRIMARY KEY (`idVacinas`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alimentacao`
--
ALTER TABLE `alimentacao`
  MODIFY `idAlimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `animal`
--
ALTER TABLE `animal`
  MODIFY `idPet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `controleparasitario`
--
ALTER TABLE `controleparasitario`
  MODIFY `idControle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `idExercicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `higiene`
--
ALTER TABLE `higiene`
  MODIFY `igHigiene` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tutor`
--
ALTER TABLE `tutor`
  MODIFY `idTutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `vacinas`
--
ALTER TABLE `vacinas`
  MODIFY `idVacinas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `alimentacao`
--
ALTER TABLE `alimentacao`
  ADD CONSTRAINT `alimentacao_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`),
  ADD CONSTRAINT `fk_alimentacao_animal` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);

--
-- Limitadores para a tabela `controleparasitario`
--
ALTER TABLE `controleparasitario`
  ADD CONSTRAINT `controleparasitario_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`),
  ADD CONSTRAINT `fk_controleparasitario_animal` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD CONSTRAINT `exercicios_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`),
  ADD CONSTRAINT `fk_exercicios_animal` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `higiene`
--
ALTER TABLE `higiene`
  ADD CONSTRAINT `fk_higiene_animal` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`) ON DELETE CASCADE,
  ADD CONSTRAINT `higiene_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);

--
-- Limitadores para a tabela `vacinas`
--
ALTER TABLE `vacinas`
  ADD CONSTRAINT `fk_vacinas_animal` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`) ON DELETE CASCADE,
  ADD CONSTRAINT `vacinas_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
