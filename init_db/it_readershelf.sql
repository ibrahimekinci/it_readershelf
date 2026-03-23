-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 22, 2026 at 11:51 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_readershelf`
--
CREATE DATABASE IF NOT EXISTS `it_readershelf` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `it_readershelf`;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
CREATE TABLE IF NOT EXISTS `authors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `full_name`, `biography`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Martin Kleppmann', 'Researcher in distributed systems.', '2026-03-23 00:48:10', NULL, 0, NULL),
(2, 'Alex Xu', 'Software engineer and author of System Design Interview.', '2026-03-23 00:48:10', NULL, 0, NULL),
(3, 'Brendan Burns', 'Co-founder of Kubernetes.', '2026-03-23 00:48:10', NULL, 0, NULL),
(4, 'Mark Richards', 'Experienced software architect.', '2026-03-23 00:48:10', NULL, 0, NULL),
(5, 'Neal Ford', 'Director and software architect at ThoughtWorks.', '2026-03-23 00:48:10', NULL, 0, NULL),
(6, 'Gregor Hohpe', 'Enterprise architect and author.', '2026-03-23 00:48:10', NULL, 0, NULL),
(7, 'Robert C. Martin', 'Known as Uncle Bob, author of Clean Code.', '2026-03-23 00:48:10', NULL, 0, NULL),
(8, 'Andrew Hunt', 'Co-author of The Pragmatic Programmer.', '2026-03-23 00:48:10', NULL, 0, NULL),
(9, 'David Thomas', 'Co-author of The Pragmatic Programmer.', '2026-03-23 00:48:10', NULL, 0, NULL),
(10, 'Martin Fowler', 'Author and international speaker on software development.', '2026-03-23 00:48:10', NULL, 0, NULL),
(11, 'Eric Evans', 'Domain-Driven Design pioneer.', '2026-03-23 00:48:10', NULL, 0, NULL),
(12, 'Erich Gamma', 'Co-author of Design Patterns (Gang of Four).', '2026-03-23 00:48:10', NULL, 0, NULL),
(13, 'Richard Helm', 'Co-author of Design Patterns (Gang of Four).', '2026-03-23 00:48:10', NULL, 0, NULL),
(14, 'Ralph Johnson', 'Co-author of Design Patterns (Gang of Four).', '2026-03-23 00:48:10', NULL, 0, NULL),
(15, 'John Vlissides', 'Co-author of Design Patterns (Gang of Four).', '2026-03-23 00:48:10', NULL, 0, NULL),
(16, 'Ian Sommerville', 'Academic and author of Software Engineering.', '2026-03-23 00:48:10', NULL, 0, NULL),
(17, 'Gene Kim', 'Author of The Phoenix Project.', '2026-03-23 00:48:10', NULL, 0, NULL),
(18, 'Kevin Behr', 'Co-author of The Phoenix Project.', '2026-03-23 00:48:10', NULL, 0, NULL),
(19, 'George Spafford', 'Co-author of The Phoenix Project.', '2026-03-23 00:48:10', NULL, 0, NULL),
(20, 'Thomas Cormen', 'Co-author of Introduction to Algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(21, 'Charles Leiserson', 'Co-author of Introduction to Algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(22, 'Ronald Rivest', 'Co-author of Introduction to Algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(23, 'Clifford Stein', 'Co-author of Introduction to Algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(24, 'Jon Duckett', 'Author of popular Web Development books.', '2026-03-23 00:48:10', NULL, 0, NULL),
(25, 'Kyle Simpson', 'Author of You Dont Know JS.', '2026-03-23 00:48:10', NULL, 0, NULL),
(26, 'Bruce Schneier', 'Renowned security technologist.', '2026-03-23 00:48:10', NULL, 0, NULL),
(27, 'Kevin Mitnick', 'Famous hacker and security consultant.', '2026-03-23 00:48:10', NULL, 0, NULL),
(28, 'Stuart Russell', 'AI researcher and author.', '2026-03-23 00:48:10', NULL, 0, NULL),
(29, 'Peter Norvig', 'Director of Research at Google.', '2026-03-23 00:48:10', NULL, 0, NULL),
(30, 'Andrew Ng', 'Pioneer in Machine Learning and online education.', '2026-03-23 00:48:10', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `publication_year` int DEFAULT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `description`, `cover_image_url`, `category_id`, `publication_year`, `isbn`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Designing Data-Intensive Applications', 'The comprehensive guide to distributed systems architecture and big data.', 'images/book_covers/ddia.jpg', 6, 2017, '9781449373320', '2026-03-23 00:48:10', NULL, 0, NULL),
(2, 'System Design Interview', 'An insider guide to passing the system design interview.', 'images/book_covers/sdi.jpg', 6, 2020, '9781736049112', '2026-03-23 00:48:10', NULL, 0, NULL),
(3, 'Designing Distributed Systems', 'Patterns and paradigms for scalable, reliable services.', 'images/book_covers/dds.jpg', 6, 2018, '9781491983645', '2026-03-23 00:48:10', NULL, 0, NULL),
(4, 'Fundamentals of Software Architecture', 'An engineering approach to software architecture.', 'images/book_covers/fsa.jpg', 5, 2020, '9781492043454', '2026-03-23 00:48:10', NULL, 0, NULL),
(5, 'The Software Architect Elevator', 'Redefining the architects role in the digital enterprise.', 'images/book_covers/sae.jpg', 5, 2020, '9781492077541', '2026-03-23 00:48:10', NULL, 0, NULL),
(6, 'Clean Code', 'A Handbook of Agile Software Craftsmanship.', 'images/book_covers/clean_code.jpg', 1, 2008, '9780132350884', '2026-03-23 00:48:10', NULL, 0, NULL),
(7, 'The Pragmatic Programmer', 'From Journeyman to Master, a guide to software development best practices.', 'images/book_covers/pragmatic.jpg', 5, 1999, '9780201616224', '2026-03-23 00:48:10', NULL, 0, NULL),
(8, 'Refactoring', 'Improving the Design of Existing Code.', 'images/book_covers/refactoring.jpg', 5, 2018, '9780134757599', '2026-03-23 00:48:10', NULL, 0, NULL),
(9, 'Domain-Driven Design', 'Tackling Complexity in the Heart of Software.', 'images/book_covers/ddd.jpg', 5, 2003, '9780321125217', '2026-03-23 00:48:10', NULL, 0, NULL),
(10, 'Design Patterns', 'Elements of Reusable Object-Oriented Software.', 'images/book_covers/design_patterns.jpg', 5, 1994, '9780201633610', '2026-03-23 00:48:10', NULL, 0, NULL),
(11, 'Software Engineering', 'Global Edition of the classic academic text on software engineering.', 'images/book_covers/software_eng.jpg', 5, 2015, '9781292096131', '2026-03-23 00:48:10', NULL, 0, NULL),
(12, 'The Phoenix Project', 'A Novel about IT, DevOps, and Helping Your Business Win.', 'images/book_covers/phoenix.jpg', 8, 2013, '9780988262591', '2026-03-23 00:48:10', NULL, 0, NULL),
(13, 'Introduction to Algorithms', 'The standard textbook on algorithms used in universities worldwide.', 'images/book_covers/algorithms.jpg', 1, 2009, '9780262033848', '2026-03-23 00:48:10', NULL, 0, NULL),
(14, 'HTML and CSS', 'Design and Build Websites visually and beautifully.', 'images/book_covers/html_css.jpg', 12, 2011, '9781118008188', '2026-03-23 00:48:10', NULL, 0, NULL),
(15, 'You Dont Know JS', 'A deep dive into the core mechanisms of the JavaScript language.', 'images/book_covers/ydkjs.jpg', 12, 2015, '9781491904244', '2026-03-23 00:48:10', NULL, 0, NULL),
(16, 'Applied Cryptography', 'Protocols, Algorithms, and Source Code in C.', 'images/book_covers/crypto.jpg', 4, 2015, '9781119096726', '2026-03-23 00:48:10', NULL, 0, NULL),
(17, 'The Art of Invisibility', 'The Worlds Most Famous Hacker Teaches You How to Be Safe.', 'images/book_covers/invisibility.jpg', 4, 2017, '9780316380508', '2026-03-23 00:48:10', NULL, 0, NULL),
(18, 'Artificial Intelligence: A Modern Approach', 'The definitive textbook on artificial intelligence.', 'images/book_covers/ai_modern.jpg', 3, 2020, '9780134610993', '2026-03-23 00:48:10', NULL, 0, NULL),
(19, 'Machine Learning Yearning', 'Technical Strategy for AI Engineers.', 'images/book_covers/ml_yearning.jpg', 3, 2018, '9781234567890', '2026-03-23 00:48:10', NULL, 0, NULL),
(20, 'Kubernetes Up & Running', 'Dive into the Future of Infrastructure.', 'images/book_covers/k8s.jpg', 7, 2019, '9781492046530', '2026-03-23 00:48:10', NULL, 0, NULL),
(21, 'Cloud Native Patterns', 'Designing change-tolerant software.', 'images/book_covers/cloud_native.jpg', 7, 2019, '9781617294297', '2026-03-23 00:48:10', NULL, 0, NULL),
(22, 'Database Internals', 'A Deep Dive into How Distributed Data Systems Work.', 'images/book_covers/db_internals.jpg', 9, 2019, '9781492040347', '2026-03-23 00:48:10', NULL, 0, NULL),
(23, 'Computer Networking', 'A Top-Down Approach.', 'images/book_covers/networking.jpg', 10, 2016, '9780133594140', '2026-03-23 00:48:10', NULL, 0, NULL),
(24, 'iOS Programming', 'The Big Nerd Ranch Guide.', 'images/book_covers/ios.jpg', 11, 2020, '9780135265536', '2026-03-23 00:48:10', NULL, 0, NULL),
(25, 'Mastering Bitcoin', 'Programming the Open Blockchain.', 'images/book_covers/bitcoin.jpg', 13, 2017, '9781491954386', '2026-03-23 00:48:10', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE IF NOT EXISTS `book_authors` (
  `book_id` int NOT NULL,
  `author_id` int NOT NULL,
  `display_order` int DEFAULT '1',
  PRIMARY KEY (`book_id`,`author_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_authors`
--

INSERT INTO `book_authors` (`book_id`, `author_id`, `display_order`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(4, 5, 2),
(5, 6, 1),
(6, 7, 1),
(7, 8, 1),
(7, 9, 2),
(8, 10, 1),
(9, 11, 1),
(10, 12, 1),
(10, 13, 2),
(10, 14, 3),
(10, 15, 4),
(11, 16, 1),
(12, 17, 1),
(12, 18, 2),
(12, 19, 3),
(13, 20, 1),
(13, 21, 2),
(13, 22, 3),
(13, 23, 4),
(14, 24, 1),
(15, 25, 1),
(16, 26, 1),
(17, 27, 1),
(18, 28, 1),
(18, 29, 2),
(19, 30, 1),
(20, 3, 1),
(21, 4, 1),
(22, 2, 1),
(23, 16, 1),
(24, 8, 1),
(25, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NAME` (`NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `NAME`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Programming', '2026-03-23 00:48:10', NULL, 0, NULL),
(2, 'Data Science', '2026-03-23 00:48:10', NULL, 0, NULL),
(3, 'Artificial Intelligence', '2026-03-23 00:48:10', NULL, 0, NULL),
(4, 'Cybersecurity', '2026-03-23 00:48:10', NULL, 0, NULL),
(5, 'Software Engineering', '2026-03-23 00:48:10', NULL, 0, NULL),
(6, 'System Design', '2026-03-23 00:48:10', NULL, 0, NULL),
(7, 'Cloud Computing', '2026-03-23 00:48:10', NULL, 0, NULL),
(8, 'DevOps', '2026-03-23 00:48:10', NULL, 0, NULL),
(9, 'Database Administration', '2026-03-23 00:48:10', NULL, 0, NULL),
(10, 'Networking', '2026-03-23 00:48:10', NULL, 0, NULL),
(11, 'Mobile Development', '2026-03-23 00:48:10', NULL, 0, NULL),
(12, 'Web Development', '2026-03-23 00:48:10', NULL, 0, NULL),
(13, 'Blockchain', '2026-03-23 00:48:10', NULL, 0, NULL),
(14, 'Operating Systems', '2026-03-23 00:48:10', NULL, 0, NULL),
(15, 'IT Management', '2026-03-23 00:48:10', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `book_id`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 6, 1, '2026-03-23 01:12:07', 0, NULL),
(2, 6, 2, '2026-03-23 01:51:49', 0, NULL),
(3, 6, 5, '2026-03-23 01:55:37', 1, '2026-03-23 01:56:10'),
(4, 6, 3, '2026-03-23 01:55:46', 1, '2026-03-23 01:59:52'),
(5, 6, 7, '2026-03-23 01:55:51', 0, NULL),
(6, 6, 8, '2026-03-23 01:55:59', 1, '2026-03-23 01:59:54'),
(7, 6, 6, '2026-03-23 01:56:03', 1, '2026-03-23 01:57:11'),
(8, 6, 18, '2026-03-23 01:56:33', 1, '2026-03-23 01:57:07'),
(9, 5, 2, '2026-03-23 10:47:21', 0, NULL),
(10, 5, 1, '2026-03-23 10:47:22', 0, NULL),
(11, 5, 13, '2026-03-23 10:47:23', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promoted_books`
--

DROP TABLE IF EXISTS `promoted_books`;
CREATE TABLE IF NOT EXISTS `promoted_books` (
  `book_id` int NOT NULL,
  `display_order` int DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promoted_books`
--

INSERT INTO `promoted_books` (`book_id`, `display_order`, `created_at`) VALUES
(1, 1, '2026-03-23 00:48:10'),
(2, 2, '2026-03-23 00:48:10'),
(6, 3, '2026-03-23 00:48:10'),
(7, 4, '2026-03-23 00:48:10'),
(10, 5, '2026-03-23 00:48:10'),
(12, 6, '2026-03-23 00:48:10'),
(13, 7, '2026-03-23 00:48:10'),
(18, 8, '2026-03-23 00:48:10'),
(20, 9, '2026-03-23 00:48:10'),
(25, 10, '2026-03-23 00:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `book_id`, `rating`, `review_text`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 1, 5, 'An absolute masterpiece. This book bridges the gap between theory and practical distributed systems.', '2026-03-23 00:48:10', NULL, 0, NULL),
(2, 2, 1, 4, 'Very dense but essential reading for any senior backend engineer.', '2026-03-23 00:48:10', NULL, 0, NULL),
(3, 3, 1, 5, 'If you want to understand how databases actually work under the hood, read this.', '2026-03-23 00:48:10', NULL, 0, NULL),
(4, 4, 2, 5, 'Helped me pass my FAANG interview. The diagrams are incredibly clear.', '2026-03-23 00:48:10', NULL, 0, NULL),
(5, 5, 2, 4, 'Great resource, though I wish it went a bit deeper into microservices.', '2026-03-23 00:48:10', '2026-03-23 10:47:44', 1, '2026-03-23 10:47:44'),
(6, 1, 2, 5, 'The best book on system design available right now.', '2026-03-23 00:48:10', NULL, 0, NULL),
(7, 2, 3, 4, 'Good overview of Kubernetes patterns. Brendan Burns knows his stuff.', '2026-03-23 00:48:10', NULL, 0, NULL),
(8, 3, 3, 3, 'A bit theoretical. I prefer more hands-on coding examples.', '2026-03-23 00:48:10', NULL, 0, NULL),
(9, 4, 3, 4, 'Solid introduction to distributed paradigms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(10, 5, 4, 5, 'Finally, a book that explains software architecture without the fluff.', '2026-03-23 00:48:10', NULL, 0, NULL),
(11, 1, 4, 4, 'Very structured. The trade-off analysis chapters are gold.', '2026-03-23 00:48:10', NULL, 0, NULL),
(12, 2, 4, 5, 'A must-read for anyone transitioning from developer to architect.', '2026-03-23 00:48:10', NULL, 0, NULL),
(13, 3, 5, 4, 'Great insights on how to communicate technical concepts to business leaders.', '2026-03-23 00:48:10', NULL, 0, NULL),
(14, 4, 5, 5, 'This book changed my perspective on my role in the company.', '2026-03-23 00:48:10', NULL, 0, NULL),
(15, 5, 5, 4, 'Very good, but relies heavily on enterprise-scale examples.', '2026-03-23 00:48:10', NULL, 0, NULL),
(16, 1, 6, 5, 'Uncle Bob delivers. This book completely changed how I write code.', '2026-03-23 00:48:10', NULL, 0, NULL),
(17, 2, 6, 3, 'Some of the Java examples are a bit outdated, but the core principles stand.', '2026-03-23 00:48:10', NULL, 0, NULL),
(18, 3, 6, 5, 'Mandatory reading for every junior developer on my team.', '2026-03-23 00:48:10', NULL, 0, NULL),
(19, 4, 7, 5, 'A timeless classic. The advice transcends programming languages.', '2026-03-23 00:48:10', NULL, 0, NULL),
(20, 5, 7, 5, 'I reread this every few years. Always learn something new.', '2026-03-23 00:48:10', NULL, 0, NULL),
(21, 1, 7, 4, 'Excellent pragmatic tips, though some tooling mentioned is obsolete.', '2026-03-23 00:48:10', NULL, 0, NULL),
(22, 2, 8, 4, 'A heavy read, but the catalog of refactorings is invaluable.', '2026-03-23 00:48:10', NULL, 0, NULL),
(23, 3, 8, 5, 'Martin Fowler explains code smells perfectly.', '2026-03-23 00:48:10', NULL, 0, NULL),
(24, 4, 8, 4, 'Great book, but the JavaScript examples in the 2nd edition threw me off initially.', '2026-03-23 00:48:10', NULL, 0, NULL),
(25, 5, 9, 5, 'The bible of DDD. Eric Evans coined the terms we use every day.', '2026-03-23 00:48:10', NULL, 0, NULL),
(26, 1, 9, 3, 'Extremely dry and hard to read, but the concepts are critical.', '2026-03-23 00:48:10', NULL, 0, NULL),
(27, 2, 9, 4, 'Took me two tries to finish it, but my system modeling is much better now.', '2026-03-23 00:48:10', NULL, 0, NULL),
(28, 3, 10, 5, 'The GoF book is foundational. Every dev needs this on their shelf.', '2026-03-23 00:48:10', NULL, 0, NULL),
(29, 4, 10, 4, 'A bit academic, but the patterns are standard vocabulary now.', '2026-03-23 00:48:10', NULL, 0, NULL),
(30, 5, 10, 5, 'A true classic that still applies to modern OOP.', '2026-03-23 00:48:10', NULL, 0, NULL),
(31, 1, 11, 4, 'Good academic overview of the software lifecycle.', '2026-03-23 00:48:10', NULL, 0, NULL),
(32, 2, 11, 3, 'Felt a bit like a university lecture, but solid information.', '2026-03-23 00:48:10', NULL, 0, NULL),
(33, 3, 11, 4, 'Comprehensive, covers everything from requirements to testing.', '2026-03-23 00:48:10', NULL, 0, NULL),
(34, 4, 12, 5, 'A novel about IT? I was skeptical, but I could not put it down.', '2026-03-23 00:48:10', NULL, 0, NULL),
(35, 5, 12, 5, 'If you work in DevOps, you will relate to Bill on a spiritual level.', '2026-03-23 00:48:10', NULL, 0, NULL),
(36, 1, 12, 4, 'Great introduction to lean principles in IT operations.', '2026-03-23 00:48:10', NULL, 0, NULL),
(37, 2, 13, 5, 'The definitive algorithms textbook. Very math-heavy.', '2026-03-23 00:48:10', NULL, 0, NULL),
(38, 3, 13, 4, 'Excellent reference book, though not meant for casual reading.', '2026-03-23 00:48:10', NULL, 0, NULL),
(39, 4, 13, 5, 'Got me through my CS degree. The pseudocode is easy to translate.', '2026-03-23 00:48:10', NULL, 0, NULL),
(40, 5, 14, 5, 'The most beautiful programming book I have ever owned.', '2026-03-23 00:48:10', NULL, 0, NULL),
(41, 1, 14, 4, 'Great for absolute beginners to frontend web dev.', '2026-03-23 00:48:10', NULL, 0, NULL),
(42, 2, 14, 5, 'Visual layout makes learning CSS properties a breeze.', '2026-03-23 00:48:10', NULL, 0, NULL),
(43, 3, 15, 5, 'Kyle Simpson explains JS closures and prototypes better than anyone.', '2026-03-23 00:48:10', NULL, 0, NULL),
(44, 4, 15, 4, 'Deeply technical. Not for beginners, but excellent for mid-level devs.', '2026-03-23 00:48:10', NULL, 0, NULL),
(45, 5, 15, 5, 'This series completely demystified JavaScript for me.', '2026-03-23 00:48:10', NULL, 0, NULL),
(46, 1, 16, 4, 'The standard text for cryptography. Very comprehensive.', '2026-03-23 00:48:10', NULL, 0, NULL),
(47, 2, 16, 5, 'Bruce Schneier is a legend. Great detailed protocols.', '2026-03-23 00:48:10', NULL, 0, NULL),
(48, 3, 16, 3, 'A bit outdated regarding modern elliptic curve algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(49, 4, 17, 4, 'Fascinating read on privacy in the digital age.', '2026-03-23 00:48:10', NULL, 0, NULL),
(50, 5, 17, 5, 'Eye-opening. Made me change all my passwords and use a VPN.', '2026-03-23 00:48:10', NULL, 0, NULL),
(51, 1, 17, 4, 'Good practical tips for maintaining digital anonymity.', '2026-03-23 00:48:10', NULL, 0, NULL),
(52, 2, 18, 5, 'The AI bible. Covers everything from search to neural networks.', '2026-03-23 00:48:10', NULL, 0, NULL),
(53, 3, 18, 4, 'Very dense and mathematical, but incredibly thorough.', '2026-03-23 00:48:10', NULL, 0, NULL),
(54, 4, 18, 5, 'Essential reading if you are serious about artificial intelligence.', '2026-03-23 00:48:10', NULL, 0, NULL),
(55, 5, 19, 5, 'Andrew Ng provides fantastic strategic advice for ML projects.', '2026-03-23 00:48:10', NULL, 0, NULL),
(56, 1, 19, 4, 'Short and practical. Good advice on train/dev/test splits.', '2026-03-23 00:48:10', NULL, 0, NULL),
(57, 2, 19, 5, 'A must-read for any data science project manager.', '2026-03-23 00:48:10', NULL, 0, NULL),
(58, 3, 20, 4, 'Great intro to Kubernetes core concepts.', '2026-03-23 00:48:10', NULL, 0, NULL),
(59, 4, 20, 5, 'Got my first cluster running in hours thanks to this book.', '2026-03-23 00:48:10', NULL, 0, NULL),
(60, 5, 20, 4, 'Solid guide, though K8s moves so fast some commands change.', '2026-03-23 00:48:10', NULL, 0, NULL),
(61, 1, 21, 5, 'Excellent patterns for building cloud-ready applications.', '2026-03-23 00:48:10', NULL, 0, NULL),
(62, 2, 21, 4, 'Highly recommended for backend devs moving to AWS/GCP.', '2026-03-23 00:48:10', NULL, 0, NULL),
(63, 3, 21, 4, 'Clear examples and good architectural theory.', '2026-03-23 00:48:10', NULL, 0, NULL),
(64, 4, 22, 5, 'Finally, a book that explains B-Trees and LSM-Trees clearly.', '2026-03-23 00:48:10', NULL, 0, NULL),
(65, 5, 22, 5, 'Amazing deep dive into distributed consensus algorithms.', '2026-03-23 00:48:10', NULL, 0, NULL),
(66, 1, 22, 4, 'Very technical but rewarding. Excellent diagrams.', '2026-03-23 00:48:10', NULL, 0, NULL),
(67, 2, 23, 5, 'The top-down approach makes networking so much easier to learn.', '2026-03-23 00:48:10', NULL, 0, NULL),
(68, 3, 23, 4, 'Standard networking textbook. Covers the TCP/IP stack perfectly.', '2026-03-23 00:48:10', NULL, 0, NULL),
(69, 4, 23, 5, 'Great explanations of transport layer protocols.', '2026-03-23 00:48:10', NULL, 0, NULL),
(70, 5, 24, 4, 'Big Nerd Ranch always produces quality iOS guides.', '2026-03-23 00:48:10', NULL, 0, NULL),
(71, 1, 24, 5, 'The best way to learn Swift and iOS app development.', '2026-03-23 00:48:10', NULL, 0, NULL),
(72, 2, 24, 4, 'Very practical, project-based learning.', '2026-03-23 00:48:10', NULL, 0, NULL),
(73, 3, 25, 5, 'Andreas Antonopoulos explains Bitcoin better than anyone else.', '2026-03-23 00:48:10', NULL, 0, NULL),
(74, 4, 25, 4, 'Great technical breakdown of the blockchain and mining.', '2026-03-23 00:48:10', NULL, 0, NULL),
(75, 5, 25, 5, 'Essential reading for understanding cryptocurrency fundamentals.', '2026-03-23 00:48:10', NULL, 0, NULL),
(76, 6, 2, 5, 'test', '2026-03-23 01:12:37', '2026-03-23 01:12:37', 0, NULL),
(77, 6, 7, 3, 'mm.,mm,', '2026-03-23 02:01:09', '2026-03-23 02:06:20', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Mark Nolan', 'mark@example.com', '2026-03-23 00:48:10', NULL, 0, NULL),
(2, 'Sarah Mitchell', 'sarah@example.com', '2026-03-23 00:48:10', NULL, 0, NULL),
(3, 'John Doe', 'john@example.com', '2026-03-23 00:48:10', NULL, 0, NULL),
(4, 'Jane Smith', 'jane@example.com', '2026-03-23 00:48:10', NULL, 0, NULL),
(5, 'ibrahim Ekinci', 'ibo@ibo.com', '2026-03-23 00:48:10', NULL, 0, NULL),
(6, 'test 1', 'test@test.com', '2026-03-23 01:08:38', '2026-03-23 01:11:58', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_credentials`
--

DROP TABLE IF EXISTS `user_credentials`;
CREATE TABLE IF NOT EXISTS `user_credentials` (
  `user_id` int NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_credentials`
--

INSERT INTO `user_credentials` (`user_id`, `password_hash`, `updated_at`) VALUES
(1, '$2y$10$NLxZTjFqSWlVpnMuWJVVgu/czB9/BKC0uaaA4S6EtU9WDhtJmm6fK', '2026-03-23 01:11:29'),
(2, '$2y$10$NLxZTjFqSWlVpnMuWJVVgu/czB9/BKC0uaaA4S6EtU9WDhtJmm6fK', '2026-03-23 01:11:27'),
(3, '$2y$10$NLxZTjFqSWlVpnMuWJVVgu/czB9/BKC0uaaA4S6EtU9WDhtJmm6fK', '2026-03-23 01:11:25'),
(4, '$2y$10$NLxZTjFqSWlVpnMuWJVVgu/czB9/BKC0uaaA4S6EtU9WDhtJmm6fK', '2026-03-23 01:11:23'),
(5, '$2y$10$giGY8tTJTvVINtR4.ROj9.7q8DAVJ.ZUs2OWWXhEhiT.aukgjeRNy', '2026-03-23 10:47:14'),
(6, '$2y$10$NLxZTjFqSWlVpnMuWJVVgu/czB9/BKC0uaaA4S6EtU9WDhtJmm6fK', '2026-03-23 01:08:38');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD CONSTRAINT `book_authors_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promoted_books`
--
ALTER TABLE `promoted_books`
  ADD CONSTRAINT `promoted_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD CONSTRAINT `user_credentials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
