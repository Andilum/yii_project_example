CREATE TABLE hi.hi_user_token
(
  id serial NOT NULL,
  tp_user_id integer NOT NULL,
  token character varying(32) NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_user_token PRIMARY KEY (id)
);

CREATE INDEX idx_hi_user_token_token ON hi.hi_user_token USING btree (token);
