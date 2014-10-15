CREATE SCHEMA tp AUTHORIZATION hitest;
GRANT ALL ON SCHEMA tp TO hitest;

CREATE TABLE tp.tp_user
(
  id integer NOT NULL,
  tm_id smallint NOT NULL,
  nick character varying(255) NOT NULL,
  email character varying(255) NOT NULL,
  password character(32) NOT NULL,
  name character varying(255),
  surname character varying(255),
  patronymic character varying(255),
  sex character varying(6),
  city integer NOT NULL,
  country integer NOT NULL,
  city_other character varying(255),
  date_create timestamp without time zone NOT NULL,
  my_updated timestamp without time zone NOT NULL,
  avatar_ext character varying(5),
  birthday date,
  icq character varying(100),
  www character varying(255),
  profession character varying(100),
  phone character varying(100),
  active boolean NOT NULL,
  show_email boolean NOT NULL,
  show_icq boolean NOT NULL,
  description text,
  interests character varying(255),
  manager boolean NOT NULL,
  company character varying(255),
  stage integer NOT NULL,
  reg_proj smallint NOT NULL,
  specs character varying(255),
  specialization_id smallint,
  password_strength smallint NOT NULL,
  news_subscription_tophotels boolean NOT NULL,
  news_subscription_turpoisk boolean NOT NULL,
  news_subscription_rutraveler boolean NOT NULL,
  news_subscription_travelview boolean NOT NULL,
  news_subscription_traveltalk boolean NOT NULL,
  news_subscription_travelpassport boolean NOT NULL,
  confirmed boolean NOT NULL,
  updated bigint NOT NULL,
  trash boolean NOT NULL,
  hp_id integer NOT NULL,
  hp_updated bigint NOT NULL,
  agent_ti_id integer NOT NULL,
  news_subscription_hotelsbroker boolean NOT NULL,
  not_show_age boolean NOT NULL,
  CONSTRAINT pk_tp_user PRIMARY KEY (id)
);

ALTER TABLE tp.tp_user OWNER TO hitest;
GRANT ALL ON TABLE tp.tp_user TO hitest;

COMMENT ON TABLE tp.tp_user IS 'зарегистрированные пользователи travelpassport';
COMMENT ON COLUMN tp.tp_user.tm_id IS 'id пользователя tourmanagers';
COMMENT ON COLUMN tp.tp_user.phone IS 'телефон';
COMMENT ON COLUMN tp.tp_user.show_email IS 'показывать email';
COMMENT ON COLUMN tp.tp_user.show_icq IS 'показывать icq';
COMMENT ON COLUMN tp.tp_user.description IS 'о себе';
COMMENT ON COLUMN tp.tp_user.interests IS 'интересы';
COMMENT ON COLUMN tp.tp_user.specs IS 'специализация (поле из tourmanagers)';
COMMENT ON COLUMN tp.tp_user.specialization_id IS 'специализация профи из словаря tp_dict_specialization, применимо только для записей с manager = 1';
COMMENT ON COLUMN tp.tp_user.confirmed IS 'подтверждённый пользователь';
COMMENT ON COLUMN tp.tp_user.agent_ti_id IS 'Ид агента с ТурИндекса';

CREATE UNIQUE INDEX idx_tp_user_email ON tp.tp_user USING btree (email);
CREATE INDEX idx_tp_user_hp_id ON tp.tp_user USING btree (hp_id);
CREATE INDEX idx_tp_user_lower_email ON tp.tp_user USING btree (lower(email::text) text_pattern_ops);
CREATE INDEX idx_tp_user_lower_nick ON tp.tp_user USING btree (lower(nick::text) text_pattern_ops);
CREATE UNIQUE INDEX idx_tp_user_nick ON tp.tp_user USING btree (nick);