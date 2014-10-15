-- Schema: hi

-- DROP SCHEMA hi;

CREATE SCHEMA hi
  AUTHORIZATION hitest;

GRANT ALL ON SCHEMA hi TO hitest;

-- Sequence: hi.seq_hi_owner_id

-- DROP SEQUENCE hi.seq_hi_owner_id;

CREATE SEQUENCE hi.seq_hi_owner_id
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE hi.seq_hi_owner_id
  OWNER TO hitest;


-- Table: hi.hi_like

-- DROP TABLE hi.hi_like;

CREATE TABLE hi.hi_like
(
  id serial NOT NULL,
  tp_user_id integer NOT NULL,
  date timestamp with time zone NOT NULL DEFAULT now(),
  owner_id integer NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_like PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hi.hi_like
  OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_like TO hitest;


-- Table: hi.hi_comment

-- DROP TABLE hi.hi_comment;

CREATE TABLE hi.hi_comment
(
  id bigint NOT NULL DEFAULT nextval('hi.seq_hi_owner_id'::regclass),
  tp_user_id integer NOT NULL,
  date timestamp with time zone NOT NULL DEFAULT now(),
  text text NOT NULL,
  post_id integer NOT NULL,
  lang character varying(2),
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_comment PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hi.hi_comment
  OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_comment TO hitest;



-- Table: hi.hi_post

-- DROP TABLE hi.hi_post;

CREATE TABLE hi.hi_post
(
  id bigint NOT NULL DEFAULT nextval('hi.seq_hi_owner_id'::regclass),
  name varchar(255),
  tp_user_id integer NOT NULL,
  date timestamp with time zone NOT NULL DEFAULT now(),
  text text,
  allocation_id integer,
  lang character varying(2),
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_post PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hi.hi_post
  OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_post TO hitest;


-- Table: hi.hi_photo

-- DROP TABLE hi.hi_photo;

CREATE TABLE hi.hi_photo
(
  id serial NOT NULL,
  name varchar(255),
  tp_user_id integer NOT NULL,
  date timestamp with time zone NOT NULL DEFAULT now(),
  text text,
  ext varchar(4) NOT NULL,
  size bigint NOT NULL,
  body bytea NOT NULL,
  owner_id integer NOT NULL,
  trash boolean NOT NULL DEFAULT false,
  CONSTRAINT pk_hi_photo PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hi.hi_photo
  OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_photo TO hitest;



-- Table: hi.hi_tag

-- DROP TABLE hi.hi_tag;

CREATE TABLE hi.hi_tag
(
  id serial NOT NULL,
  name varchar(255)  NOT NULL,
  CONSTRAINT pk_hi_tag PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hi.hi_tag
  OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_tag TO hitest;
