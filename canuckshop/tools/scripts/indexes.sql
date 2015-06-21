CREATE INDEX stocks1_index ON stocks (orderid); --USING BTREE;
CREATE INDEX stocks2_index ON stocks (orderid,itemcode); --USING BTREE;
CREATE INDEX stocks3_index ON stocks (orderid,styleid); --USING BTREE;

CREATE INDEX items1_index ON items (itemcode); --USING BTREE;
