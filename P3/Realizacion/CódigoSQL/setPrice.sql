-- setPrice.sql

UPDATE orderdetail SET price = 
	quantity 
	*
	(SELECT products.price 
	* 
	(pow(cast(0.98 as float),
	(select date_part('year', current_date)) - 
	(select date_part('year',orderdate) 
		from orders 
		where orders.orderid = orderdetail.orderid)))
		
	FROM products,orders
	WHERE products.prod_id = orderdetail.prod_id
		AND orders.orderid = orderdetail.orderid)
	FROM products,orders
	WHERE products.prod_id = orderdetail.prod_id 
		AND orders.orderid = orderdetail.orderid;