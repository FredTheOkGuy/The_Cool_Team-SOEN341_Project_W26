-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2026 at 01:29 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `allergies`
--

CREATE TABLE `allergies` (
  `allergy_id` int NOT NULL,
  `allergy` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `allergies`
--

INSERT INTO `allergies` (`allergy_id`, `allergy`) VALUES
(6, 'Beef'),
(9, 'Eggs'),
(3, 'Milk'),
(7, 'Peaches'),
(1, 'Peanuts'),
(8, 'Pork'),
(2, 'Shellfish'),
(5, 'Soy'),
(4, 'Wheat');

-- --------------------------------------------------------

--
-- Table structure for table `calorie_goals`
--

CREATE TABLE `calorie_goals` (
  `goal_id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `daily_goal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `calorie_goals`
--

INSERT INTO `calorie_goals` (`goal_id`, `user_id`, `daily_goal`) VALUES
(1, 4, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `diet_preferences`
--

CREATE TABLE `diet_preferences` (
  `preference_id` int NOT NULL,
  `preference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diet_preferences`
--

INSERT INTO `diet_preferences` (`preference_id`, `preference`) VALUES
(4, 'Carnitarian'),
(3, 'Pescatarian'),
(2, 'Vegan'),
(1, 'Vegetarian');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int NOT NULL,
  `ingredient_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `ingredient_name`) VALUES
(11, '2 lbs beef roast'),
(12, '2 tablespoons salt'),
(13, '2 teaspoons salt'),
(3, 'Beef'),
(19, 'Blah'),
(22, 'Bread'),
(23, 'Butter'),
(5, 'Carrot'),
(16, 'Carrots'),
(25, 'Cheese'),
(7, 'Chicken'),
(20, 'Cool stuff'),
(8, 'Cornichons'),
(9, 'Dijon Mustard'),
(10, 'Egg'),
(24, 'Ham'),
(18, 'Pepper'),
(15, 'Potatoes'),
(4, 'Rice'),
(6, 'Salt'),
(14, 'Steak'),
(21, 'Suprise'),
(17, 'Tomatoes');

-- --------------------------------------------------------

--
-- Table structure for table `meal_schedule`
--

CREATE TABLE `meal_schedule` (
  `schedule_id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `recipe_id` int NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `meal_type` enum('Breakfast','Lunch','Dinner','Snack') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `meal_schedule`
--

INSERT INTO `meal_schedule` (`schedule_id`, `user_id`, `recipe_id`, `day_of_week`, `meal_type`) VALUES
(1, 4, 13, 'Sunday', 'Breakfast'),
(2, 4, 17, 'Sunday', 'Lunch'),
(3, 4, 21, 'Sunday', 'Dinner');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `description` text,
  `prep_time` int DEFAULT NULL,
  `cook_time` int DEFAULT NULL,
  `difficulty_level` enum('easy','medium','hard') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `calories` int DEFAULT NULL,
  `gmo_free` tinyint(1) DEFAULT '0',
  `gluten_free` tinyint(1) DEFAULT '0',
  `lactose_free` tinyint(1) DEFAULT '0',
  `vegan` tinyint(1) DEFAULT '0',
  `vegetarian` tinyint(1) DEFAULT '0',
  `meal_type` enum('breakfast','lunch','dinner') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `user_id`, `recipe_name`, `description`, `prep_time`, `cook_time`, `difficulty_level`, `calories`, `gmo_free`, `gluten_free`, `lactose_free`, `vegan`, `vegetarian`, `meal_type`) VALUES
(11, 4, 'Chicken Tartar', 'This is a delicious chicken tartare my grandmother who sadly died from salmonella passed on to me.', 25, 5, 'easy', 650, 0, 0, 0, 0, 0, 'breakfast'),
(12, 4, 'Simple Salted Beef', 'A minimalist yet flavorful beef dinner prepared with just salt, allowing the natural richness of the beef to shine through.', NULL, NULL, 'easy', 250, 1, 1, 1, 0, 0, 'dinner'),
(13, 4, 'Salt-Crusted Beef Roast', 'A simple yet flavorful dinner featuring a beef roast encrusted with salt, allowing the natural flavors of the beef to shine through in every bite.', NULL, NULL, 'easy', 450, 1, 1, 1, 0, 0, 'dinner'),
(14, 4, 'Salted Beef Roast', 'A simple yet flavorful slow-roasted beef seasoned with just salt, allowing the natural richness of the beef to shine through. Perfect for a hearty carnivore dinner.', 15, 120, 'easy', 450, 1, 1, 1, 0, 0, 'dinner'),
(16, 4, 'Hearty Vegetable and Steak Pot', 'A wholesome and satisfying dinner featuring tender steak alongside roasted potatoes, carrots, and tomatoes, seasoned simply with salt and pepper for a clean and delicious meal.', 15, 45, 'medium', 520, 1, 1, 1, 0, 0, 'dinner'),
(17, 4, 'Blah Surprise', 'A simple and straightforward lunch made with the available ingredients.', 5, 0, 'easy', 0, 1, 1, 1, 1, 1, 'lunch'),
(19, 4, 'Blah Cool Stuff Surprise Dinner', 'A mysterious and delightful dinner combining Blah, Cool Stuff, and Surprise into a harmonious meal that respects all dietary preferences and avoids listed allergens.', 10, 20, 'easy', 200, 1, 1, 1, 1, 1, 'dinner'),
(20, 4, 'Tomato and Ham Cheese Toast', 'A delicious open-faced sandwich featuring toasted bread topped with melted butter, sliced tomatoes, ham, and cheese. Perfect for a quick and satisfying lunch.', 5, 8, 'easy', 385, 0, 0, 0, 0, 0, 'lunch'),
(21, 4, 'Tomato and Cheese Bread Bake', 'A simple yet satisfying dinner dish featuring layers of toasted bread, fresh tomatoes, and melted cheese. Perfect for a quick and comforting meal.', 10, 20, 'easy', 350, 1, 0, 0, 0, 1, 'dinner');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_ingredient_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `ingredient_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_ingredient_id`, `recipe_id`, `ingredient_id`) VALUES
(12, 11, 7),
(13, 11, 8),
(14, 11, 9),
(15, 11, 10),
(16, 12, 3),
(17, 12, 6),
(18, 13, 11),
(19, 13, 12),
(28, 17, 19),
(31, 16, 14),
(32, 16, 15),
(33, 16, 16),
(34, 16, 17),
(35, 16, 6),
(36, 19, 19),
(37, 19, 20),
(38, 19, 21),
(53, 21, 22),
(54, 21, 17),
(55, 21, 25),
(56, 20, 22),
(57, 20, 23),
(58, 20, 17),
(59, 20, 24);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_steps`
--

CREATE TABLE `recipe_steps` (
  `step_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `step_number` int NOT NULL,
  `step_instruction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipe_steps`
--

INSERT INTO `recipe_steps` (`step_id`, `recipe_id`, `step_number`, `step_instruction`) VALUES
(9, 11, 1, 'Cut the chicken into small bits.'),
(10, 11, 2, 'Crack the egg and put the yolk in a bowl.'),
(11, 11, 3, 'Put the Dijon mustard and the pickles in the yolk bowl and mix.'),
(12, 11, 4, 'Add the cut chicken and mix.'),
(13, 11, 5, 'Serve'),
(14, 12, 1, 'Remove the beef from refrigeration and let it rest at room temperature for about 5 minutes.'),
(15, 12, 2, 'Generously season all sides of the beef with salt, pressing it gently into the surface.'),
(16, 12, 3, 'Heat a pan or skillet over high heat until very hot.'),
(17, 12, 4, 'Place the salted beef into the hot pan and sear for 3-4 minutes on each side until a deep brown crust forms.'),
(18, 12, 5, 'Reduce heat to medium and continue cooking to your desired level of doneness (approximately 5-6 minutes more for medium).'),
(19, 12, 6, 'Remove the beef from the pan and let it rest for 5 minutes before serving to allow the juices to redistribute.'),
(20, 12, 7, 'Slice and serve immediately.'),
(21, 13, 1, 'Preheat your oven to 375°F (190°C).'),
(22, 13, 2, 'Pat the beef roast dry with a paper towel to remove any excess moisture.'),
(23, 13, 3, 'Generously rub salt all over the entire surface of the beef roast, ensuring even coverage on all sides.'),
(24, 13, 4, 'Place the salt-crusted beef roast on a roasting rack in a roasting pan.'),
(25, 13, 5, 'Insert a meat thermometer into the thickest part of the roast.'),
(26, 13, 6, 'Roast in the preheated oven for approximately 90 minutes, or until the internal temperature reaches 145°F (63°C) for medium doneness.'),
(27, 13, 7, 'Remove the roast from the oven and let it rest for 10-15 minutes before slicing.'),
(28, 13, 8, 'Slice against the grain and serve immediately.'),
(45, 17, 1, 'Obtain the Blah ingredient.'),
(46, 17, 2, 'Prepare the Blah as desired.'),
(47, 17, 3, 'Serve and enjoy your Blah lunch.'),
(49, 16, 1, 'Preheat your oven to 400°F (200°C).'),
(50, 16, 2, 'Wash and chop the potatoes into bite-sized cubes and slice the carrots into rounds.'),
(51, 16, 3, 'Halve the tomatoes and place them along with the potatoes and carrots on a baking tray.'),
(52, 16, 4, 'Season the vegetables generously with salt and pepper.'),
(53, 16, 5, 'Roast the vegetables in the oven for 30 minutes, turning halfway through, until tender and slightly caramelized.'),
(54, 16, 6, 'While the vegetables roast, season both sides of the steak with salt and pepper.'),
(55, 16, 7, 'Heat a skillet over high heat until very hot, then sear the steak for 3-4 minutes per side for medium doneness, or adjust to your preference.'),
(56, 16, 8, 'Remove the steak from the skillet and let it rest for 5 minutes before slicing.'),
(57, 16, 9, 'Plate the roasted vegetables alongside the sliced steak and serve immediately.'),
(58, 19, 1, 'Gather your Blah, Cool Stuff, and Surprise and place them on a clean preparation surface.'),
(59, 19, 2, 'Carefully prepare each ingredient by cleaning and portioning them appropriately for a dinner serving.'),
(60, 19, 3, 'Combine Blah and Cool Stuff together in a large pot over medium heat and stir for 10 minutes.'),
(61, 19, 4, 'Add the Surprise ingredient to the pot and mix well, allowing all components to meld together for another 10 minutes.'),
(62, 19, 5, 'Plate the dish elegantly, serve warm, and enjoy your mysterious Blah Cool Stuff Surprise Dinner.'),
(84, 21, 1, 'Slice the bread and arrange pieces in a baking dish'),
(85, 21, 2, 'Slice the tomatoes and layer them over the bread'),
(86, 21, 3, 'Grate or slice the cheese and distribute evenly over the tomato layer'),
(87, 21, 4, 'Bake at 375°F (190°C) for 20 minutes until cheese is melted and bread is crispy'),
(88, 21, 5, 'Remove from oven and serve hot'),
(89, 20, 1, 'Slice the bread and toast until golden brown'),
(90, 20, 2, 'Spread butter on both sides of the toasted bread'),
(91, 20, 3, 'Layer ham slices on top of the buttered toast'),
(92, 20, 4, 'Add tomato slices on top of the ham'),
(93, 20, 5, 'Top with cheese and return to toaster oven until cheese melts (about 3-4 minutes)'),
(94, 20, 6, 'Remove and serve immediately while warm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL COMMENT 'Unique identifier for each users',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Username of the user',
  `email` varchar(255) NOT NULL COMMENT 'Email of the user',
  `password` varchar(255) NOT NULL COMMENT 'Password of the user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(4, 'Fred', 'gagne.frederic@hotmail.ca', '$2y$10$910/wniHlrMmrlYcCC/l7O42ZZIv4xpGZBeIbedYL9zMEPCDIWV02'),
(5, 'Test1', 'testEmail@gmail.com', '$2y$10$SpkbLDclP96V/aLFe14MxORgSlVHptv8VKiHnf/jzea7tGX5IdqgG'),
(6, 'asd', 'asd@gmail.com', '$2y$10$Cz8NEfPMv5x9M77ByMsQdu94AVtu9x13gz93Z9cwUSjujhrmNMS3i');

-- --------------------------------------------------------

--
-- Table structure for table `user_allergies`
--

CREATE TABLE `user_allergies` (
  `user_id` int UNSIGNED NOT NULL,
  `allergy_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_allergies`
--

INSERT INTO `user_allergies` (`user_id`, `allergy_id`) VALUES
(4, 1),
(5, 1),
(4, 5),
(4, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_daily_calories`
--

CREATE TABLE `user_daily_calories` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `log_date` date NOT NULL,
  `total_calories` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_daily_calories`
--

INSERT INTO `user_daily_calories` (`id`, `user_id`, `log_date`, `total_calories`) VALUES
(1, 4, '2026-03-25', 1150),
(2, 4, '2026-03-25', 1150);

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int UNSIGNED NOT NULL,
  `preference_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`user_id`, `preference_id`) VALUES
(4, 1),
(4, 2),
(4, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergies`
--
ALTER TABLE `allergies`
  ADD PRIMARY KEY (`allergy_id`),
  ADD UNIQUE KEY `allergy` (`allergy`);

--
-- Indexes for table `calorie_goals`
--
ALTER TABLE `calorie_goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `diet_preferences`
--
ALTER TABLE `diet_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD UNIQUE KEY `preference` (`preference`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `ingredient_name` (`ingredient_name`);

--
-- Indexes for table `meal_schedule`
--
ALTER TABLE `meal_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_ingredient_id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `recipe_steps`
--
ALTER TABLE `recipe_steps`
  ADD PRIMARY KEY (`step_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Indexes for table `user_allergies`
--
ALTER TABLE `user_allergies`
  ADD PRIMARY KEY (`user_id`,`allergy_id`),
  ADD KEY `allergy_id` (`allergy_id`);

--
-- Indexes for table `user_daily_calories`
--
ALTER TABLE `user_daily_calories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`,`preference_id`),
  ADD KEY `preference_id` (`preference_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allergies`
--
ALTER TABLE `allergies`
  MODIFY `allergy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `calorie_goals`
--
ALTER TABLE `calorie_goals`
  MODIFY `goal_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diet_preferences`
--
ALTER TABLE `diet_preferences`
  MODIFY `preference_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `meal_schedule`
--
ALTER TABLE `meal_schedule`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `recipe_ingredient_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `recipe_steps`
--
ALTER TABLE `recipe_steps`
  MODIFY `step_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each users', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_daily_calories`
--
ALTER TABLE `user_daily_calories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calorie_goals`
--
ALTER TABLE `calorie_goals`
  ADD CONSTRAINT `calorie_goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `meal_schedule`
--
ALTER TABLE `meal_schedule`
  ADD CONSTRAINT `meal_schedule_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `meal_schedule_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`);

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`);

--
-- Constraints for table `recipe_steps`
--
ALTER TABLE `recipe_steps`
  ADD CONSTRAINT `recipe_steps_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_allergies`
--
ALTER TABLE `user_allergies`
  ADD CONSTRAINT `user_allergies_ibfk_1` FOREIGN KEY (`allergy_id`) REFERENCES `allergies` (`allergy_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_allergies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_daily_calories`
--
ALTER TABLE `user_daily_calories`
  ADD CONSTRAINT `user_daily_calories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_preferences_ibfk_2` FOREIGN KEY (`preference_id`) REFERENCES `diet_preferences` (`preference_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
