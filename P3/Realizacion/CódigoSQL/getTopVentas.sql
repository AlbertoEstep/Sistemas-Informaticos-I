-- getTopVentas.sql

DROP FUNCTION IF EXISTS getTopVentas(INTEGER);
CREATE OR REPLACE FUNCTION getTopVentas(INTEGER) 
RETURNS TABLE (anio INTEGER, titulo CHARACTER VARYING(255), num integer) as $$
DECLARE
	a alias for $1;
begin
	return query(
		SELECT TablaMaximoPorAño.año AS Año, TablaPeliculasConMaximo.movietitle AS Título, TablaMaximoPorAño.Maximo AS Total
		FROM
			(SELECT año, max(Total) AS Maximo FROM (
			SELECT M.movieid, M.movietitle, CAST(count(M.movieid)AS INT) as Total, CAST(date_part('year', O.orderdate)AS INT) AS año
			FROM orders AS O, imdb_movies AS M, products AS P, orderdetail AS OD
			WHERE O.orderid = OD.orderid 
				AND OD.prod_id = P.prod_id
				AND P.movieid = M.movieid
				AND date_part('year', O.orderdate) >= a
			GROUP BY M.movieid, M.movietitle, año)as AllPelis
			GROUP BY año) AS TablaMaximoPorAño
			,
			(SELECT M.movieid, M.movietitle, count(M.movieid) as Total, CAST(date_part('year', O.orderdate)AS INT) AS año
			FROM orders AS O, imdb_movies AS M, products AS P, orderdetail AS OD
			WHERE O.orderid = OD.orderid 
				AND OD.prod_id = P.prod_id
				AND P.movieid = M.movieid
				AND date_part('year', O.orderdate) >= a
			GROUP BY M.movieid, M.movietitle, año) AS TablaPeliculasConMaximo
		WHERE TablaMaximoPorAño.año = TablaPeliculasConMaximo.año
			AND TablaMaximoPorAño.Maximo = TablaPeliculasConMaximo.Total
		ORDER BY año
	);
END;
$$ LANGUAGE plpgsql;

select getTopVentas(2015);
