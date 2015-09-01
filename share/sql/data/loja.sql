-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 01-Set-2015 às 03:45
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `loja`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) COLLATE utf8_swedish_ci NOT NULL,
  `pai` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `pai`) VALUES
(2, 'TELEVISIONS', 1),
(3, 'TUBE', 2),
(4, 'LCD', 2),
(5, 'PLASMA', 2),
(6, 'PORTABLE ELECTRONICS', 1),
(7, 'MP3 PLAYERS', 6),
(8, 'FLASH', 7),
(9, 'CD PLAYERS', 6),
(10, '2 WAY RADIOS', 6),
(11, 'CAMERAS', 0),
(12, 'FILMADORAS', 11),
(13, 'DIVERSOS', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `cpfcnpj` varchar(14) COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(80) COLLATE utf8_swedish_ci DEFAULT NULL,
  `tel` varchar(11) COLLATE utf8_swedish_ci NOT NULL,
  `cep` char(8) COLLATE utf8_swedish_ci NOT NULL,
  `num` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `pass` char(40) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpfcnpj`, `email`, `tel`, `cep`, `num`, `pass`) VALUES
(1, 'Luis Gustavo A', '11111111111', NULL, '11983958277', '04717003', '1128 apto 111', '123'),
(2, 'L. Gustavo A MEE', '11862423000137', 'voipgus@gmail.com', '11983958277', '04717003', '1128 apto 111', '123'),
(3, 'ghgjgjj', '5765765757', 'hgjhhg', '767678687', '22222222', 'jhgjhgh', '55765656'),
(4, 'gdfgdfgdfgdfgdfsg', '345345345345', 'eterterwtert', 'werwerwe', '11111111', 'ewerwe', 'ewtewrterter');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_swedish_ci DEFAULT NULL,
  `favorite` int(11) NOT NULL DEFAULT '0',
  `val` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=60 ;

--
-- Extraindo dados da tabela `contacts`
--

INSERT INTO `contacts` (`id`, `firstname`, `lastname`, `email`, `phone`, `favorite`, `val`) VALUES
(1, 'Camilla dddd', 'Donati', 'ridiculus.mus.Proin@Quisquepurus.co.uk', '(815) 195-5424', 0, 12),
(2, 'Erica uuu', 'Ferraro', 'dui.quis@sollicitudinorcisem.com', '(574) 384-5826', 0, 12),
(24, 'Anna ggg', 'Donati', 'pede@diam.co.uk', '(665) 699-6419', 0, 12),
(26, 'Silvia maezona !!', 'Montanari', 'id.blandit@tempor.org', '(244) 414-7169', 0, 12),
(33, 'Angela ffff', 'Santoro', 'scelerisque@seddictumeleifend.com', '(560) 323-3311', 0, 12),
(34, 'Valerio', 'Valente', 'quis@urnaNuncquis.net', '(481) 200-1813', 0, 12),
(35, 'Federica', 'Rizzo', 'ac.mattis@duiaugueeu.co.uk', '(779) 319-6428', 0, 12),
(36, 'Alessio', 'Farina', 'ipsum.dolor.sit@Sed.co.uk', '(217) 426-6110', 0, 12),
(39, 'Alberto', 'Catalano', 'natoque@nonluctussit.ca', '(924) 436-9002', 0, 12),
(40, 'Luigi', 'Mariani', 'parturient.montes.nascetur@Donec.edu', '(356) 747-2807', 1, 12),
(41, 'Sara', 'Ceccarelli', 'tempor.augue@dolordapibus.co.uk', '(238) 247-5724', 1, 12),
(42, 'Aurora', 'Farina', 'gravida.nunc@ante.com', '(863) 900-3146', 0, 12),
(43, 'Elisa', 'Monti', 'felis.Nulla.tempor@morbitristique.com', '(991) 862-1199', 0, 12),
(44, 'Samuele', 'Sala', 'parturient.montes@Morbi.co.uk', '(717) 381-6151', 0, 12),
(45, 'Emanuele', 'Fiore', 'nisl.Quisque.fringilla@dolorsit.org', '(272) 128-8329', 0, 12),
(46, 'Giovanni', 'Rinaldi', 'dolor.nonummy.ac@Nullafacilisi.net', '(731) 808-7684', 0, 12),
(48, 'Fabio', 'Mancini', 'purus.sapien@Maurisvelturpis.org', '(295) 764-9970', 0, 12),
(49, 'Alex', 'Gentile', 'dictum.cursus.Nunc@elitpretium.org', '(280) 312-2829', 0, 12),
(50, 'Viola', 'Conte', 'Sed.dictum@miac.org', '(971) 491-2667', 0, 12),
(51, 'Elena', 'Barone', 'bibendum.sed@Integerinmagna.com', '(279) 570-6383', 0, 12),
(59, 'dadds', NULL, 'werwerwe@qqweqwe.com', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregas`
--

CREATE TABLE IF NOT EXISTS `entregas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cep` char(8) COLLATE utf8_swedish_ci NOT NULL,
  `num` varchar(40) COLLATE utf8_swedish_ci NOT NULL,
  `obs` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `ped_id` int(11) NOT NULL,
  `sedex` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_id` int(11) NOT NULL,
  `miureiro_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos`
--

CREATE TABLE IF NOT EXISTS `fotos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `miura_id` int(11) NOT NULL,
  `img` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `tags` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens`
--

CREATE TABLE IF NOT EXISTS `itens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) NOT NULL,
  `ped_id` int(11) NOT NULL,
  `nome` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `preco` float NOT NULL,
  `qtde` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mailing`
--

CREATE TABLE IF NOT EXISTS `mailing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `prod_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `miuras`
--

CREATE TABLE IF NOT EXISTS `miuras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `miureiro_id` int(11) NOT NULL,
  `modelo` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `ano` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `cor` varchar(12) COLLATE utf8_swedish_ci NOT NULL,
  `submodelo` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `historia` text COLLATE utf8_swedish_ci NOT NULL,
  `atributos` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `miureiros1`
--

CREATE TABLE IF NOT EXISTS `miureiros1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `senha` char(40) COLLATE utf8_swedish_ci NOT NULL,
  `uf` char(2) COLLATE utf8_swedish_ci NOT NULL,
  `cidade` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `dt_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(40) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` text COLLATE utf8_swedish_ci NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=26 ;

--
-- Extraindo dados da tabela `notes`
--

INSERT INTO `notes` (`id`, `body`, `contact_id`) VALUES
(2, 'wwwwwwwwwww', 1),
(3, 'aaaaaaaaaa bbbb', 1),
(4, 'uyutryutyutyu6758', 1),
(5, 'pppppppppp', 33),
(6, 'rtyrtyrtyret', 55),
(8, 'ertertreter', 26),
(11, 'etrerterggg', 26),
(12, 'sfdfsdfsd', 26),
(13, 'dfdfgdf', 26),
(14, 'rrfrfrfrfrfrf', 26),
(15, 'eeeeeeeeeee', 26),
(16, 'tryrtyrt', 26),
(17, 'dsfsdfsdfsdqqqqqqqqqq', 26),
(18, 'ttttttttttttt', 26),
(19, 'ferfre', 36),
(20, 'fasdfasdsd', 37),
(21, 'dfgdfgdf', 36),
(22, 'terter', 35),
(23, 'dfsdfsd', 35),
(24, 'gsdfgdsfgdfg', 36),
(25, 'hgfhfghgf', 36);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE utf8_swedish_ci NOT NULL,
  `status` set('NOVO','CANCEL','ANALISE','APROV','DESP') COLLATE utf8_swedish_ci NOT NULL,
  `frete` float NOT NULL,
  `desconto` float NOT NULL,
  `total` float NOT NULL,
  `transacao` varchar(200) COLLATE utf8_swedish_ci NOT NULL,
  `pgto` enum('BOLETO','PAGSEG','BCASH','PAYPAL') COLLATE utf8_swedish_ci NOT NULL,
  `cupom` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `cli_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8_swedish_ci NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `preco` float NOT NULL,
  `prazo` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `estoque` int(11) NOT NULL,
  `relacionados` varchar(300) COLLATE utf8_swedish_ci DEFAULT NULL,
  `thumb` varchar(40) COLLATE utf8_swedish_ci DEFAULT NULL,
  `img` varchar(40) COLLATE utf8_swedish_ci DEFAULT NULL,
  `descricao` text COLLATE utf8_swedish_ci,
  `peso` float NOT NULL DEFAULT '0',
  `categ_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `ativo`, `preco`, `prazo`, `estoque`, `relacionados`, `thumb`, `img`, `descricao`, `peso`, `categ_id`) VALUES
(1, 'Calculadora HP 12C', 1, 233.33, '5', 2, NULL, NULL, NULL, 'Descricao da hp 12c', 0.6, 12),
(2, 'Binoculos 3D', 1, 1233.73, 'E', 0, NULL, NULL, NULL, 'Descricao da binoculos', 1.6, 12),
(3, 'Camera digital Sony Cybershot', 1, 900, 'E', 2, NULL, NULL, NULL, 'Descricao da sonyy', 1, 11),
(4, 'produto de teste bla', 1, 122, '2', 0, NULL, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text COLLATE utf8_swedish_ci NOT NULL,
  `apikey` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `email`, `apikey`) VALUES
(1, 'root', 'root');
--
-- Database: `test`
--
--
-- Database: `vendas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'uuuu'),
(2, 'gggg'),
(3, 'fdgsdfgsgfd'),
(4, 'ppp'),
(5, 'rrrrrrrrr');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Clientes_Usuarios_idx` (`idUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `idUsuario`, `cpf`, `endereco`) VALUES
(1, 3, 'dsfgdfgdsfg', NULL),
(2, 4, '12345', NULL),
(5, 12, 'werwerwere', NULL),
(6, 13, 'werwerwere', NULL),
(7, 14, 'dasdasdsd', NULL),
(8, 15, 'dasdasdsd', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `cnpj` varchar(45) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `cnpj`, `endereco`) VALUES
(1, 'aaa', '123123', NULL),
(2, 'bbb', '5345345', NULL),
(3, 'sdfsdfdfsdfs', 'dfsdfsdfsdfsd', NULL),
(4, 'ttttttttttt', 'ttttt', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itensvenda`
--

CREATE TABLE IF NOT EXISTS `itensvenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVenda` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `precoUnitario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_itensVenda_Vendas1_idx` (`idVenda`),
  KEY `fk_itensVenda_Produtos1_idx` (`idProduto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `itensvenda`
--

INSERT INTO `itensvenda` (`id`, `idVenda`, `idProduto`, `quantidade`, `precoUnitario`) VALUES
(1, 1, 1, 1, '111.11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `idFornecedor` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `quantidadeMinima` int(11) DEFAULT NULL,
  `precoUnitario` decimal(10,2) DEFAULT NULL,
  `descricao` varchar(245) DEFAULT NULL,
  `foto` blob,
  `ativo` tinyint(4) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Produtos_Categorias1_idx` (`idCategoria`),
  KEY `fk_Produtos_Fornecedores1_idx` (`idFornecedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `idCategoria`, `idFornecedor`, `nome`, `quantidade`, `quantidadeMinima`, `precoUnitario`, `descricao`, `foto`, `ativo`, `codigo`) VALUES
(1, 1, 2, 'ggggggggggg', 3, 0, '111.11', 'fsdf', NULL, 1, '333333'),
(4, 5, 4, 'bdbdgdfgdfgdfg', 3, 3, '0.00', '', NULL, 1, '33333');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(45) NOT NULL,
  `senha` varchar(45) NOT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `lastIp` varchar(45) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `login`, `senha`, `lastLogin`, `lastIp`, `tipo`) VALUES
(1, 'a', 'a', 'a', 'a', '2015-01-07 15:59:47', '::1', 'a'),
(2, 'b', 'b', 'b', 'b', '2015-01-05 01:58:33', '::1', 'v'),
(3, 'c', 'c', 'c', 'c', '2015-01-05 01:56:56', '::1', 'c'),
(4, 'clientefff', 'preencher', 'j58mM', 'j58mM', NULL, NULL, 'c'),
(7, 'jjgfjh', '', 'gjhf', 'hgfg', NULL, NULL, 'v'),
(9, 'rrrrrrrrrrrrrrrr', '', 'dfgdfgdfgdf', 'gdfgdf', NULL, NULL, 'v'),
(12, 'werwerwerwerwe', 'preencher', 'N6eRW', 'N6eRW', NULL, NULL, 'c'),
(13, 'werwerwerwerwe', 'preencher', 'HBeoi', 'HBeoi', NULL, NULL, 'c'),
(14, 'asdasdas', 'preencher', 'O3IaX', 'O3IaX', NULL, NULL, 'c'),
(15, 'asdasdas', 'preencher', 'gJYy3', 'gJYy3', NULL, NULL, 'c'),
(16, '77777777777', '', 'oooooooo', 'ooooooooooo', NULL, NULL, 'v'),
(17, 'yukyuyuiyuiyuiy', '', 'yuiyuiytuiyu', 'iyuiyuiyui', NULL, NULL, 'v');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE IF NOT EXISTS `vendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL,
  `idVendedor` int(11) NOT NULL,
  `idTransportadora` int(11) DEFAULT NULL,
  `dataVenda` datetime DEFAULT NULL,
  `dataEnvio` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Vendas_Clientes1_idx` (`idCliente`),
  KEY `fk_Vendas_Vendedores1_idx` (`idVendedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `idCliente`, `idVendedor`, `idTransportadora`, `dataVenda`, `dataEnvio`) VALUES
(1, 2, 1, NULL, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendedores`
--

CREATE TABLE IF NOT EXISTS `vendedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  `dataContratacao` varchar(45) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Clientes_Usuarios_idx` (`idUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `vendedores`
--

INSERT INTO `vendedores` (`id`, `idUsuario`, `cpf`, `matricula`, `dataContratacao`, `ativo`, `endereco`) VALUES
(1, 2, 'werwerwe', 'werwer', '2014-12-02', 1, NULL),
(2, 7, '', '', '2015-01-05', 1, NULL),
(4, 9, '', '', '2015-01-05', 1, NULL),
(7, 16, '', '', '', 1, NULL),
(8, 17, '', '', '2015-01-05', 1, NULL);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_Clientes_Usuarios` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `itensvenda`
--
ALTER TABLE `itensvenda`
  ADD CONSTRAINT `fk_itensVenda_Produtos1` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_itensVenda_Vendas1` FOREIGN KEY (`idVenda`) REFERENCES `vendas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_Produtos_Categorias1` FOREIGN KEY (`idCategoria`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Produtos_Fornecedores1` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `fk_Vendas_Clientes1` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vendas_Vendedores1` FOREIGN KEY (`idVendedor`) REFERENCES `vendedores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `vendedores`
--
ALTER TABLE `vendedores`
  ADD CONSTRAINT `fk_Clientes_Usuarios0` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
