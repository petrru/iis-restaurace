-- IDS - projekt
-- 2. část: SQL skript pro vytvoření základních objektů schématu databáze
--
-- Téma: Restaurace
-- Autoři: Tamara Krestianková<xkrest07@stud.fit.vutbr.cz>
--         Petr Rusiňák<xrusin03@stud.fit.vutbr.cz>
-- Datum vytvoření: březen 2017
-- Kódování souboru: UTF-8

-- Odstranění již existujících tabulek:


DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS ingredients_in_items;
DROP TABLE IF EXISTS item_categories;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS ordered_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS reserved_rooms;
DROP TABLE IF EXISTS rooms;

-- ----------------------------------
-- Zákazník:
-- ----------------------------------

CREATE TABLE customers
(
  customer_id  INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
  first_name   VARCHAR(50)   NOT NULL,
  last_name    VARCHAR(50)   NOT NULL,
  phone_number INT           NULL,
  email        VARCHAR(100)  NULL,
  card_number  INT           NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO customers (first_name, last_name)
  VALUES ('Jan', 'Novák');

INSERT INTO customers (first_name, last_name)
  VALUES ('Petr', 'Starý');

INSERT INTO customers (first_name, last_name, card_number)
  VALUES ('Martina', 'Falešná', 87451);

INSERT INTO customers (first_name, last_name, phone_number, card_number)
  VALUES ('Pepa', 'Vomáčka',
          775125713, 4475);

INSERT INTO customers (first_name, last_name, email, card_number)
  VALUES ('Lenka', 'Vomáčková',
          'lenka@example.com', 99999);

INSERT INTO customers
  VALUES (DEFAULT, 'Filip', 'Hodný',
          457123456, 'filip@seznam.cz', 77889);

INSERT INTO customers
  VALUES (DEFAULT, 'Jan', 'Pospíchal',
          800808080, 'jan@example.com', NULL);

-- ----------------------------------
-- Pracovní pozice:
-- ----------------------------------

CREATE TABLE positions
(
  position_id INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(60)   NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO positions VALUES (DEFAULT, 'Vedoucí');
INSERT INTO positions VALUES (DEFAULT, 'Číšník');

-- ----------------------------------
-- Zaměstnanec:
-- ----------------------------------

CREATE TABLE employees
(
  employee_id         INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
  first_name          VARCHAR(50)   NOT NULL,
  last_name           VARCHAR(50)   NOT NULL,
  phone_number        INT           NULL,
  email               VARCHAR(100)  NOT NULL UNIQUE,
  birth_number        NUMERIC(10,0) NOT NULL UNIQUE,
  position_id         INT           NOT NULL,
  salary              INT           NOT NULL,
  bank_account_prefix INT           NOT NULL,
  bank_account        NUMERIC(10,0) NOT NULL,
  bank_code           SMALLINT      NOT NULL,
  street_name         VARCHAR(50)   NOT NULL,
  street_number       INT           NOT NULL,
  city                VARCHAR(50)   NOT NULL,
  zip                 INT           NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO employees
  VALUES (
    DEFAULT, 'Tamara', 'Krestianková',
    777888999, 'tamara@example.com', 9551010007,
    1, 40000, 668, 123456796,
    0100, 'Falešná', 1, 'Brno',
    60001
  );


INSERT INTO employees
  VALUES (
    DEFAULT, 'Petr', 'Rusiňák',
    NULL, 'petr@example.com', 9502090070,
    1, 40000, 9769, 1000000005,
    2700,'Nová', 13, 'Brno', 60001
  );

INSERT INTO employees
  VALUES (
    DEFAULT, 'Pepa', 'Novák',
    776776776, 'pepa@example.com', 8002021863,
    2, 18000, 0, 784319,
    3030,'Husitská', 459, 'Pardubice',
    53001
  );

INSERT INTO employees
  VALUES (
    DEFAULT, 'Hana', 'Nováková',
    NULL, 'hana@example.com', 8551111558,
    2, 18000, 0, 784319,
    3030,'Husitská', 459, 'Pardubice',
    53001
  );

-- ----------------------------------
-- Místnosti:
-- ----------------------------------

CREATE TABLE rooms
(
  room_id     INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
  capacity    SMALLINT      NOT NULL,
  description VARCHAR(50)   NOT NULL,
  tables_from SMALLINT      NOT NULL,
  tables_to   SMALLINT      NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO rooms
  VALUES (DEFAULT, 50, 'Hlavní místnost',
          1, 50);
INSERT INTO rooms
  VALUES (DEFAULT, 30, 'Zahrádka',
          51, 80);
INSERT INTO rooms
  VALUES (DEFAULT, 30, 'Salónek',
          81, 110);

-- ----------------------------------
-- Rezervace:
-- ----------------------------------

CREATE TABLE reservations
(
  reservation_id INT        NOT NULL PRIMARY KEY AUTO_INCREMENT,
  date_created   DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_reserved  DATETIME   NOT NULL,
  customer_id    INT        NOT NULL,
  employee_id    INT        NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO reservations
VALUES (DEFAULT, '2017-01-01 14:00',
        '2017-06-01 15:00', 1, 1);

INSERT INTO reservations
VALUES (DEFAULT, '2017-03-02 14:00',
        '2017-06-01 14:00', 4, 1);

INSERT INTO reservations
VALUES (DEFAULT, '2017-03-02 14:02',
        '2017-06-01 14:00', 2, 1);

INSERT INTO reservations
VALUES (DEFAULT, DEFAULT,
        '2017-06-02 12:00', 1, 1);

INSERT INTO reservations
VALUES (DEFAULT, DEFAULT,
        '2017-06-03 10:00', 5, 1);

INSERT INTO reservations
VALUES (DEFAULT, DEFAULT,
        '2017-06-03 11:00', 6, 1);


-- ----------------------------------
-- Objednávka:
-- ----------------------------------

CREATE TABLE orders
(
  order_id       INT        NOT NULL PRIMARY KEY AUTO_INCREMENT,
  date_created   DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  paid           SMALLINT   NOT NULL DEFAULT 0,
  table_number   INT        NOT NULL,
  reservation_id INT        NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO orders (paid, table_number) VALUES (1, 10);
INSERT INTO orders (paid, table_number) VALUES (1, 15);
INSERT INTO orders (paid, table_number) VALUES (1, 18);
INSERT INTO orders VALUES (DEFAULT, '2017-06-01 14:13',
                           1, 22, 1);
INSERT INTO orders (table_number) VALUES (25);
INSERT INTO orders (table_number) VALUES (27);

-- ----------------------------------
-- Kategorie v menu:
-- ----------------------------------

CREATE TABLE item_categories
(
  category_id INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(50)  NOT NULL,
  menu_order  INT          NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO item_categories VALUES (DEFAULT,
                                    'Polévky',1);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Nealkoholické nápoje', 21);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Alkoholické nápoje', 20);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Hlavní jídla', 2);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Přílohy', 3);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Denní menu', 0);
INSERT INTO item_categories VALUES (DEFAULT,
                                    'Minutky', 10);

-- ----------------------------------
-- Položka v jídelníčku:
-- ----------------------------------

CREATE TABLE items
(
  item_id     INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(60)  NOT NULL,
  available   SMALLINT     NOT NULL,
  price       NUMERIC(7,2) NOT NULL,
  category_id INT          NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO items VALUES (DEFAULT, 'Cibulová', 1,
                          20, 1);
INSERT INTO items VALUES (DEFAULT, 'Česneková', 1,
                          15, 1);
INSERT INTO items VALUES (DEFAULT, 'Rajská', 1,
                          20, 1);
INSERT INTO items VALUES (DEFAULT, 'Knedlíčková', 1,
                          20, 1);
INSERT INTO items VALUES (DEFAULT, 'Písmenková', 1,
                          20, 1);

INSERT INTO items VALUES (DEFAULT, 'Kofola', 1,
                          15, 2);
INSERT INTO items VALUES (DEFAULT, 'Soda', 1,
                          10, 2);
INSERT INTO items VALUES (DEFAULT, 'Pomerančový džus', 1,
                          20, 2);
INSERT INTO items VALUES (DEFAULT, 'Káva', 1,
                          20, 2);
INSERT INTO items VALUES (DEFAULT, 'Sprite', 1,
                          22, 2);

INSERT INTO items VALUES (DEFAULT, 'Pivo Kozel', 1,
                          19, 3);
INSERT INTO items VALUES (DEFAULT, 'Pivo Plzeň', 1,
                          35, 3);
INSERT INTO items VALUES (DEFAULT, 'Pivo Gambrinus', 1,
                          25, 3);
INSERT INTO items VALUES (DEFAULT, 'Víno bílé', 1,
                          80, 3);
INSERT INTO items VALUES (DEFAULT, 'Víno červené', 1,
                          80, 3);
INSERT INTO items VALUES (DEFAULT, 'Vodka', 1,
                          25, 3);
INSERT INTO items VALUES (DEFAULT, 'Rum', 1,
                          24, 3);

INSERT INTO items VALUES (DEFAULT, 'Řízek', 1,
                          99, 4);
INSERT INTO items VALUES (DEFAULT, 'Palačinky', 1,
                          79, 4);
INSERT INTO items VALUES (DEFAULT, 'Smažený sýr', 1,
                          75, 4);
INSERT INTO items VALUES (DEFAULT, 'Špagety', 1,
                          99, 4);
INSERT INTO items VALUES (DEFAULT, 'Kachna', 0,
                          99, 4);

INSERT INTO items VALUES (DEFAULT, 'Brambory', 1,
                          10, 5);
INSERT INTO items VALUES (DEFAULT, 'Rýže', 1,
                          10, 5);
INSERT INTO items VALUES (DEFAULT, 'Houskový knedlík', 1,
                          10, 5);
INSERT INTO items VALUES (DEFAULT, 'Hranolky', 1,
                          10, 5);
INSERT INTO items VALUES (DEFAULT, 'Krokety', 1,
                          10, 5);

INSERT INTO items VALUES (DEFAULT, 'Losos', 1,
                          120, 6);
INSERT INTO items VALUES (DEFAULT, 'Kuřecí plátek', 1,
                          90, 6);
INSERT INTO items VALUES (DEFAULT, 'Guláš', 1,
                          74.90, 6);

INSERT INTO items VALUES (DEFAULT, 'Nakládaný sýr', 1,
                          55, 7);
INSERT INTO items VALUES (DEFAULT, 'Pizza', 1,
                          90, 7);

-- ----------------------------------
-- Ingredience:
-- ----------------------------------

CREATE TABLE ingredients
(
  ingredience_id INT                     NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name           VARCHAR(50)             NOT NULL,
  unit           VARCHAR(5) DEFAULT 'kg' NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO ingredients (name) VALUES ('Maso');
INSERT INTO ingredients (name) VALUES ('Brambory');
INSERT INTO ingredients (name) VALUES ('Rýže');
INSERT INTO ingredients (name, unit) VALUES ('Voda', 'l');
INSERT INTO ingredients (name) VALUES ('Mouka');
INSERT INTO ingredients (name, unit) VALUES ('Mléko', 'l');
INSERT INTO ingredients (name, unit) VALUES ('Hermelín', 'ks');

-- ==================================
-- Tabulky tvořící N:M vztahy:
-- ==================================

-- ----------------------------------
-- Rezervace (obsahují) místnosti:
-- ----------------------------------

CREATE TABLE reserved_rooms
(
  room_id        INT      NOT NULL,
  reservation_id INT      NOT NULL,
  seat_count     SMALLINT NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO reserved_rooms VALUES (1, 1, 2);
INSERT INTO reserved_rooms VALUES (1, 2, 5);
INSERT INTO reserved_rooms VALUES (1, 3, 1);
INSERT INTO reserved_rooms VALUES (1, 4, 50);
INSERT INTO reserved_rooms VALUES (2, 4, 30);
INSERT INTO reserved_rooms VALUES (3, 4, 30);
INSERT INTO reserved_rooms VALUES (2, 5, 5);
INSERT INTO reserved_rooms VALUES (1, 6, 30);
INSERT INTO reserved_rooms VALUES (2, 6, 20);

-- ----------------------------------
-- Objednávky (obsahují) položky:
-- ----------------------------------

CREATE TABLE ordered_items
(
  order_id INT      NOT NULL,
  item_id  INT      NOT NULL,
  amount   SMALLINT NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO ordered_items VALUES (1, 18, 1);
INSERT INTO ordered_items VALUES (1, 23, 1);
INSERT INTO ordered_items VALUES (1, 6, 1);
INSERT INTO ordered_items VALUES (2, 11, 5);
INSERT INTO ordered_items VALUES (2, 12, 3);
INSERT INTO ordered_items VALUES (2, 17, 4);
INSERT INTO ordered_items VALUES (3, 13, 2);
INSERT INTO ordered_items VALUES (3, 14, 1);
INSERT INTO ordered_items VALUES (4, 20, 1);
INSERT INTO ordered_items VALUES (4, 26, 1);
INSERT INTO ordered_items VALUES (4, 11, 1);
INSERT INTO ordered_items VALUES (5, 30, 1);
INSERT INTO ordered_items VALUES (6, 19, 3);

-- ----------------------------------
-- Položky (obsahují) ingredience:
-- ----------------------------------

CREATE TABLE ingredients_in_items
(
  ingredience_id INT          NOT NULL,
  item_id        INT          NOT NULL,
  amount         NUMERIC(7,2) NOT NULL
) ENGINE=InnoDB COLLATE utf8mb4_czech_ci;

INSERT INTO ingredients_in_items VALUES (4, 1, 0.3);
INSERT INTO ingredients_in_items VALUES (4, 2, 0.3);
INSERT INTO ingredients_in_items VALUES (4, 3, 0.3);
INSERT INTO ingredients_in_items VALUES (4, 4, 0.3);
INSERT INTO ingredients_in_items VALUES (4, 5, 0.3);
INSERT INTO ingredients_in_items VALUES (4, 9, 0.2);
INSERT INTO ingredients_in_items VALUES (6, 9, 0.05);
INSERT INTO ingredients_in_items VALUES (1, 18, 0.4);
INSERT INTO ingredients_in_items VALUES (5, 18, 0.1);
INSERT INTO ingredients_in_items VALUES (5, 19, 0.1);
INSERT INTO ingredients_in_items VALUES (6, 19, 0.25);
INSERT INTO ingredients_in_items VALUES (7, 20, 1);
INSERT INTO ingredients_in_items VALUES (5, 20, 0.1);
INSERT INTO ingredients_in_items VALUES (1, 22, 0.5);
INSERT INTO ingredients_in_items VALUES (2, 23, 0.3);
INSERT INTO ingredients_in_items VALUES (3, 24, 0.3);
INSERT INTO ingredients_in_items VALUES (2, 26, 0.3);
INSERT INTO ingredients_in_items VALUES (2, 27, 0.2);
INSERT INTO ingredients_in_items VALUES (1, 29, 0.25);
INSERT INTO ingredients_in_items VALUES (4, 30, 0.3);
INSERT INTO ingredients_in_items VALUES (7, 31, 1.5);

