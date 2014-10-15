CREATE SCHEMA dict AUTHORIZATION hitest;
GRANT ALL ON SCHEMA dict TO hitest;



CREATE TABLE dict.dict_allocation
(
  id integer NOT NULL,
  name character varying(100) NOT NULL,
  name_alt character varying(100),
  name_eng character varying(100),
  name_indx character varying(100),
  cat smallint NOT NULL,
  resort smallint NOT NULL,
  resort_place integer NOT NULL,
  chage_baby smallint NOT NULL,
  chage_small smallint NOT NULL,
  chage_big smallint NOT NULL,
  original integer NOT NULL,
  hotels_network integer NOT NULL,
  tophotels_cat smallint NOT NULL,
  tophotels_cat_confirm boolean NOT NULL,
  hotel_promo boolean NOT NULL,
  bonus boolean NOT NULL,
  active boolean NOT NULL,
  trash boolean NOT NULL,
  date_create timestamp without time zone NOT NULL,
  updated bigint NOT NULL,
  allocation_type smallint NOT NULL,
  staff_modified integer NOT NULL,
  hotels_subnetwork integer NOT NULL,
  date_modified timestamp without time zone,
  CONSTRAINT pk_dict_allocation PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE dict.dict_allocation
  OWNER TO hitest;
GRANT ALL ON TABLE dict.dict_allocation TO hitest;

COMMENT ON TABLE dict.dict_allocation
  IS 'таблица Отели';



CREATE INDEX idx_dict_allocation_resort
  ON dict.dict_allocation
  USING btree
  (resort );
COMMENT ON INDEX dict.idx_dict_allocation_resort
  IS 'Индекс по курорту. На турпоиске часто идет связь местоположения с курортом';


CREATE INDEX idx_dict_allocation_updated
  ON dict.dict_allocation
  USING btree
  (updated );




CREATE TABLE dict.dict_alloccat
(
  id smallint NOT NULL,
  name character varying(50) NOT NULL,
  nick character varying(50),
  name_eng character varying(50),
  description character varying(255),
  active boolean NOT NULL,
  trash boolean NOT NULL,
  date_create timestamp without time zone NOT NULL,
  updated bigint NOT NULL,
  weight numeric(6,2) NOT NULL,
  staff_modified integer NOT NULL,
  date_modified timestamp without time zone,
  CONSTRAINT pk_dict_alloccat PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE dict.dict_alloccat
  OWNER TO hitest;
GRANT ALL ON TABLE dict.dict_alloccat TO hitest;


CREATE INDEX idx_dict_alloccat_updated
  ON dict.dict_alloccat
  USING btree
  (updated );



CREATE TABLE dict.dict_country
(
  id integer NOT NULL,
  name character varying(50) NOT NULL,
  name_eng character varying(50),
  nick character varying(50),
  label character varying(50),
  region integer NOT NULL,
  name_genitive character varying(50),
  active boolean NOT NULL,
  trash boolean NOT NULL,
  date_create timestamp without time zone NOT NULL,
  updated bigint NOT NULL,
  phone_code integer NOT NULL,
  offer_currency smallint,
  staff_modified integer,
  date_modified timestamp without time zone,
  CONSTRAINT pk_dict_country PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE dict.dict_country
  OWNER TO hitest;
GRANT ALL ON TABLE dict.dict_country TO hitest;


CREATE INDEX idx_dict_country_name
  ON dict.dict_country
  USING btree
  (name );


CREATE INDEX idx_dict_country_updated
  ON dict.dict_country
  USING btree
  (updated );




CREATE TABLE dict.dict_resort
(
  id smallint NOT NULL,
  country smallint NOT NULL,
  name character varying(50) NOT NULL,
  name_eng character varying(50),
  active boolean NOT NULL,
  trash boolean NOT NULL,
  date_create timestamp without time zone NOT NULL,
  updated bigint NOT NULL,
  capital smallint,
  staff_modified integer NOT NULL,
  date_modified timestamp without time zone,
  CONSTRAINT pk_dict_resort PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);
ALTER TABLE dict.dict_resort
  OWNER TO hitest;
GRANT ALL ON TABLE dict.dict_resort TO hitest;
COMMENT ON TABLE dict.dict_resort
  IS 'Таблица городов соответствующих странам';
COMMENT ON COLUMN dict.dict_resort.country IS 'id страны';
COMMENT ON COLUMN dict.dict_resort.name IS 'название города';
COMMENT ON COLUMN dict.dict_resort.name_eng IS 'английское название города';
COMMENT ON COLUMN dict.dict_resort.capital IS 'столица';



CREATE INDEX idx_dict_resort_country
  ON dict.dict_resort
  USING btree
  (country );
COMMENT ON INDEX dict.idx_dict_resort_country
  IS 'В админке для вывода курортов по определенной стране';


CREATE INDEX idx_dict_resort_name
  ON dict.dict_resort
  USING btree
  (name );


CREATE INDEX idx_dict_resort_updated
  ON dict.dict_resort
  USING btree
  (updated );



