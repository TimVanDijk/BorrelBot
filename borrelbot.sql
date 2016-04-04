-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2016 at 08:14 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `borrelbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `available`
--

CREATE TABLE IF NOT EXISTS `available` (
  `slot_1` varchar(50) DEFAULT NULL,
  `slot_2` varchar(50) DEFAULT NULL,
  `slot_3` varchar(50) DEFAULT NULL,
  `slot_4` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `available`
--

INSERT INTO `available` (`slot_1`, `slot_2`, `slot_3`, `slot_4`) VALUES
('prosecco', 'orange juice', 'vodka', 'fresh peach purée');

-- --------------------------------------------------------

--
-- Table structure for table `cocktails`
--

CREATE TABLE IF NOT EXISTS `cocktails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `ingr_1` varchar(30) NOT NULL,
  `amnt_1` int(10) unsigned NOT NULL,
  `ingr_2` varchar(30) DEFAULT NULL,
  `amnt_2` int(10) unsigned DEFAULT NULL,
  `ingr_3` varchar(30) DEFAULT NULL,
  `amnt_3` int(10) unsigned DEFAULT NULL,
  `ingr_4` varchar(30) DEFAULT NULL,
  `amnt_4` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=463 ;

--
-- Dumping data for table `cocktails`
--

INSERT INTO `cocktails` (`id`, `name`, `picture`, `ingr_1`, `amnt_1`, `ingr_2`, `amnt_2`, `ingr_3`, `amnt_3`, `ingr_4`, `amnt_4`) VALUES
(408, 'Rose', '/images/Rose.jpg', 'dry vermouth', 30, 'kirsch', 30, 'strawberry syrup', 30, NULL, NULL),
(409, 'Cuba Libre', '/images/Cuba Libre.jpg', 'cola light', 30, 'rum', 30, 'fresh lime juice', 30, NULL, NULL),
(410, 'Moscow Mule', '/images/Moscow Mule.jpg', 'vodka', 30, 'lime juice', 30, 'ginger beer', 30, NULL, NULL),
(411, 'Harvey Wallbanger', '/images/Harvey Wallbanger.jpg', 'vodka', 30, 'galliano', 30, 'fresh orange juice', 30, NULL, NULL),
(412, 'Martini', '/images/Martini.jpg', 'gin', 30, 'dry vermouth', 30, NULL, NULL, NULL, NULL),
(413, 'Bacardi cocktail', '/images/Bacardi cocktail.jpg', 'white rum', 30, 'lime juice', 30, 'grenadine', 30, 'syrup', 30),
(414, 'Sour White Lady', '/images/Sour White Lady.jpg', 'gin', 30, 'triple sec', 30, 'lemon juice', 30, NULL, NULL),
(415, 'Margarita', '/images/Margarita.jpg', 'tequila', 30, 'cointreau', 30, 'lime juice', 30, NULL, NULL),
(416, 'Aviation', '/images/Aviation.jpg', 'gin', 30, 'lemon juice', 30, 'maraschino liqueur', 30, NULL, NULL),
(417, 'Casino', '/images/Casino.jpg', 'gin', 30, 'maraschino', 30, 'orange bitters', 30, 'fresh lemon juice', 30),
(418, 'Sex on the Beach', '/images/Sex on the Beach.jpg', 'vodka', 30, 'peach schnapps', 30, 'orange juice', 30, 'cranberry juice', 30),
(419, 'Mint Julep', '/images/Mint Julep.jpg', 'bourbon', 30, 'whiskey', 30, 'mint', 30, 'powdered water', 30),
(420, 'Tequila Sunrise', '/images/Tequila Sunrise.jpg', 'tequila', 30, 'orange juice', 30, 'grenadine', 30, 'syrup', 30),
(421, 'Sea Breeze', '/images/Sea Breeze.jpg', 'vodka', 30, 'cranberry juice', 30, 'grapefruit juice', 30, NULL, NULL),
(422, 'John Collins', '/images/John Collins.jpg', 'gin', 30, 'lemon juice', 30, 'syrup', 30, 'carbonated water', 30),
(423, 'Sidecar', '/images/Sidecar.jpg', 'cognac', 30, 'triple sec', 30, 'lemon juice', 30, NULL, NULL),
(424, 'Vesper', '/images/Vesper.jpg', 'gin', 30, 'vodka', 30, 'lillet blonde', 30, NULL, NULL),
(425, 'Cosmopolitan', '/images/Cosmopolitan.jpg', 'vodka citron', 30, 'cointreau', 30, 'fresh lime juice', 30, 'cranberry juice', 30),
(426, 'Negroni', '/images/Negroni.jpg', 'gin', 30, 'sweet red vermouth', 30, 'campari', 30, NULL, NULL),
(427, 'Grasshopper', '/images/Grasshopper.jpg', 'crème de menthe', 30, 'crème de cacao', 30, 'white fresh cream', 30, NULL, NULL),
(428, 'Monkey Gland', '/images/Monkey Gland.jpg', 'gin', 30, 'orange juice', 30, 'absinthe', 30, 'grenadine', 30),
(429, 'Caipirinha', '/images/Caipirinha.jpg', 'cachaça', 30, 'lime', 30, NULL, NULL, NULL, NULL),
(430, 'Alexander', '/images/Alexander.jpg', 'cognac', 30, 'white crème de cacao light', 30, 'cream', 30, NULL, NULL),
(431, 'Spritz Veneziano', '/images/Spritz Veneziano.jpg', 'prosecco', 30, 'aperol', 30, 'soda water', 30, NULL, NULL),
(432, 'Americano', '/images/Americano.jpg', 'campari', 30, 'red vermouth', 30, 'soda water', 30, NULL, NULL),
(433, 'Godfather', '/images/Godfather.jpg', 'scotch', 30, 'whisky', 30, 'disaronno', 30, NULL, NULL),
(434, 'Golden dream', '/images/Golden dream.jpg', 'galliano', 30, 'triple sec', 30, 'fresh orange juice', 30, 'fresh cream', 30),
(435, 'Espresso Martini', '/images/Espresso Martini.jpg', 'vodka', 30, 'kahlua', 30, 'syrup', 30, 'strong espresso', 30),
(436, 'Stinger', '/images/Stinger.jpg', 'cognac', 30, 'white creme de menthe', 30, NULL, NULL, NULL, NULL),
(437, 'Rusty Nail', '/images/Rusty Nail.jpg', 'scotch', 30, 'whisky', 30, 'drambuie', 30, NULL, NULL),
(438, 'Mimosa', '/images/Mimosa.jpg', 'champagne', 30, 'orange juice', 30, NULL, NULL, NULL, NULL),
(439, 'Porto flip', '/images/Porto flip.jpg', 'brandy', 30, 'port', 30, 'egg yolk', 30, NULL, NULL),
(440, 'Angel Face', '/images/Angel Face.jpg', 'gin', 30, 'apricot brandy', 30, 'calvados', 30, NULL, NULL),
(441, 'Pina Colada', '/images/Pi C3 B1a Colada.jpg', 'white rum', 30, 'coconut milk', 30, 'pineapple juice', 30, NULL, NULL),
(442, 'Irish Coffee', '/images/Irish Coffee.jpg', 'irish whiskey', 30, 'hot coffee', 30, 'fresh cream', 30, NULL, NULL),
(443, 'Manhattan', '/images/Manhattan.jpg', 'rye', 30, 'sweet red vermouth', 30, 'angostura bitters', 30, NULL, NULL),
(444, 'Pisco sour', '/images/Pisco sour.jpg', 'pisco', 30, 'lemon juice', 30, 'syrup', 30, 'egg', 30),
(445, 'Horse 27s Neck', '/images/Horse 27s Neck.jpg', 'brandy', 30, 'ginger ale', 30, 'angostura bitter', 30, NULL, NULL),
(446, 'Between the Sheets', '/images/Between the Sheets.jpg', 'white rum', 30, 'cognac', 30, 'triple sec', 30, 'fresh lemon juice', 30),
(447, 'French 75', '/images/French 75.jpg', 'gin', 30, 'syrup', 30, 'lemon juice', 30, 'champagne', 30),
(448, 'Mary Pickford', '/images/Mary Pickford.jpg', 'white rum', 30, 'fresh pineapple juice', 30, 'grenadine', 30, 'maraschino', 30),
(449, 'French Connection', '/images/French Connection.jpg', 'cognac', 30, 'disaronno liqueur', 30, NULL, NULL, NULL, NULL),
(450, 'Champagne Cocktail', '/images/Champagne Cocktail.jpg', 'champagne', 30, 'cognac', 30, 'angostura bitters', 30, NULL, NULL),
(451, 'Screwdriver', '/images/Screwdriver.jpg', 'vodka', 30, 'orange juice', 30, NULL, NULL, NULL, NULL),
(452, 'Paradise', '/images/Paradise.jpg', 'gin', 30, 'apricot brandy', 30, 'orange juice', 30, NULL, NULL),
(453, 'Kamikaze', '/images/Kamikaze.jpg', 'vodka', 30, 'triple sec', 30, 'lime juice', 30, NULL, NULL),
(454, 'Mojito', '/images/Mojito.jpg', 'white rum', 30, 'fresh lime juice', 30, 'mint', 30, 'soda water', 30),
(455, 'Bellini', '/images/Bellini.jpg', 'prosecco', 30, 'fresh peach purée', 30, NULL, NULL, NULL, NULL),
(456, 'Sazerac', '/images/Sazerac.jpg', 'cognac', 30, 'absinthe', 30, 'peychauds bitters', 30, NULL, NULL),
(457, 'Dark 27N 27 Stormy', '/images/Dark 27N 27 Stormy.jpg', 'dark rum', 30, 'ginger beer', 30, NULL, NULL, NULL, NULL),
(458, 'Yellow Bird', '/images/Yellow Bird.jpg', 'white rum', 30, 'galliano', 30, 'triple sec', 30, 'lime juice', 30),
(459, 'French Martini', '/images/French Martini.jpg', 'vodka', 30, 'raspberry liqueur', 30, 'fresh pineapple juice', 30, NULL, NULL),
(460, 'Black Russian', '/images/Black Russian.jpg', 'vodka', 30, 'coffee liqueur', 30, NULL, NULL, NULL, NULL),
(461, 'Kir', '/images/Kir.jpg', 'white wine', 30, 'crème de cassis', 30, NULL, NULL, NULL, NULL),
(462, 'Derby', '/images/Derby.jpg', 'gin', 30, 'peach bitters', 30, 'fresh mint', 30, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
