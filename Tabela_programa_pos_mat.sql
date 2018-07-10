--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.12
-- Dumped by pg_dump version 9.5.12

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: programa_pos_mat; Type: TABLE; Schema: public; Owner: pos
--

CREATE TABLE public.programa_pos_mat (
    id_programa_pos integer NOT NULL,
    tipo_programa_pos_ptbr character varying(255) NOT NULL,
    tipo_programa_pos_en character varying(255),
    tipo_programa_pos_es character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.programa_pos_mat OWNER TO pos;

--
-- Name: programa_pos_mat_id_programa_pos_seq; Type: SEQUENCE; Schema: public; Owner: pos
--

CREATE SEQUENCE public.programa_pos_mat_id_programa_pos_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.programa_pos_mat_id_programa_pos_seq OWNER TO pos;

--
-- Name: programa_pos_mat_id_programa_pos_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: pos
--

ALTER SEQUENCE public.programa_pos_mat_id_programa_pos_seq OWNED BY public.programa_pos_mat.id_programa_pos;


--
-- Name: id_programa_pos; Type: DEFAULT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.programa_pos_mat ALTER COLUMN id_programa_pos SET DEFAULT nextval('public.programa_pos_mat_id_programa_pos_seq'::regclass);


--
-- Data for Name: programa_pos_mat; Type: TABLE DATA; Schema: public; Owner: pos
--

COPY public.programa_pos_mat (id_programa_pos, tipo_programa_pos_ptbr, tipo_programa_pos_en, tipo_programa_pos_es, created_at, updated_at) FROM stdin;
1	Mestrado	Master's degree	Maestría	2017-11-21 15:27:00	2017-11-21 15:27:00
2	Doutorado	PhD degree	Doctorado	2017-11-21 15:27:00	2017-11-21 15:27:00
\.


--
-- Name: programa_pos_mat_id_programa_pos_seq; Type: SEQUENCE SET; Schema: public; Owner: pos
--

SELECT pg_catalog.setval('public.programa_pos_mat_id_programa_pos_seq', 2, true);


--
-- Name: programa_pos_mat_pkey; Type: CONSTRAINT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.programa_pos_mat
    ADD CONSTRAINT programa_pos_mat_pkey PRIMARY KEY (id_programa_pos);


--
-- PostgreSQL database dump complete
--

