CREATE TABLE hi.hi_allocation_subscription (
  subscriber_id integer NOT NULL,
  allocation_id integer NOT NULL,
  trash boolean NOT NULL DEFAULT false
);

CREATE INDEX idx_hi_allocation_subscription_subscriber_id ON hi.hi_allocation_subscription (subscriber_id);
CREATE INDEX idx_hi_allocation_subscription_allocation_id ON hi.hi_allocation_subscription (allocation_id);

ALTER TABLE hi.hi_allocation_subscription OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_allocation_subscription TO hitest;



CREATE TABLE hi.hi_user_subscription (
  subscriber_id integer NOT NULL,
  tp_user_id integer NOT NULL,
  trash boolean NOT NULL DEFAULT false
);

CREATE INDEX idx_hi_user_subscription_subscriber_id ON hi.hi_user_subscription (subscriber_id);
CREATE INDEX idx_hi_user_subscription_tp_user_id ON hi.hi_user_subscription (tp_user_id);

ALTER TABLE hi.hi_user_subscription OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_user_subscription TO hitest;