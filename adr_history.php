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
-- Name: adr_record_details_history; Type: TABLE; Schema: public; Owner: webadmin
--

CREATE TABLE public.adr_record_details_history (
    id integer NOT NULL,
    adr_number character varying(100),
    state integer,
    district integer,
    police_station integer,
    date date,
    "time" time without time zone,
    source_of_information integer,
    mode_of_information integer,
    case_type integer,
    inquest_by character varying(255),
    place_details text,
    complainant_name character varying(255),
    complainant_address character varying(255),
    complainant_father_name character varying(255),
    complainant_mobile character varying(15),
    identifier_name character varying(255),
    identifier_address character varying(255),
    identifier_relation integer,
    identifier_father_name character varying(255),
    identifier_mobile character varying(15),
    dead_name character varying(255),
    dead_gender character varying(10),
    dead_age integer,
    dead_father_name character varying(255),
    dead_address text,
    officer_name character varying(255),
    officer_designation character varying(255),
    officer_id character varying(100),
    officer_mobile character varying(15),
    attachments text,
    status character varying(50) DEFAULT 'Pending'::character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    remark text,
    login_unit_cd integer DEFAULT 0,
    login_sub_unit_cd integer DEFAULT 0,
    created_by numeric,
    updated_by numeric
);


ALTER TABLE public.adr_record_details_history OWNER TO webadmin;

--
-- Name: adr_meeting_details_id_seq; Type: SEQUENCE; Schema: public; Owner: webadmin
--

CREATE SEQUENCE public.adr_meeting_details_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adr_meeting_details_history_id_seq OWNER TO webadmin;

--
-- Name: adr_meeting_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: webadmin
--

ALTER SEQUENCE public.adr_meeting_details_history_id_seq OWNED BY public.adr_record_details_history.id;
--
-- Name: adr_record_details_history id; Type: DEFAULT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.adr_record_details_history ALTER COLUMN id SET DEFAULT nextval('public.adr_meeting_details_history_id_seq'::regclass);
--
-- Name: adr_record_details_history adr_meeting_details_pkey; Type: CONSTRAINT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.adr_record_details_history
    ADD CONSTRAINT adr_meeting_details_history_pkey PRIMARY KEY (id);


--
-- Name: TABLE adr_record_details_history; Type: ACL; Schema: public; Owner: webadmin
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.adr_record_details_history TO webadmin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.adr_record_details_history TO webadmin;       

--
-- Name: SEQUENCE adr_meeting_details_id_seq; Type: ACL; Schema: public; Owner: webadmin
--

GRANT ALL ON SEQUENCE public.adr_meeting_details_id_seq TO webadmin;


--
-- PostgreSQL database dump complete
--
