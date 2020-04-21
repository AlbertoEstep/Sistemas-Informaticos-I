explain select count(*)
from orders 
where status is null;

explain select count(*) 
from orders 
where status ='Shipped';


Drop index if exists idx_status;
CREATE INDEX if not exists idx_status ON orders(status);

ANALYZE VERBOSE orders;

explain select count(*) 
from orders 
where status ='Paid';

explain select count(*) 
from orders 
where status ='Processed';
