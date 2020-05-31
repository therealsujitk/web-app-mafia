# Mafia

Mafia is a social deduction game, created by Dimitry Davidoff in 1986. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.

### Preview

![Preview 1](https://i.imgur.com/NEy5d3i.png)

![Preview 2](https://i.imgur.com/NOOkvyN.png)

![Preview 3](https://i.imgur.com/IoBpD6w.png)

![Preview 4](https://i.imgur.com/cQL5cTZ.png)

![Preview 5](https://i.imgur.com/hXyh1BR.png)

### Requirements

- PHP 5.6+
- MySQL 5.2+
- SQL table **bug_reports**:
```sql
CREATE TABLE bug_reports (
	id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	time_stamp TIMESTAMP NOT NULL,
	report VARCHAR(2000) NOT NULL
);
```
- SQL table **town_details**:
```sql
CREATE TABLE town_details (
	town_id VARCHAR(15) PRIMARY KEY,
	time_stamp TIMESTAMP NOT NULL,
	town_name VARCHAR(50) NOT NULL,
	mob_name VARCHAR(50) NOT NULL,
	has_started INT (2) DEFAULT 0 NOT NULL,
	game_index INT(2) DEFAULT 0 NOT NULL,
	daily_index INT(2) DEFAULT 0 NOT NULL,
	daily_max INT(2) DEFAULT 3 NOT NULL
);
```
- SQL table **statistics**:
```sql
CREATE TABLE statistics (
	id INT(1) DEFAULT 1 NOT NULL,
	players_joined INT(255) DEFAULT 0 NOT NULL,
	games_played INT(255) DEFAULT 0 NOT NULL
);
```
- SQL insert into **statistics**
```sql
INSERT INTO statistics (id, players_joined, games_played) VALUES ('1', '0', '0');
```

### Usage

To play the game visit: [`https://playmafia.cf/`](https://playmafia.cf/)

### Links

- [Mafia Website](https://mafia.binarystack.org)
- [BinaryStack Website](https://binarystack.org)

### Built by

- [Abishek Devendran](https://github.com/abishekdevendran) (Graphic Designer)
- [Sujit Kumar](https://github.com/therealsujitk) (Lead Software Developer)
