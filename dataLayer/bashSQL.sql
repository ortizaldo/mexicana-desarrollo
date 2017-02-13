INSERT INTO rol(type ,created_at, updated_at, active) VALUES("SuperAdmin", NOW(), NOW(), 1);
INSERT INTO rol(type ,created_at, updated_at, active) VALUES("Admin", NOW(), NOW(), 1);
INSERT INTO rol(type ,created_at, updated_at, active) VALUES("Agency", NOW(), NOW(), 1);
INSERT INTO rol(type ,created_at, updated_at, active) VALUES("Employee", NOW(), NOW(), 1);

INSERT INTO ( ,created_at, updated_at, active) VALUES("", NOW(), NOW(), 1);





/*mysql> desc city;
+------------+------------------+------+-----+-------------------+-----------------------------+
| Field      | Type             | Null | Key | Default           | Extra                       |
+------------+------------------+------+-----+-------------------+-----------------------------+
| id         | int(10) unsigned | NO   | PRI | NULL              | auto_increment              |
| name       | varchar(255)     | YES  |     | NULL              |                             |
| idState    | int(10) unsigned | YES  | MUL | NULL              |                             |
| created_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| updated_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| active     | tinyint(4)       | YES  |     | 1                 |                             |
+------------+------------------+------+-----+-------------------+-----------------------------+
6 rows in set (0.01 sec)

mysql> desc state;
+------------+------------------+------+-----+-------------------+-----------------------------+
| Field      | Type             | Null | Key | Default           | Extra                       |
+------------+------------------+------+-----+-------------------+-----------------------------+
| id         | int(10) unsigned | NO   | PRI | NULL              | auto_increment              |
| name       | varchar(50)      | YES  |     | NULL              |                             |
| idCountry  | int(10) unsigned | NO   | MUL | NULL              |                             |
| created_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| updated_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| active     | tinyint(4)       | YES  |     | 1                 |                             |
+------------+------------------+------+-----+-------------------+-----------------------------+
6 rows in set (0.00 sec)

mysql> desc country;
+------------+------------------+------+-----+-------------------+-----------------------------+
| Field      | Type             | Null | Key | Default           | Extra                       |
+------------+------------------+------+-----+-------------------+-----------------------------+
| id         | int(10) unsigned | NO   | PRI | NULL              | auto_increment              |
| name       | varchar(50)      | YES  |     | NULL              |                             |
| created_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| updated_at | timestamp        | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
| active     | tinyint(4)       | YES  |     | 1                 |                             |
+------------+------------------+------+-----+-------------------+-----------------------------+
5 rows in set (0.00 sec)*/