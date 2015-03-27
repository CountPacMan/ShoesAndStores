--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: brands; Type: TABLE; Schema: public; Owner: danis; Tablespace: 
--

CREATE TABLE brands (
    id integer NOT NULL,
    brand character varying
);


ALTER TABLE brands OWNER TO danis;

--
-- Name: brands_id_seq; Type: SEQUENCE; Schema: public; Owner: danis
--

CREATE SEQUENCE brands_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE brands_id_seq OWNER TO danis;

--
-- Name: brands_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: danis
--

ALTER SEQUENCE brands_id_seq OWNED BY brands.id;


--
-- Name: stores; Type: TABLE; Schema: public; Owner: danis; Tablespace: 
--

CREATE TABLE stores (
    id integer NOT NULL,
    name character varying
);


ALTER TABLE stores OWNER TO danis;

--
-- Name: stores_brands; Type: TABLE; Schema: public; Owner: danis; Tablespace: 
--

CREATE TABLE stores_brands (
    id integer NOT NULL,
    store_id integer,
    brand_id integer
);


ALTER TABLE stores_brands OWNER TO danis;

--
-- Name: stores_brands_id_seq; Type: SEQUENCE; Schema: public; Owner: danis
--

CREATE SEQUENCE stores_brands_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE stores_brands_id_seq OWNER TO danis;

--
-- Name: stores_brands_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: danis
--

ALTER SEQUENCE stores_brands_id_seq OWNED BY stores_brands.id;


--
-- Name: stores_id_seq; Type: SEQUENCE; Schema: public; Owner: danis
--

CREATE SEQUENCE stores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE stores_id_seq OWNER TO danis;

--
-- Name: stores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: danis
--

ALTER SEQUENCE stores_id_seq OWNED BY stores.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: danis
--

ALTER TABLE ONLY brands ALTER COLUMN id SET DEFAULT nextval('brands_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: danis
--

ALTER TABLE ONLY stores ALTER COLUMN id SET DEFAULT nextval('stores_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: danis
--

ALTER TABLE ONLY stores_brands ALTER COLUMN id SET DEFAULT nextval('stores_brands_id_seq'::regclass);


--
-- Data for Name: brands; Type: TABLE DATA; Schema: public; Owner: danis
--

COPY brands (id, brand) FROM stdin;
\.


--
-- Name: brands_id_seq; Type: SEQUENCE SET; Schema: public; Owner: danis
--

SELECT pg_catalog.setval('brands_id_seq', 1, false);


--
-- Data for Name: stores; Type: TABLE DATA; Schema: public; Owner: danis
--

COPY stores (id, name) FROM stdin;
\.


--
-- Data for Name: stores_brands; Type: TABLE DATA; Schema: public; Owner: danis
--

COPY stores_brands (id, store_id, brand_id) FROM stdin;
\.


--
-- Name: stores_brands_id_seq; Type: SEQUENCE SET; Schema: public; Owner: danis
--

SELECT pg_catalog.setval('stores_brands_id_seq', 1, false);


--
-- Name: stores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: danis
--

SELECT pg_catalog.setval('stores_id_seq', 1, false);


--
-- Name: brands_pkey; Type: CONSTRAINT; Schema: public; Owner: danis; Tablespace: 
--

ALTER TABLE ONLY brands
    ADD CONSTRAINT brands_pkey PRIMARY KEY (id);


--
-- Name: stores_brands_pkey; Type: CONSTRAINT; Schema: public; Owner: danis; Tablespace: 
--

ALTER TABLE ONLY stores_brands
    ADD CONSTRAINT stores_brands_pkey PRIMARY KEY (id);


--
-- Name: stores_pkey; Type: CONSTRAINT; Schema: public; Owner: danis; Tablespace: 
--

ALTER TABLE ONLY stores
    ADD CONSTRAINT stores_pkey PRIMARY KEY (id);


--
-- Name: stores_brands_brand_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: danis
--

ALTER TABLE ONLY stores_brands
    ADD CONSTRAINT stores_brands_brand_id_fkey FOREIGN KEY (brand_id) REFERENCES brands(id);


--
-- Name: stores_brands_store_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: danis
--

ALTER TABLE ONLY stores_brands
    ADD CONSTRAINT stores_brands_store_id_fkey FOREIGN KEY (store_id) REFERENCES stores(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: danis
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM danis;
GRANT ALL ON SCHEMA public TO danis;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

