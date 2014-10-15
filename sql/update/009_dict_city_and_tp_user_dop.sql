CREATE TABLE dict.dict_city (
  id integer NOT NULL,
  name character varying(50) NOT NULL,
  active boolean NOT NULL,
  trash boolean NOT NULL,
  updated bigint NOT NULL,
  date_create timestamp without time zone NOT NULL,
  country integer,
  district integer,
  name_eng character varying(50),
  staff_modified integer NOT NULL,
  resort integer,
  date_modified timestamp without time zone NOT NULL,
  CONSTRAINT pk_dict_city PRIMARY KEY (id)
);

ALTER TABLE dict.dict_city OWNER TO hitest;
GRANT ALL ON TABLE dict.dict_city TO hitest;

CREATE INDEX idx_dict_city_country ON dict.dict_city (country);
CREATE INDEX idx_dict_city_resort ON dict.dict_city (resort);
CREATE INDEX idx_dict_city_updated ON dict.dict_city (updated);
CREATE INDEX ix_dict_city_name ON dict.dict_city (name);



CREATE TABLE tp.tp_user_dop (
  id integer NOT NULL,
  facebook character varying(255),
  twitter character varying(255),
  vkontakte character varying(255),
  livejournal character varying(255),
  odnoklassniki character varying(255),
  plusgoogle character varying(255),
  soc_www character varying(255),
  updated bigint NOT NULL DEFAULT 0,
  CONSTRAINT pk_tp_user_dop PRIMARY KEY (id)
);

ALTER TABLE tp.tp_user_dop OWNER TO hitest;
GRANT ALL ON TABLE tp.tp_user_dop TO hitest;

CREATE INDEX idx_tp_user_dop_upd ON tp.tp_user_dop (updated);