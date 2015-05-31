SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id`       INT(11)               NOT NULL,
  `title`    TEXT
             COLLATE utf8_czech_ci NOT NULL,
  `finished` INT(1)                NOT NULL,
  `order`    INT(11)               NOT NULL,
  `user_id`  INT(11)               NOT NULL
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 99
  DEFAULT CHARSET = utf8
  COLLATE = utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id`       INT(11)               NOT NULL,
  `username` TEXT
             COLLATE utf8_czech_ci NOT NULL,
  `password` TEXT
             COLLATE utf8_czech_ci NOT NULL
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8
  COLLATE = utf8_czech_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 99;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;