-- updInventory

DROP FUNCTION IF EXISTS updateI() cascade;
CREATE OR REPLACE FUNCTION updateI() RETURNS TRIGGER AS $$
DECLARE
	temporal record;

BEGIN
	IF NEW.status = 'Paid' THEN
		FOR temporal IN select orderdetail.prod_id as p,stock,sales,orderdetail.quantity as q 
				from inventory,orderdetail 
				where inventory.prod_id = orderdetail.prod_id 
					and NEW.orderid = orderdetail.orderid LOOP
			update inventory set sales = (temporal.sales + temporal.q) where prod_id = temporal.p;
			update inventory set stock = (temporal.stock - temporal.q) where prod_id = temporal.p;
			IF (temporal.stock - temporal.q) = 0 THEN
				INSERT INTO alertas(prod_id) values(temporal.p);
			END IF;
		END LOOP;
	END IF;

RETURN NEW; END; $$ LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS updInventory on orders;
CREATE TRIGGER updInventory BEFORE UPDATE OF status ON orders
FOR EACH ROW EXECUTE PROCEDURE updateI();