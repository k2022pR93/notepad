--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.22
-- Dumped by pg_dump version 9.6.22

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: crime_details_history; Type: TABLE; Schema: public; Owner: webadmin
--

CREATE TABLE public.crime_details_history (
    id integer NOT NULL,
    crime_number character varying(255),
    type_of_crime character varying(255),
    crime_date date,
    crime_time time without time zone,
    police_station integer,
    victim_name character varying(255),
    victim_age integer,
    victim_gender integer,
    victim_contact character varying(255),
    accused_name character varying(255),
    accused_age integer,
    accused_gender integer,
    accused_address text,
    accused_state integer,
    accused_district integer,
    accused_contact character varying(255),
    complainant_name character varying(255),
    complainant_contact character varying(255),
    complainant_relation character varying(255),
    officer_name character varying(255),
    officer_designation character varying(255),
    officer_id character varying(255),
    officer_mobile character varying(255),
    attachments text,
    status character varying(255),
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    remark text,
    login_unit_cd integer DEFAULT 0,
    login_sub_unit_cd integer DEFAULT 0,
    created_by numeric,
    updated_by numeric
);


ALTER TABLE public.crime_details_history OWNER TO webadmin;

--
-- Name: crime_details_history_id_seq; Type: SEQUENCE; Schema: public; Owner: webadmin
--

CREATE SEQUENCE public.crime_details_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.crime_details_history_id_seq OWNER TO webadmin;

--
-- Name: crime_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: webadmin
--

ALTER SEQUENCE public.crime_details_history_id_seq OWNED BY public.crime_details_history.id;
--
-- Name: crime_details_history id; Type: DEFAULT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.crime_details_history ALTER COLUMN id SET DEFAULT nextval('public.crime_details_history_id_seq'::regclass);


--
-- Name: crime_details_history crime_details_history_pkey; Type: CONSTRAINT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.crime_details_history
    ADD CONSTRAINT crime_details_history_pkey PRIMARY KEY (id);

--
-- PostgreSQL database dump complete
--
