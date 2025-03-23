-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 08:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agribusiness_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_table`
--

CREATE TABLE `course_table` (
  `course_id` int(11) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `course_description` text NOT NULL,
  `course_photo` varchar(255) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_category` varchar(100) NOT NULL,
  `course_price` decimal(10,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_table`
--

INSERT INTO `course_table` (`course_id`, `course_title`, `course_description`, `course_photo`, `instructor_id`, `course_category`, `course_price`, `last_updated`) VALUES
(1, 'Modern Techniques in Organic farming', 'Learn sustainable and eco friendly farming methods', 'uploadedfiles/modern.jpg', 2, 'Crop Farming', 150000.00, '2025-03-18 07:49:26'),
(2, 'Precision: Using Technology in Crop farming', 'Explore how drone, sensors, and AI improve farming efficiency', 'uploadedfiles/1742270633_beach1[1].jpg', 2, 'Agritech', 150000.00, '2025-03-18 07:49:36'),
(3, 'Soil Health & Fertility Management', 'Understand how enhance soil quality for better crop yields', 'uploadedfiles/1742271410_1742271116628_1[1].jpg', 2, 'Crop Farming', 150000.00, '2025-03-18 07:49:45'),
(4, 'Pest & Disease Management in Crops', 'Learn how to identify and prevent pests and diseases in crops', 'uploadedfiles/1742283115_pests-pesticide[1].jpg', 2, 'Crop Farming', 150000.00, '2025-03-18 07:49:52');

-- --------------------------------------------------------

--
-- Table structure for table `learning_table`
--

CREATE TABLE `learning_table` (
  `learning_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_id` int(50) DEFAULT NULL,
  `progress` decimal(5,2) DEFAULT 0.00,
  `status` enum('In Progress','Completed') DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_table`
--

INSERT INTO `learning_table` (`learning_id`, `user_id`, `course_id`, `lesson_id`, `progress`, `status`) VALUES
(1, 3, 1, NULL, 50.00, 'Completed'),
(2, 3, 4, NULL, 0.00, 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_table`
--

CREATE TABLE `lesson_table` (
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_description` text DEFAULT NULL,
  `lesson_file` varchar(255) DEFAULT NULL,
  `lesson_duration` time DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_table`
--

INSERT INTO `lesson_table` (`lesson_id`, `course_id`, `lesson_title`, `lesson_description`, `lesson_file`, `lesson_duration`, `last_updated`) VALUES
(1, 1, 'Lesson 1:Introduction to Organic farming & Sustainability', 'Organic farming is a sustainable agricultural approach that relies on natural inputs such as compost, manure, and biological pest control instead of synthetic chemicals. It focuses on maintaining soil health, biodiversity, and ecological balance while avoiding genetically modified organisms (GMOs). The core principles of organic farming include health, which ensures soil and human well-being, ecology, which promotes harmony with nature, fairness, which supports ethical farming practices, and care, which ensures responsible resource use for future generations.\r\n\r\nUnlike conventional farming, which depends on synthetic fertilizers and pesticides, organic farming nurtures the soil through crop rotation, green manure, and composting. Natural pest control methods, such as companion planting and beneficial insects, help protect crops without harming the environment. These practices reduce pollution, enhance soil fertility, and contribute to healthier food production. Farmers adopting organic methods often experience improved soil structure and reduced dependency on external chemical inputs, making their farms more resilient in the long run.\r\n\r\nSustainability in agriculture is crucial for ensuring food security and environmental preservation. Organic farming reduces water contamination, conserves biodiversity, and supports climate change mitigation by improving carbon sequestration in soil. Additionally, it provides consumers with healthier food options, free from harmful chemical residues. By adopting organic techniques, farmers contribute to a cleaner environment and a more sustainable future for agriculture.\r\n', 'uploadedfiles/1742280916_1742271116628_1[1].jpg', '04:00:00', '2025-03-18 07:50:25'),
(2, 1, 'Lesson2: Soil Health Management & Natural Fertilization', 'nutrients that plants can absorb. This natural nutrient cycle reduces the need for external chemical inputs and promotes long-term soil productivity.\r\n\r\nOne of the most effective ways to improve soil health is through composting. Compost is made from decomposed organic materials such as kitchen scraps, leaves, and animal manure. When added to the soil, compost increases organic matter content, enhances moisture retention, and provides essential nutrients. Additionally, it encourages microbial activity, which helps break down nutrients into forms that plants can easily absorb.\r\n\r\nAnother important soil enrichment technique is green manure, which involves growing specific plants (such as legumes) and then plowing them into the soil. These plants fix nitrogen from the atmosphere into the soil, reducing the need for artificial fertilizers. Green manure also improves soil texture, prevents erosion, and adds organic matter, which supports soil life.\r\n\r\nCrop rotation is another essential practice in organic farming. Instead of growing the same crop repeatedly on the same land, farmers rotate different crops to maintain soil fertility. Certain crops, such as legumes, naturally replenish nitrogen, while others prevent soil depletion. Crop rotation also helps reduce pest and disease buildup, as different plants attract different types of insects and pathogens.\r\n\r\nMulching is a technique that involves covering the soil with organic materials such as straw, leaves, or wood chips. This helps retain moisture, suppress weeds, and regulate soil temperature. As the mulch decomposes, it enriches the soil with organic matter and nutrients. Mulching is especially useful in dry regions where water conservation is critical.\r\n\r\nVermiculture, or the use of earthworms to break down organic waste, is another effective soil management method. Earthworms consume organic matter and excrete nutrient-rich castings that improve soil structure and fertility. Vermicompost is a highly valuable natural fertilizer that enhances soil aeration, water retention, and microbial activity.\r\n\r\nOrganic farmers also use biofertilizers, which contain beneficial microorganisms that promote plant growth. These include nitrogen-fixing bacteria, phosphate-solubilizing fungi, and mycorrhizal fungi that enhance nutrient absorption. Unlike chemical fertilizers, biofertilizers improve soil fertility without causing environmental harm.\r\n\r\nMaintaining proper soil pH is essential for healthy crop growth. Different crops require different pH levels, and organic farmers adjust soil pH using natural amendments such as lime (to raise pH) or sulfur (to lower pH). Regular soil testing helps farmers monitor nutrient levels and make necessary adjustments to ensure optimal growing conditions.\r\n\r\nIn conclusion, soil health management is a fundamental aspect of organic farming. Through composting, green manure, crop rotation, mulching, vermiculture, and biofertilizers, organic farmers can maintain fertile, nutrient-rich soil without relying on synthetic chemicals. These sustainable practices not only improve crop yields but also protect the environment and ensure long-term agricultural productivity.\r\n', 'uploadedfiles/1742281340_1742271116628_1[1].jpg', '04:00:00', '2025-03-18 07:50:45'),
(3, 1, 'Lesson 3: Integrated Pest & Disease Management (IPM)', 'Pests and diseases pose a major challenge to agricultural productivity, affecting crop yields and farm sustainability. In organic farming, managing pests and diseases without synthetic chemicals is crucial for maintaining soil health, biodiversity, and food safety. Integrated Pest Management (IPM) is a sustainable approach that combines various natural strategies to control pests while minimizing environmental impact.\r\n\r\nIPM relies on preventive measures to reduce the risk of pest infestations. One key practice is crop rotation, where different crops are grown in sequence to disrupt pest life cycles. For example, planting legumes after cereal crops can break the cycle of soil-borne pests and diseases. Another preventive technique is companion planting, where certain plants are grown together to repel pests. For instance, marigolds release chemicals that deter nematodes and aphids.\r\n\r\nEncouraging natural predators is another effective method in organic pest control. Beneficial insects such as ladybugs, lacewings, and praying mantises feed on harmful pests like aphids, caterpillars, and whiteflies. Farmers can also introduce biological control agents such as parasitic wasps, which target specific pest species without harming beneficial insects. This method helps maintain a balanced ecosystem within the farm.\r\n\r\nUsing botanical pesticides derived from plants is a safer alternative to synthetic chemicals. Neem oil, for example, acts as an insect repellent and disrupts the growth of pests. Garlic and chili extracts also have natural pesticidal properties that deter insects. These organic pesticides break down quickly in the environment, reducing the risk of harmful residues on crops and soil.\r\n\r\nAnother IPM strategy is mechanical and physical control, which involves using traps, barriers, and manual pest removal. Sticky traps attract and capture flying pests, while row covers and netting protect crops from insect infestations. Handpicking larger pests like caterpillars and beetles is also an effective method for small-scale organic farms.\r\n\r\nSoil health management plays a critical role in disease prevention. Well-nourished soil with high organic matter content enhances plant immunity, making crops more resistant to infections. Additionally, solarization—a process where soil is covered with plastic sheets to trap heat—helps eliminate soil-borne pathogens, weed seeds, and insect larvae before planting.\r\n\r\nFarmers also use biopesticides, which contain natural microorganisms such as fungi, bacteria, and viruses that target specific pests. For example, Bacillus thuringiensis (Bt) is a widely used bacterial biopesticide that produces proteins toxic to caterpillars and larvae while being harmless to humans and beneficial insects. Fungal biopesticides like Beauveria bassiana infect and kill insect pests naturally.\r\n\r\nTo prevent the spread of plant diseases, organic farmers practice proper sanitation and field hygiene. Removing infected plants, cleaning farm tools, and avoiding excessive moisture in crop fields reduce the risk of fungal and bacterial infections. Disease-resistant crop varieties are also an essential part of an organic farmer’s pest management strategy.\r\n\r\nIPM emphasizes monitoring and early detection to control pest populations before they become widespread. Farmers regularly inspect their crops for signs of pest damage and disease symptoms. By keeping records of pest occurrences and environmental conditions, they can predict outbreaks and apply appropriate organic control measures in time.\r\n\r\nIn conclusion, Integrated Pest Management (IPM) is a holistic and environmentally friendly approach to pest and disease control in organic farming. By combining crop rotation, natural predators, organic pesticides, mechanical barriers, soil health management, and biopesticides, farmers can effectively manage pests while reducing harm to the environment. This sustainable approach ensures healthier crops, safer food, and a balanced farm ecosystem.\r\n', 'uploadedfiles/1742281996_agribus[1].jpg', '04:00:00', '2025-03-18 07:51:02'),
(4, 1, 'Lesson 4: Water Conservation & Smart Irrigation Techniques', 'Water is a critical resource in agriculture, and its efficient use is essential for sustainable organic farming. With increasing climate change and water scarcity, organic farmers must adopt water conservation techniques to ensure healthy crop growth while minimizing wastage. Proper water management helps maintain soil fertility, reduces erosion, and enhances productivity.\r\n\r\nOne of the most effective methods of conserving water in organic farming is rainwater harvesting. Farmers collect and store rainwater in tanks or reservoirs for later use. This method ensures a steady water supply during dry seasons, reducing dependency on groundwater or irrigation systems. Additionally, simple structures like trenches and swales help direct rainwater to crop fields, maximizing soil moisture retention.\r\n\r\nDrip irrigation is a highly efficient watering technique that delivers water directly to the plant roots through small tubes and emitters. Unlike traditional overhead sprinklers, drip irrigation minimizes evaporation and runoff, ensuring that plants receive adequate moisture without excess water loss. This method is particularly useful in dry regions where water conservation is essential.\r\n\r\nAnother water-efficient irrigation method is mulching, which involves covering the soil with organic materials such as straw, leaves, or wood chips. Mulch helps reduce moisture evaporation, suppress weeds, and regulate soil temperature. As the mulch decomposes, it also enriches the soil with organic matter, improving its ability to retain water.\r\n\r\nCover cropping is another strategy that enhances water conservation. Growing cover crops such as legumes and grasses between planting seasons helps prevent soil erosion, improve soil structure, and increase moisture retention. Cover crops also reduce the impact of heavy rainfall, preventing water runoff and nutrient loss.\r\n\r\nFarmers can also use soil moisture monitoring techniques to optimize irrigation. Simple tools like tensiometers and soil moisture sensors help determine when crops actually need water, preventing over-irrigation. By applying water only when necessary, farmers save water while promoting healthy plant growth.\r\n\r\nContour farming and terracing are effective methods for managing water on sloped land. Contour farming involves planting crops along the natural curves of the land to slow down water runoff and increase soil absorption. Terracing, on the other hand, creates step-like structures on steep slopes, preventing soil erosion and ensuring that water is evenly distributed across the field.\r\n\r\nIn addition to these techniques, organic farmers practice greywater recycling, which involves reusing household wastewater (excluding sewage) for irrigation. Treated greywater from kitchens and bathrooms can be safely used for watering crops, reducing overall water consumption and making farms more self-sufficient.\r\n\r\nAgroforestry, which integrates trees and shrubs with crops, also plays a role in water conservation. Trees provide shade, reducing evaporation, while their deep roots help draw moisture from lower soil layers. This system not only conserves water but also enhances biodiversity and soil fertility.\r\n\r\nIn conclusion, smart water conservation and irrigation techniques are essential for sustainable organic farming. Methods such as rainwater harvesting, drip irrigation, mulching, cover cropping, soil moisture monitoring, and agroforestry help maximize water efficiency while preserving soil health. By implementing these practices, farmers can reduce water wastage, improve crop resilience, and ensure long-term agricultural productivity.\r\n', 'uploadedfiles/1742282185_beach1[1].jpg', '03:00:00', '2025-03-18 07:51:24'),
(5, 1, 'Lesson 5: Organic Certification & Market Opportunities', 'Organic certification is a crucial step for farmers who want to sell their produce as organic. It involves following strict guidelines that prohibit synthetic pesticides, chemical fertilizers, and genetically modified organisms (GMOs). Farmers must undergo inspections by certification bodies to ensure compliance with organic standards. Certification not only builds consumer trust but also provides access to premium markets where organic products are in high demand.\r\n\r\nOnce certified, organic farmers can explore various market opportunities, including direct sales at farmers\' markets, organic food stores, supermarkets, and online platforms. Many consumers prefer organic products due to health and environmental benefits, creating a profitable niche for organic farmers. Additionally, value-added organic products, such as organic juices, dried fruits, and herbal teas, offer higher revenue potential.\r\n\r\nDespite the benefits, organic farmers face challenges such as high certification costs and limited market access. However, government support, cooperatives, and online marketplaces are making it easier for small-scale farmers to enter the organic market. By leveraging these opportunities, organic farmers can increase their profitability while contributing to a healthier food system and a more sustainable environment.\r\n', 'uploadedfiles/1742282318_agribus[1].jpg', '01:20:00', '2025-03-18 07:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `quizze_table`
--

CREATE TABLE `quizze_table` (
  `quizze_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_A` varchar(255) NOT NULL,
  `option_B` varchar(255) NOT NULL,
  `option_C` varchar(255) NOT NULL,
  `option_D` varchar(255) NOT NULL,
  `correct_answer` char(1) DEFAULT NULL CHECK (`correct_answer` in ('A','B','C','D'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_tel` varchar(20) NOT NULL,
  `user_role` enum('Admin','Instructor','Learner','Supplier') NOT NULL,
  `pro_info` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `name`, `surname`, `user_email`, `password`, `user_tel`, `user_role`, `pro_info`, `reg_date`) VALUES
(1, 'Abeid', 'Mashauri', 'abeidmashauri@gmail.com', '123456', '+255753940450', 'Admin', 'System admin', '2025-03-13 20:15:40'),
(2, 'Siaba', 'David', 'siaba@gmail.com', '123456', '+255626140450', 'Instructor', 'Iam instructor', '2025-03-13 20:20:06'),
(3, 'Yohana', 'Andew', 'yohana@gmail.com', '123456', '+255710940972', 'Learner', 'I like Agribusiness', '2025-03-13 20:20:06'),
(4, 'HAMMY', 'DAX', 'hammydax@gmail.com', '123445', '0675456443', 'Instructor', NULL, '2025-03-14 18:34:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course_table`
--
ALTER TABLE `course_table`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `learning_table`
--
ALTER TABLE `learning_table`
  ADD PRIMARY KEY (`learning_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_table`
--
ALTER TABLE `lesson_table`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `quizze_table`
--
ALTER TABLE `quizze_table`
  ADD PRIMARY KEY (`quizze_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_tel` (`user_tel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_table`
--
ALTER TABLE `course_table`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `learning_table`
--
ALTER TABLE `learning_table`
  MODIFY `learning_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lesson_table`
--
ALTER TABLE `lesson_table`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quizze_table`
--
ALTER TABLE `quizze_table`
  MODIFY `quizze_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_table`
--
ALTER TABLE `course_table`
  ADD CONSTRAINT `course_table_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_table`
--
ALTER TABLE `learning_table`
  ADD CONSTRAINT `learning_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_table_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_table`
--
ALTER TABLE `lesson_table`
  ADD CONSTRAINT `lesson_table_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `quizze_table`
--
ALTER TABLE `quizze_table`
  ADD CONSTRAINT `quizze_table_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
