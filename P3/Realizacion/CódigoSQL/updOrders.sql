---updOrders

DROP FUNCTION IF EXISTS updateOrders() cascade;
CREATE OR REPLACE FUNCTION updateOrders() RETURNS TRIGGER AS $$
BEGIN
	IF(TG_OP = 'INSERT') THEN
		UPDATE orders SET netamount = netamount + (new.price) * (new.quantity) where orderid=new.orderid;
		UPDATE orders SET totalamount = netamount * ((100 + tax)/100) where orderid=new.orderid;
		return NEW;
	ELSIF(TG_OP = 'UPDATE') THEN
		UPDATE orders SET netamount = netamount + (new.price) * (new.quantity) - (old.price) * (old.quantity) where orderid=new.orderid;
		UPDATE orders SET totalamount = netamount * ((100 + tax)/100) where orderid=new.orderid;
		return null;
	ELSE
		UPDATE orders SET netamount = netamount - (old.price) * (old.quantity) where orderid=new.orderid;
		UPDATE orders SET totalamount = netamount * ((100 + tax)/100) where orderid=new.orderid;
		return null;
	END IF;
END; 
$$ LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS updOrders on orderdetail;
CREATE TRIGGER updOrders AFTER DELETE OR INSERT OR UPDATE ON orderdetail
FOR EACH ROW EXECUTE PROCEDURE updateOrders();
