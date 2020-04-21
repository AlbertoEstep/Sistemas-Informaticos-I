-- getTopMonths
DROP FUNCTION IF EXISTS getTopMonths(integer,numeric);
CREATE OR REPLACE FUNCTION getTopMonths(prod integer, prices numeric)
RETURNS TABLE(mes double precision, anyo double precision, prod_totales numeric, precio_total numeric) AS $$
	
begin
RETURN query 
	select *
	from
		(select extract('month' from orderdate) as mm,
			extract('year' from orderdate) as yy
				, sum(cuenta) as prod_totales , sum(total) as precio_total
		from 
			(select orderid,orderdate,count(prod_id) * quantity as cuenta, sum(totalamount) as total
			from orders natural join orderdetail
			GROUP BY orderid,quantity
			order by orderid) as aux
		GROUP BY mm,yy
		order by yy,mm) as aux2
	where aux2.prod_totales >= prod OR aux2.precio_total >= prices;

end;
$$ language plpgsql;

select * from getTopMonths(19000,320000);



	