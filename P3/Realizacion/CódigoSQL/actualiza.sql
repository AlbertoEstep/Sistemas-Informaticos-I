-- actualiza.sql
-- Autores: Sergio Teso - Alberto Estepa

--Foreign keys
ALTER TABLE orders 
ADD FOREIGN KEY (customerid) 
REFERENCES customers(customerid)
ON DELETE CASCADE;

ALTER TABLE orderdetail 
ADD FOREIGN KEY (orderid) 
REFERENCES orders(orderid)
ON DELETE CASCADE;


ALTER TABLE orderdetail 
ADD FOREIGN KEY (prod_id) 
REFERENCES products(prod_id)
ON DELETE CASCADE;

ALTER TABLE inventory 
ADD FOREIGN KEY (prod_id) 
REFERENCES products(prod_id)
ON DELETE CASCADE;

ALTER TABLE imdb_actormovies 
ADD FOREIGN KEY (actorid) 
REFERENCES imdb_actors(actorid)
ON DELETE CASCADE;

ALTER TABLE imdb_actormovies 
ADD FOREIGN KEY (movieid) 
REFERENCES imdb_movies(movieid)
ON DELETE CASCADE;

ALTER TABLE imdb_directormovies 
ADD FOREIGN KEY (directorid) 
REFERENCES imdb_directors(directorid)
ON DELETE CASCADE;

ALTER TABLE imdb_directormovies 
ADD FOREIGN KEY (movieid) 
REFERENCES imdb_movies(movieid)
ON DELETE CASCADE;

ALTER TABLE products 
ADD FOREIGN KEY (movieid) 
REFERENCES imdb_movies(movieid)
ON DELETE CASCADE;

-- COUNTRIES GENRES LANGUAGES---------------------
DROP TABLE IF EXISTS genres CASCADE;
CREATE TABLE genres
(
	genreid serial NOT NULL,
	genre VARCHAR(32),
	CONSTRAINT genres_pkey PRIMARY KEY(genreid)
);
DROP TABLE IF EXISTS languages CASCADE;
CREATE TABLE languages
(
	languageid serial NOT NULL,
	language VARCHAR(32),
	CONSTRAINT languages_pkey PRIMARY KEY(languageid)
);
DROP TABLE IF EXISTS countries CASCADE;
CREATE TABLE countries
(
	countryid serial NOT NULL,
	country VARCHAR(32),
	CONSTRAINT countries_pkey PRIMARY KEY(countryid)
);

-- Rellenar tablas
INSERT INTO countries(country)
(select distinct country
from imdb_moviecountries);

INSERT INTO genres(genre)
(select distinct genre
from imdb_moviegenres);

INSERT INTO languages(language)
(select distinct language
from imdb_movielanguages);

ALTER TABLE imdb_moviegenres 
ADD COLUMN genreid INTEGER 
CONSTRAINT imdb_moviegenres_genreid_fkey
REFERENCES genres(genreid)ON DELETE CASCADE;

ALTER TABLE imdb_moviecountries 
ADD COLUMN countryid INTEGER 
CONSTRAINT imdb_moviecountries_countryid_fkey
REFERENCES countries(countryid)ON DELETE CASCADE;

ALTER TABLE imdb_movielanguages 
ADD COLUMN languageid INTEGER 
CONSTRAINT imdb_movielanguages_languageid_fkey
REFERENCES languages(languageid)ON DELETE CASCADE;

ALTER TABLE imdb_moviegenres 
ADD FOREIGN KEY (genreid) 
REFERENCES genres(genreid)
ON DELETE CASCADE;

----------------Poblar tablas nuevas----------------

UPDATE imdb_moviegenres
SET genreid = genres.genreid 
FROM genres 
WHERE imdb_moviegenres.genre = genres.genre;

ALTER TABLE imdb_moviegenres 
DROP COLUMN genre;

UPDATE imdb_moviecountries
SET countryid = countries.countryid 
FROM countries 
WHERE imdb_moviecountries.country = countries.country;

ALTER TABLE imdb_moviecountries 
DROP COLUMN country;

UPDATE imdb_movielanguages
SET languageid = languages.languageid 
FROM languages 
WHERE imdb_movielanguages.language = languages.language;

ALTER TABLE imdb_movielanguages 
DROP COLUMN language;

-------------------------------------------

--   NOT NULLS -------------

ALTER TABLE customers ALTER COLUMN firstname DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN lastname DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN address1 DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN city DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN country DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN creditcardtype DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN creditcardexpiration DROP NOT NULL;
ALTER TABLE customers ALTER COLUMN region DROP NOT NULL;


-- add income

ALTER TABLE customers ADD CHECK (income >= 0);

-- Crear Alertas

CREATE TABLE alertas(
	alertaid serial NOT NULL,
	prod_id integer,
	CONSTRAINT alertas_pkey PRIMARY KEY(alertaid),
	CONSTRAINT alertas_fkey FOREIGN KEY(prod_id)
		REFERENCES inventory(prod_id)ON DELETE CASCADE
);

-- actualizar los serial id
SELECT setval('alertas_alertaid_seq', (SELECT max(alertaid) FROM alertas));
SELECT setval('countries_countryid_seq', (SELECT max(countryid) FROM countries));
SELECT setval('customers_customerid_seq', (SELECT max(customerid) FROM customers));
SELECT setval('genres_genreid_seq', (SELECT max(genreid) FROM genres));
SELECT setval('languages_languageid_seq', (SELECT max(languageid) FROM languages));
SELECT setval('orders_orderid_seq', (SELECT max(orderid) FROM orders));
SELECT setval('products_prod_id_seq', (SELECT max(prod_id) FROM products));
SELECT setval('imdb_actors_actorid_seq', (SELECT max(actorid) FROM imdb_actors));
SELECT setval('imdb_directors_directorid_seq', (SELECT max(directorid) FROM imdb_directors));
SELECT setval('imdb_movies_movieid_seq', (SELECT max(movieid) FROM imdb_movies));