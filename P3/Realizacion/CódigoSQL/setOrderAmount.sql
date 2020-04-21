-- setOrderAmount
create or replace function setOrderAmount()
returns void as $$

declare

begin

UPDATE orders SET netamount =
	suma 
	FROM
	(select orderid,sum(price * quantity) as suma
	from orders NATURAL JOIN orderdetail 
	GROUP BY orderid) as aux
	where orders.orderid = aux.orderid;

UPDATE orders SET totalamount =
	suma 
	FROM
	(select orderid,netamount * (100+tax)/100 as suma
	from orders NATURAL JOIN orderdetail 
	GROUP BY orderid) as aux
	where orders.orderid = aux.orderid;
	
end;
$$ language plpgsql;

select setOrderAmount();