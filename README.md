# Mafia

Mafia is a social deduction game, created by Dimitry Davidoff in 1986. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.

### Preview

### Requirements

- SQL table **bug_reports**:
```sql
CREATE TABLE bug_reports (
	id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	time_stamp TIMESTAMP NOT NULL,
	report VARCHAR(2000) NOT NULL
);
```
- SQL Table **town_details**:
```sql
CREATE TABLE town_details (
	town_id VARCHAR(15) PRIMARY KEY,
	time_stamp TIMESTAMP NOT NULL,
	town_name VARCHAR(50) NOT NULL,
	mob_name VARCHAR(50) NOT NULL,
	owner_id int(2) NOT NULL,
	has_started INT (2) DEFAULT 0,
	game_index INT(2) DEFAULT 0
);
```

### Usage

To play the game visit: [`https://playmafia.cf`](https://playmafia.cf)

### Links

- [Mafia Website](https://mafia.binarystack.org)
- [BinaryStack Website](https://binarystack.org)

### Built by

- [Abishek Devendran](https://github.com/abishekdevendran) (Graphic Designer)
- [Sujit Kumar](https://github.com/therealsujitk) (Lead Developer)
