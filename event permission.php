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
-- Name: event_permission_details_history; Type: TABLE; Schema: public; Owner: webadmin
--

CREATE TABLE public.event_permission_details_history (
    id integer NOT NULL,
    event_number character varying(100),
    event_name character varying(255),
    event_type character varying(100),
    from_date date,
    to_date date,
    from_time time without time zone,
    to_time time without time zone,
    address text,
    pin_code character varying(10),
    state integer,
    district integer,
    police_station integer,
    organizer_name character varying(255),
    organization character varying(255),
    contact_number character varying(20),
    applicant_name character varying(255),
    applicant_age integer,
    applicant_contact character varying(20),
    applicant_address text,
    applicant_gender character varying(10),
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    status character varying(20),
    officer_name character varying(255),
    officer_designation character varying(255),
    officer_id character varying(255),
    officer_mobile character varying(20),
    attachments text,
    remark text,
    login_unit_cd integer DEFAULT 0,
    login_sub_unit_cd integer DEFAULT 0,
    created_by numeric,
    updated_by numeric
);


ALTER TABLE public.event_permission_details_history OWNER TO webadmin;

--
-- Name: event_permission_details_history_id_seq; Type: SEQUENCE; Schema: public; Owner: webadmin
--

CREATE SEQUENCE public.event_permission_details_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_permission_details_history_id_seq OWNER TO webadmin;

--
-- Name: event_permission_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: webadmin
--

ALTER SEQUENCE public.event_permission_details_history_id_seq OWNED BY public.event_permission_details_history.id;
--
-- Name: event_permission_details_history id; Type: DEFAULT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.event_permission_details_history ALTER COLUMN id SET DEFAULT nextval('public.event_permission_details_history_id_seq'::regclass);


--
-- Name: event_permission_details_history event_permission_details_history_pkey; Type: CONSTRAINT; Schema: public; Owner: webadmin
--

ALTER TABLE ONLY public.event_permission_details_history
    ADD CONSTRAINT event_permission_details_history_pkey PRIMARY KEY (id);

--
-- PostgreSQL database dump complete
--
