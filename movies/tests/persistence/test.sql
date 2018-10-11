-- This is a trimmed down version of the Sakila Sample Database which has been modified to be compatible with SQLite3. 

CREATE TABLE actor (
  actor_id SMALLINT UNSIGNED NOT NULL,
  first_name VARCHAR(45) NOT NULL,
  last_name VARCHAR(45) NOT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO actor VALUES
(1,'PENELOPE','GUINESS','2006-02-15 04:34:33'),
(2,'NICK','WAHLBERG','2006-02-15 04:34:33'),
(3,'ED','CHASE','2006-02-15 04:34:33'),
(4,'JENNIFER','DAVIS','2006-02-15 04:34:33'),
(5,'JOHNNY','LOLLOBRIGIDA','2006-02-15 04:34:33'),
(6,'BETTE','NICHOLSON','2006-02-15 04:34:33'),
(7,'GRACE','MOSTEL','2006-02-15 04:34:33'),
(8,'MATTHEW','JOHANSSON','2006-02-15 04:34:33'),
(9,'JOE','SWANK','2006-02-15 04:34:33'),
(10,'CHRISTIAN','GABLE','2006-02-15 04:34:33');

CREATE TABLE category (
  category_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(25) NOT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO category VALUES
(1,'Action','2006-02-15 04:46:27'),
(2,'Animation','2006-02-15 04:46:27'),
(3,'Children','2006-02-15 04:46:27'),
(4,'Classics','2006-02-15 04:46:27'),
(5,'Comedy','2006-02-15 04:46:27'),
(6,'Documentary','2006-02-15 04:46:27'),
(7,'Drama','2006-02-15 04:46:27'),
(8,'Family','2006-02-15 04:46:27'),
(9,'Foreign','2006-02-15 04:46:27'),
(10,'Games','2006-02-15 04:46:27'),
(11,'Horror','2006-02-15 04:46:27'),
(12,'Music','2006-02-15 04:46:27'),
(13,'New','2006-02-15 04:46:27'),
(14,'Sci-Fi','2006-02-15 04:46:27'),
(15,'Sports','2006-02-15 04:46:27'),
(16,'Travel','2006-02-15 04:46:27');

CREATE TABLE film (
  film_id SMALLINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  release_year YEAR DEFAULT NULL,
  language_id TINYINT UNSIGNED NOT NULL,
  original_language_id TINYINT UNSIGNED DEFAULT NULL,
  rental_duration TINYINT UNSIGNED NOT NULL DEFAULT 3,
  rental_rate DECIMAL(4,2) NOT NULL DEFAULT 4.99,
  length SMALLINT UNSIGNED DEFAULT NULL,
  replacement_cost DECIMAL(5,2) NOT NULL DEFAULT 19.99,
  rating VARCHAR(5),
  special_features VARCHAR(255) DEFAULT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO film VALUES
(1,'ACADEMY DINOSAUR','A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies',2006,1,NULL,6,'0.99',86,'20.99','PG','Deleted Scenes,Behind the Scenes','2006-02-15 05:03:42'),
(2,'ACE GOLDFINGER','A Astounding Epistle of a Database Administrator And a Explorer who must Find a Car in Ancient China',2006,1,NULL,3,'4.99',48,'12.99','G','Trailers,Deleted Scenes','2006-02-15 05:03:42'),
(3,'ADAPTATION HOLES','A Astounding Reflection of a Lumberjack And a Car who must Sink a Lumberjack in A Baloon Factory',2006,1,NULL,7,'2.99',50,'18.99','NC-17','Trailers,Deleted Scenes','2006-02-15 05:03:42'),
(4,'AFFAIR PREJUDICE','A Fanciful Documentary of a Frisbee And a Lumberjack who must Chase a Monkey in A Shark Tank',2006,1,NULL,5,'2.99',117,'26.99','G','Commentaries,Behind the Scenes','2006-02-15 05:03:42'),
(5,'AFRICAN EGG','A Fast-Paced Documentary of a Pastry Chef And a Dentist who must Pursue a Forensic Psychologist in The Gulf of Mexico',2006,1,NULL,6,'2.99',130,'22.99','G','Deleted Scenes','2006-02-15 05:03:42'),
(6,'AGENT TRUMAN','A Intrepid Panorama of a Robot And a Boy who must Escape a Sumo Wrestler in Ancient China',2006,1,NULL,3,'2.99',169,'17.99','PG','Deleted Scenes','2006-02-15 05:03:42'),
(7,'AIRPLANE SIERRA','A Touching Saga of a Hunter And a Butler who must Discover a Butler in A Jet Boat',2006,1,NULL,6,'4.99',62,'28.99','PG-13','Trailers,Deleted Scenes','2006-02-15 05:03:42'),
(8,'AIRPORT POLLOCK','A Epic Tale of a Moose And a Girl who must Confront a Monkey in Ancient India',2006,1,NULL,6,'4.99',54,'15.99','R','Trailers','2006-02-15 05:03:42'),
(9,'ALABAMA DEVIL','A Thoughtful Panorama of a Database Administrator And a Mad Scientist who must Outgun a Mad Scientist in A Jet Boat',2006,1,NULL,3,'2.99',114,'21.99','PG-13','Trailers,Deleted Scenes','2006-02-15 05:03:42'),
(10,'ALADDIN CALENDAR','A Action-Packed Tale of a Man And a Lumberjack who must Reach a Feminist in Ancient China',2006,1,NULL,6,'4.99',63,'24.99','NC-17','Trailers,Deleted Scenes','2006-02-15 05:03:42');

CREATE TABLE film_actor (
  actor_id SMALLINT UNSIGNED NOT NULL,
  film_id SMALLINT UNSIGNED NOT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO film_actor VALUES
(1,1,'2006-02-15 05:05:03'),
(1,23,'2006-02-15 05:05:03'),
(1,25,'2006-02-15 05:05:03'),
(1,106,'2006-02-15 05:05:03'),
(1,140,'2006-02-15 05:05:03'),
(1,166,'2006-02-15 05:05:03'),
(1,277,'2006-02-15 05:05:03'),
(1,361,'2006-02-15 05:05:03'),
(1,438,'2006-02-15 05:05:03'),
(1,499,'2006-02-15 05:05:03');

CREATE TABLE film_category (
  film_id SMALLINT UNSIGNED NOT NULL,
  category_id TINYINT UNSIGNED NOT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO film_category VALUES
-- This first row is custom to test a film having multiple categories. 
(1,1,'2006-02-15 05:07:09'),
(1,6,'2006-02-15 05:07:09'),
(2,11,'2006-02-15 05:07:09'),
(3,6,'2006-02-15 05:07:09'),
(4,11,'2006-02-15 05:07:09'),
(5,8,'2006-02-15 05:07:09'),
(6,9,'2006-02-15 05:07:09'),
(7,5,'2006-02-15 05:07:09'),
(8,11,'2006-02-15 05:07:09'),
(9,11,'2006-02-15 05:07:09'),
(10,15,'2006-02-15 05:07:09');

CREATE TABLE language (
  language_id TINYINT UNSIGNED NOT NULL,
  name CHAR(20) NOT NULL,
  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO language VALUES
(1,'English','2006-02-15 05:02:19'),
(2,'Italian','2006-02-15 05:02:19'),
(3,'Japanese','2006-02-15 05:02:19'),
(4,'Mandarin','2006-02-15 05:02:19'),
(5,'French','2006-02-15 05:02:19'),
(6,'German','2006-02-15 05:02:19');