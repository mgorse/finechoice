use budget;

create table users (
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
email varchar(50) NOT NULL,
pass varchar(50)
);

create table budgets(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(50) NOT NULL,
  amount int NOT NULL
);

create table budgetauth (
user int NOT NULL,
budget int NOT NULL,
admin bool NOT NULL,
primary key (user, budget)
);

create table items(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  budget int NOT NULL,
  name varchar(50),
  mincost int NOT NULL,
  maxcost int NOT NULL
);

create table selections(
  user int NOT NULL,
  item int NOT NULL,
  amount int NOT NULL,
primary key (user, item)
);

create table comments(
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user int NOT NULL,
  item int NOT NULL,
  time timestamp NOT NULL,
  comment mediumtext NOT NULL
);

