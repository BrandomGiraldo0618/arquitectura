--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 12.2

-- Started on 2021-05-05 10:25:13

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

SET default_table_access_method = heap;

--
-- TOC entry 203 (class 1259 OID 103670)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 103668)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 2915 (class 0 OID 0)
-- Dependencies: 202
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 211 (class 1259 OID 103720)
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 103731)
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 103691)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 103700)
-- Name: permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 103698)
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissions_id_seq OWNER TO postgres;

--
-- TOC entry 2916 (class 0 OID 0)
-- Dependencies: 207
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- TOC entry 215 (class 1259 OID 103759)
-- Name: posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.posts (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    content text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint NOT NULL
);


ALTER TABLE public.posts OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 103757)
-- Name: posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.posts_id_seq OWNER TO postgres;

--
-- TOC entry 2917 (class 0 OID 0)
-- Dependencies: 214
-- Name: posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


--
-- TOC entry 213 (class 1259 OID 103742)
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 103711)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 103709)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO postgres;

--
-- TOC entry 2918 (class 0 OID 0)
-- Dependencies: 209
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 205 (class 1259 OID 103678)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    image character varying(100),
    status smallint DEFAULT '0'::smallint NOT NULL,
    language character(3) DEFAULT 'es'::bpchar NOT NULL,
    last_date_connection timestamp(0) without time zone,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 103676)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 2919 (class 0 OID 0)
-- Dependencies: 204
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 216 (class 1259 OID 103774)
-- Name: v_users; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_users AS
 SELECT users.id,
    users.name,
    users.email,
    users.password,
    users.image,
    users.status,
    users.language,
    users.last_date_connection,
    users.remember_token,
    users.created_at,
    users.updated_at,
    COALESCE(roles.id, (0)::bigint) AS role_id,
    COALESCE(roles.name, ''::character varying) AS role_name,
        CASE
            WHEN (users.status = 1) THEN 'Activo'::text
            ELSE 'Inactivo'::text
        END AS status_name
   FROM ((public.users
     LEFT JOIN public.model_has_roles ON ((users.id = model_has_roles.model_id)))
     LEFT JOIN public.roles ON ((roles.id = model_has_roles.role_id)));


ALTER TABLE public.v_users OWNER TO postgres;

--
-- TOC entry 2736 (class 2604 OID 103673)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 2740 (class 2604 OID 103703)
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- TOC entry 2742 (class 2604 OID 103762)
-- Name: posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


--
-- TOC entry 2741 (class 2604 OID 103714)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 2737 (class 2604 OID 103681)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 2897 (class 0 OID 103670)
-- Dependencies: 203
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2020_09_01_223317_create_permission_tables	1
4	2021_02_23_014312_create_posts_table	1
\.


--
-- TOC entry 2905 (class 0 OID 103720)
-- Dependencies: 211
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- TOC entry 2906 (class 0 OID 103731)
-- Dependencies: 212
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
2	App\\User	1
2	App\\User	2
1	App\\User	3
2	App\\User	4
2	App\\User	5
2	App\\User	6
2	App\\User	7
1	App\\User	8
1	App\\User	9
1	App\\User	10
1	App\\User	11
\.


--
-- TOC entry 2900 (class 0 OID 103691)
-- Dependencies: 206
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 2902 (class 0 OID 103700)
-- Dependencies: 208
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	index_permissions	api	2021-02-26 20:41:46	2021-02-26 20:41:46
2	create_permissions	api	2021-02-26 20:41:46	2021-02-26 20:41:46
3	update_permissions	api	2021-02-26 20:41:46	2021-02-26 20:41:46
4	delete_permissions	api	2021-02-26 20:41:46	2021-02-26 20:41:46
5	index_users	api	2021-02-26 20:41:46	2021-02-26 20:41:46
6	create_users	api	2021-02-26 20:41:46	2021-02-26 20:41:46
7	update_users	api	2021-02-26 20:41:46	2021-02-26 20:41:46
8	delete_users	api	2021-02-26 20:41:46	2021-02-26 20:41:46
9	index_posts	api	2021-02-26 20:41:46	2021-02-26 20:41:46
10	create_posts	api	2021-02-26 20:41:46	2021-02-26 20:41:46
11	update_posts	api	2021-02-26 20:41:46	2021-02-26 20:41:46
12	delete_posts	api	2021-02-26 20:41:46	2021-02-26 20:41:46
\.


--
-- TOC entry 2909 (class 0 OID 103759)
-- Dependencies: 215
-- Data for Name: posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.posts (id, name, content, created_at, updated_at, user_id) FROM stdin;
\.


--
-- TOC entry 2907 (class 0 OID 103742)
-- Dependencies: 213
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
6	1
7	1
8	1
9	1
10	1
11	1
12	1
9	2
10	2
11	2
12	2
5	2
\.


--
-- TOC entry 2904 (class 0 OID 103711)
-- Dependencies: 210
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	admin	api	2021-02-26 20:41:46	2021-02-26 20:41:46
2	user	api	2021-02-26 20:41:47	2021-02-26 20:41:47
\.


--
-- TOC entry 2899 (class 0 OID 103678)
-- Dependencies: 205
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, password, image, status, language, last_date_connection, remember_token, created_at, updated_at) FROM stdin;
1	Karen Bartell	rigoberto.barrows@example.net	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	RAgdke5eGr	2021-02-26 20:41:48	2021-02-26 20:41:48
2	Mrs. Mattie Kemmer Jr.	hmurazik@example.org	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	ehmVvGB3gZ	2021-02-26 20:41:48	2021-02-26 20:41:48
4	Meredith Vandervort	donald28@example.org	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	elud8Ep5zU	2021-02-26 20:41:48	2021-02-26 20:41:48
5	Santino O'Conner	pfeest@example.org	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	xZZerPJSr1	2021-02-26 20:41:48	2021-02-26 20:41:48
6	Prof. Carmella Schaefer PhD	kmcdermott@example.net	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	uVjE0ViXcO	2021-02-26 20:41:48	2021-02-26 20:41:48
7	Julia Kuhic	felicita56@example.org	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	2VvsaQv8V3	2021-02-26 20:41:48	2021-02-26 20:41:48
8	Durward Gaylord	beverly71@example.com	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	FzJG3z25w1	2021-02-26 20:41:48	2021-02-26 20:41:48
9	Prof. Bobby Lehner	elmira.lueilwitz@example.com	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	EfRM66oLBO	2021-02-26 20:41:48	2021-02-26 20:41:48
10	Antwan Schiller	jhoppe@example.net	$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy	\N	1	es 	\N	4oDrIz6xDJ	2021-02-26 20:41:48	2021-02-26 20:41:48
11  
\.


--
-- TOC entry 2920 (class 0 OID 0)
-- Dependencies: 202
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 4, true);


--
-- TOC entry 2921 (class 0 OID 0)
-- Dependencies: 207
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 12, true);


--
-- TOC entry 2922 (class 0 OID 0)
-- Dependencies: 214
-- Name: posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.posts_id_seq', 1, false);


--
-- TOC entry 2923 (class 0 OID 0)
-- Dependencies: 209
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 3, true);


--
-- TOC entry 2924 (class 0 OID 0)
-- Dependencies: 204
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 11, true);


--
-- TOC entry 2744 (class 2606 OID 103675)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 2756 (class 2606 OID 103730)
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- TOC entry 2759 (class 2606 OID 103741)
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- TOC entry 2751 (class 2606 OID 103708)
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 2763 (class 2606 OID 103767)
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- TOC entry 2761 (class 2606 OID 103756)
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- TOC entry 2753 (class 2606 OID 103719)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 2746 (class 2606 OID 103690)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 2748 (class 2606 OID 103688)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2754 (class 1259 OID 103723)
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- TOC entry 2757 (class 1259 OID 103734)
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- TOC entry 2749 (class 1259 OID 103697)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- TOC entry 2764 (class 2606 OID 103724)
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 2765 (class 2606 OID 103735)
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- TOC entry 2768 (class 2606 OID 103768)
-- Name: posts posts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- TOC entry 2766 (class 2606 OID 103745)
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 2767 (class 2606 OID 103750)
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


-- Completed on 2021-05-05 10:25:13

--
-- PostgreSQL database dump complete
--

