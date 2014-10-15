DROP TABLE hi.hi_tag;
CREATE TABLE hi.hi_tag
(
  id integer NOT NULL,
  name varchar(255) NOT NULL,
  hash varchar(32) NOT NULL
);

ALTER TABLE hi.hi_tag OWNER TO hitest;
GRANT ALL ON TABLE hi.hi_tag TO hitest;

CREATE INDEX hi_tag_hash ON hi.hi_tag (hash);