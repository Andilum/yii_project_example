CREATE TABLE hi.hi_rating_category (
  id serial NOT NULL,
  name character varying(255) NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_rating_category PRIMARY KEY (id)
);

ALTER TABLE hi.hi_rating_category OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_rating_category TO hitest;



CREATE TABLE hi.hi_rating_service (
  id serial NOT NULL,
  name character varying(255) NOT NULL,
  category_id integer NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_rating_service PRIMARY KEY (id)
);

ALTER TABLE hi.hi_rating_service OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_rating_service TO hitest;



CREATE TABLE hi.hi_rating (
  id serial NOT NULL,
  rate double precision NOT NULL,
  label character varying(255) NOT NULL,
  description character varying(255) NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_rating PRIMARY KEY (id)
);

ALTER TABLE hi.hi_rating OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_rating TO hitest;



CREATE TABLE hi.hi_user_rating (
  id serial NOT NULL,
  post_id integer NOT NULL,
  tp_user_id integer NOT NULL,
  service_id integer NOT NULL,
  rating_id integer NOT NULL,
  date timestamp with time zone NOT NULL DEFAULT now(),
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_user_rating PRIMARY KEY (id)
);

ALTER TABLE hi.hi_user_rating OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_user_rating TO hitest;