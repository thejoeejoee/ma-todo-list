SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ma-todo-list`
--

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `title`, `finished`, `order`, `user_id`) VALUES
  (97, 'Otestovat TODO list', 0, 0, 7),
  (98, 'Udělat release', 0, 1, 7);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
  (7, 'guest', '$2y$10$.CYAn9jY30nuNijMNeozs.sU3bHkiuNf5Qm5YYcPruQfIMd9Dy3wm');

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;