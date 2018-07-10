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
-- Name: formacao; Type: TABLE; Schema: public; Owner: pos
--

CREATE TABLE public.formacao (
    id integer NOT NULL,
    tipo_ptbr character varying(50) NOT NULL,
    tipo_en character varying(50),
    tipo_es character varying(50),
    nivel character varying(50),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.formacao OWNER TO pos;

--
-- Name: formacao_id_seq1; Type: SEQUENCE; Schema: public; Owner: pos
--

CREATE SEQUENCE public.formacao_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formacao_id_seq1 OWNER TO pos;

--
-- Name: formacao_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: pos
--

ALTER SEQUENCE public.formacao_id_seq1 OWNED BY public.formacao.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.formacao ALTER COLUMN id SET DEFAULT nextval('public.formacao_id_seq1'::regclass);


--
-- Data for Name: formacao; Type: TABLE DATA; Schema: public; Owner: pos
--

COPY public.formacao (id, tipo_ptbr, tipo_en, tipo_es, nivel, created_at, updated_at) FROM stdin;
6	Mestrado Profissional	\N	\N	Pós-Graduação	2017-11-21 15:27:00	2017-11-21 15:27:00
8	Especialização	\N	\N	Pós-Graduação	2017-11-21 15:27:00	2017-11-21 15:27:00
1	Bacharelado	Bachelor’s degree	Bachillerato	Graduação	2017-11-21 15:27:00	2018-05-03 16:24:26
3	Ambos	Both	Ambos	Graduação	2017-11-21 15:27:00	2018-05-03 16:29:51
2	Licenciatura	College degree	Licenciatura	Graduação	2017-11-21 15:27:00	2018-05-03 16:31:05
5	Mestrado Acadêmico	Master’s degree	Magíster	Pós-Graduação	2017-11-21 15:27:00	2018-05-03 16:32:23
4	Não se Aplica	Not applicable	No se aplica	Graduação	2017-11-21 15:27:00	2018-05-03 16:32:51
7	Doutorado	PhD	Doctorado	Pós-Graduação	2017-11-21 15:27:00	2018-05-03 16:33:44
\.


--
-- Name: formacao_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: pos
--

SELECT pg_catalog.setval('public.formacao_id_seq1', 1, false);


--
-- Name: formacao_pkey1; Type: CONSTRAINT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.formacao
    ADD CONSTRAINT formacao_pkey1 PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

