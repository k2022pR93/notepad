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
-- Name: character_verification_details_history; Type: TABLE; Schema: public; Owner: krishnap
--

CREATE TABLE public.character_verification_details_history (
    id integer NOT NULL,
    number character varying(255),
    state integer,
    district integer,
    police_station integer,
    application_date date,
    application_time time without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    person_name character varying(255),
    status character varying(255),
    address text,
    aadhaar_card_number character varying(255),
    contact_number character varying(255),
    registered_crime_id character varying(255),
    is_crime character varying(10),
    officer_name character varying(255),
    officer_designation character varying(255),
    officer_id character varying(255),
    officer_mobile character varying(255),
    attachments text,
    remark text,
    login_unit_cd integer DEFAULT 0,
    login_sub_unit_cd integer DEFAULT 0,
    created_by numeric,
    updated_by numeric
);


ALTER TABLE public.character_verification_details_history OWNER TO webadmin;

--
-- Name: character_verification_details_history_id_seq; Type: SEQUENCE; Schema: public; Owner: webadmin
--

CREATE SEQUENCE public.character_verification_details_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.character_verification_details_history_id_seq OWNER TO webadmin;

--
-- Name: character_verification_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: webadmin
--

ALTER SEQUENCE public.character_verification_details_history_id_seq OWNED BY public.character_verification_details_history.id;
--
-- Name: character_verification_details_history id; Type: DEFAULT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.character_verification_details_history ALTER COLUMN id SET DEFAULT nextval('public.character_verification_details_history_id_seq'::regclass);
--
-- Name: character_verification_details_history character_verification_details_history_pkey; Type: CONSTRAINT; Schema: public; Owner: krishnap
--

ALTER TABLE ONLY public.character_verification_details_history
    ADD CONSTRAINT character_verification_details_history_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--
