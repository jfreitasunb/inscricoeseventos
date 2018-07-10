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
-- Name: area_pos_mat; Type: TABLE; Schema: public; Owner: pos
--

CREATE TABLE public.area_pos_mat (
    id_area_pos integer NOT NULL,
    nome_ptbr character varying(200) NOT NULL,
    nome_en character varying(200),
    nome_es character varying(200),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.area_pos_mat OWNER TO pos;

--
-- Name: area_pos_mat_id_area_pos_seq1; Type: SEQUENCE; Schema: public; Owner: pos
--

CREATE SEQUENCE public.area_pos_mat_id_area_pos_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.area_pos_mat_id_area_pos_seq1 OWNER TO pos;

--
-- Name: area_pos_mat_id_area_pos_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: pos
--

ALTER SEQUENCE public.area_pos_mat_id_area_pos_seq1 OWNED BY public.area_pos_mat.id_area_pos;


--
-- Name: id_area_pos; Type: DEFAULT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.area_pos_mat ALTER COLUMN id_area_pos SET DEFAULT nextval('public.area_pos_mat_id_area_pos_seq1'::regclass);


--
-- Data for Name: area_pos_mat; Type: TABLE DATA; Schema: public; Owner: pos
--

COPY public.area_pos_mat (id_area_pos, nome_ptbr, nome_en, nome_es, created_at, updated_at) FROM stdin;
1	Álgebra	Algebra	Álgebra	2017-11-21 15:27:00	2018-05-03 16:20:13
2	Análise	Analysis	Análisis	2017-11-21 15:27:00	2018-05-03 16:20:26
3	Análise Numérica	Numerical Analysis	Análisis Numérico	2017-11-21 15:27:00	2018-05-03 16:21:08
4	Dinâmica de Fluidos	Fluid Dynamics	Dinamica de Fluidos	2017-11-21 15:27:00	2018-05-03 16:21:24
5	Geometria	Geometry	Geometría	2017-11-21 15:27:00	2018-05-03 16:21:33
6	Probabilidade	Probability	Probabilidad	2017-11-21 15:27:00	2018-05-03 16:21:45
7	Sistemas Dinâmicos	Dynamical Systems	Sistemas Dinámicos	2017-11-21 15:27:00	2018-05-03 16:21:57
8	Teoria da Computação	Theory of Computation	Teoría de la Computación	2017-11-21 15:27:00	2018-05-03 16:22:12
9	Teoria dos Números	Number Theory	Teoría de los Números	2017-11-21 15:27:00	2018-05-03 16:22:26
\.


--
-- Name: area_pos_mat_id_area_pos_seq1; Type: SEQUENCE SET; Schema: public; Owner: pos
--

SELECT pg_catalog.setval('public.area_pos_mat_id_area_pos_seq1', 1, false);


--
-- Name: area_pos_mat_pkey1; Type: CONSTRAINT; Schema: public; Owner: pos
--

ALTER TABLE ONLY public.area_pos_mat
    ADD CONSTRAINT area_pos_mat_pkey1 PRIMARY KEY (id_area_pos);


--
-- PostgreSQL database dump complete
--

