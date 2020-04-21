ALTER TABLE customers ADD promo INTEGER;

DROP FUNCTION IF EXISTS promo() cascade;
CREATE OR REPLACE FUNCTION promo() returns TRIGGER AS $$
DECLARE
BEGIN
	UPDATE orders 
		SET netamount = netamount*(100 - NEW.promo)/100
		WHERE customerid = NEW.customerid AND status IS NULL;
	perform pg_sleep(20);
RETURN NEW;
END; $$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS updPromo on customers;
CREATE TRIGGER updPromo AFTER UPDATE OR INSERT ON customers
FOR EACH ROW EXECUTE PROCEDURE promo();

--Creacion de uno o varios carritos (status a NULL) mediante la sentencia UPDATE.
UPDATE orders SET status = NULL WHERE orderid < 10;
