DROP FUNCTION IF EXISTS clientesDistintos(varchar,integer);
CREATE OR REPLACE FUNCTION clientesDistintos(anyomes varchar, amount integer)
RETURNS TABLE (clientes bigint) AS $$

begin
RETURN query
	SELECT count(DISTINCT customerid)
	FROM orders
	WHERE totalamount > amount 
		and TO_CHAR(orderdate, 'YYYYMM') = anyomes;

end; $$ language plpgsql;

explain analyze select clientesDistintos('201404',100);

drop index if exists idx_query;
drop index if exists idx_query2;

create index idx_query ON orders (orderdate);
create index idx_query2 ON orders (totalamount);

explain analyze select clientesDistintos('201404',300);

DROP FUNCTION IF EXISTS clientesDistintos2(varchar,integer);
CREATE OR REPLACE FUNCTION clientesDistintos2(anyomes , amount integer)
RETURNS TABLE (clientes bigint) AS $$

begin
RETURN query
	SELECT count(DISTINCT customerid)
	FROM orders
	WHERE totalamount > amount 
		and TO_CHAR(orderdate, 'YYYYMM') = anyomes;

end; $$ language plpgsql;
