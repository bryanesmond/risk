DROP SCHEMA IF EXISTS riskdb CASCADE;

CREATE SCHEMA riskdb;

DROP TABLE IF EXISTS riskdb.territory_status;
DROP TABLE IF EXISTS riskdb.continent_status;
DROP TABLE IF EXISTS riskdb.players;
DROP TABLE IF EXISTS riskdb.games;
DROP TABLE IF EXISTS riskdb.colors;
DROP TABLE IF EXISTS riskdb.adjacent_territories;
DROP TABLE IF EXISTS riskdb.territories;
DROP TABLE IF EXISTS riskdb.continents;
DROP TABLE IF EXISTS riskdb.authentication;


SET search_path = riskdb, public;

CREATE TABLE colors(
    id SERIAL PRIMARY KEY,
    name varchar(6) NOT NULL DEFAULT '',
    hex_value CHAR(6) NOT NULL DEFAULT ''
);

INSERT INTO colors (name) VALUES ('green'), ('blue'), ('red'), ('orange'), ('yellow'), ('brown');

/* Map info: The 'continents', 'territories', and 'adjacent_terrories' relations
 are the same for any game. */
create table continents (
    continent_id SERIAL primary key,
    name varchar(20) NOT NULL DEFAULT '',
    unit_value integer not null DEFAULT 0
);

INSERT INTO continents(name, unit_value) VALUES ('North America', 5),
                                                ('South America', 2),
                                                ('Africa', 3),
                                                ('Europe', 5),
                                                ('Asia', 7),
                                                ('Austrailia', 2);

create table territories (
        territory_id SERIAL primary key,
        continent integer not null references continents (continent_id),
        name varchar(30) NOT NULL DEFAULT ''
	-- removed unit_value, all territories have the same value
);

INSERT INTO territories(continent, name) VALUES (1, 'Alaska'), (1, 'Northwest Territories'), (1, 'Greenland'), (1, 'Western Canada'),(1, 'Central Canada'),
                                                (1, 'Eastern Canada'), (1, 'Western United States'), (1, 'Eastern United States'),(1, 'Central America'), 
                                                (2, 'Venezuela'), (2, 'Peru'), (2, 'Brazil'), (2, 'Argentina'),(3, 'North Africa'), (3, 'Egypt'),
                                                (3, 'East Africa'), (3, 'Congo'), (3, 'South Africa'),(3, 'Madagascar'), (4, 'Iceland'), (4, 'Scandanavia'), 
                                                (4, 'Great Britain'), (4, 'Ukraine'), (4, 'Northern Europe'), (4, 'Southern Europe'), (4, 'Western Europe'),
                                                (5, 'Middle East'), (5, 'Ural'), (5, 'Afghanistan'), (5, 'India'), (5, 'Siberia'), (5, 'China'), (5, 'Siam'), 
                                                (5, 'Yakutsk'), (5, 'Irkutsk'), (5, 'Mongolia'),(5, 'Japan'), (5, 'Kamchatka'), (6, 'Indonesia'), 
                                                (6, 'New Guinea'), (6, 'Western Austrailia'), (6, 'Eastern Austrailia');



create table adjacent_territories (
    territory1 integer not null references territories (territory_id),
    territory2 integer not null references territories (territory_id),
    primary key (territory1 , territory2)
);

INSERT INTO adjacent_territories(territory1, territory2) VALUES (1,2), (1,4), (1,38), (2,1), (2,3), (2,4), (2,5), (2,6), (3,2), (3,5), (3,6), (3,20),
                                                                (4,1), (4,2), (4,5), (4,7), (5,2), (5,3), (5,4), (5,6), (5,7), (5,8), (6,2), (6,3), (6,5), (6,8),
                                                                (7,4), (7,5), (7,8), (7,9), (8,5), (8,6), (8,7), (8,9), (9,7), (9,8), (9,10), (10,9), (10,11), (10,12),
                                                                (11,10), (11,12), (11,13), (12,10), (12,11), (12,13), (12,14), (13,11), (13,12), (14,12), (14,15),
                                                                (14,16), (14,17), (14,25), (14,26), (15,14), (15,16), (15,25), (15,27), (16,14), (16,15), (16,17), (16,18),
                                                                (16,19), (16,27), (17,14), (17,16), (17,18), (18,16), (18,17), (18,19), (19,16), (19,18), (20,3), (20,22), (21,23),
                                                                (21,24), (22,20), (22,24), (22,26), (23,21), (23,24), (23,25), (23,27), (23,28), (23,29), (24,21),
                                                                (24,22), (24,23), (24,25), (24,26), (25,14), (25,15), (25,23), (25,24), (25,26), (25,27), (26,14),
                                                                (26,22), (26,24), (26,25), (27,15), (27,16), (27,23), (27,25), (27,29), (27,30), (28,23), (28,29), (28,31), (28,32),
                                                                (29,23), (29,27), (29,28), (29,30), (29,32), (30,27), (30,29), (30,32), (30,33), (31,28), (31,32), (31,34), (31,35), (31,36),
                                                                (32,28), (32,29), (32,30), (32,31), (32,33), (32,36), (33,30), (33,32), (33,39), (34,31), (34,35), (34,38), (35,31), (35,34),
                                                                (35,36), (35,38), (36,31), (36,32), (36,35), (36,37), (36,38), (37,36), (37,38), (38,1), (38,34), (38,35), (38,36), (38,37),
                                                                (39,33), (39,40), (39,41), (40,39), (40,41), (40,42), (41,39), (41,40), (41,42), (42,40), (42,41);


/*Authentication of user*/
create table authentication (
	username 	VARCHAR(30) PRIMARY KEY,
	password_hash 	CHAR(75) NOT NULL,
	pwsalt CHAR(75) NOT NULL,
	salt integer not null
);


/* Game info: The 'games' 'map' and 'color' relations are all dependent on 
 the 'game_id' */
create table games (
    game_id SERIAL primary key,
    creator_id varchar(50) not null references authentication (username),
    status varchar(25) NOT NULL DEFAULT ''
		-- alternatively, 'creator_id' could be replaced with creator_color and
		-- reference 'players'.
);

/* I decided to separate 'users' and 'players', this allows one player to play
 multiple games simultaneously. This is also necessary so that we have a place
 to record which color each player has. */

create table players(
    game_id integer references games(game_id),
    color integer NOT NULL REFERENCES colors(id),
    username varchar(50) not null references authentication,
    primary key (game_id, color)
);

create table territory_status (
    game_id integer references games(game_id),
    territory integer NOT NULL references territories(territory_id),
    owner varchar(50) NOT NULL DEFAULT '',
    num_units integer NOT NULL DEFAULT 0,
    primary key (game_id, territory)
);

create table continent_status(
    game_id integer references games(game_id),
    continent integer NOT NULL REFERENCES continents(continent_id),
    owner varchar(6) NOT NULL DEFAULT '',
    num_units integer NOT NULL DEFAULT 0,
    primary key (game_id)
);
