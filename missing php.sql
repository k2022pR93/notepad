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
-- Name: missing_record_details_history; Type: TABLE; Schema: public; Owner: webadmin
--

CREATE TABLE public.missing_record_details_history (
    id integer NOT NULL,
    missing_number character varying(100),
    state integer,
    district integer,
    police_station integer,
    missing_date date,
    missing_time time without time zone,
    missing_place text,
    alias_name character varying(100),
    missing_person_name character varying(255),
    missing_person_father_name character varying(255),
    gender character varying(20),
    age integer,
    mobile_number character varying(15),
    current_address text,
    permanent_address text,
    complexion character varying(100),
    height character varying(50),
    build character varying(100),
    face_type character varying(100),
    eye_type character varying(100),
    eye_color character varying(50),
    nose_type character varying(100),
    moustache character varying(50),
    beard character varying(50),
    hair_type character varying(100),
    hair_color character varying(50),
    special_marks text,
    complainant_name character varying(255),
    complainant_address text,
    complainant_father_name character varying(255),
    complainant_mobile character varying(15),
    complainant_relation character varying(100),
    officer_name character varying(255),
    officer_designation character varying(255),
    officer_id character varying(50),
    officer_mobile character varying(15),
    attachments text,
    remark text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    status character varying(50),
    weight integer,
    identification_marks text,
    login_unit_cd integer DEFAULT 0,
    login_sub_unit_cd integer DEFAULT 0,
    created_by numeric,
    updated_by numeric
);


ALTER TABLE public.missing_record_details_history OWNER TO webadmin;

--
-- Name: missing_record_details_history_id_seq; Type: SEQUENCE; Schema: public; Owner: webadmin
--

CREATE SEQUENCE public.missing_record_details_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.missing_record_details_history_id_seq OWNER TO webadmin;

--
-- Name: missing_record_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: webadmin
--

ALTER SEQUENCE public.missing_record_details_history_id_seq OWNED BY public.missing_record_details_history.id;
--
-- Name: missing_record_details_history id; Type: DEFAULT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.missing_record_details_history ALTER COLUMN id SET DEFAULT nextval('public.missing_record_details_history_id_seq'::regclass);


--
-- Name: missing_record_details_history missing_record_details_history_pkey; Type: CONSTRAINT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.missing_record_details_history
    ADD CONSTRAINT missing_record_details_history_pkey PRIMARY KEY (id);

--
-- Name: TABLE missing_record_details_history; Type: ACL; Schema: public; Owner: webadmin
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.missing_record_details_history TO webadmin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.missing_record_details_history TO webadmin;

--
-- Name: SEQUENCE missing_record_details_history_id_seq; Type: ACL; Schema: public; Owner: webadmin
--

GRANT ALL ON SEQUENCE public.missing_record_details_history_id_seq TO webadmin;
--
-- PostgreSQL database dump complete
--
