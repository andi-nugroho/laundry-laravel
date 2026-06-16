--
-- PostgreSQL database dump
--


-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

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
-- Name: bookings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bookings (
    id bigint NOT NULL,
    booking_code character varying(255) NOT NULL,
    user_id bigint,
    customer_id bigint,
    service_id bigint NOT NULL,
    booking_date date NOT NULL,
    estimated_finish_date date,
    weight numeric(8,2),
    total_price numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    pickup_type character varying(255) DEFAULT 'antar_sendiri'::character varying NOT NULL,
    status character varying(255) DEFAULT 'booking_masuk'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bookings_pickup_type_check CHECK (((pickup_type)::text = ANY ((ARRAY['antar_sendiri'::character varying, 'pickup'::character varying])::text[]))),
    CONSTRAINT bookings_status_check CHECK (((status)::text = ANY ((ARRAY['booking_masuk'::character varying, 'diterima'::character varying, 'dicuci'::character varying, 'dikeringkan'::character varying, 'disetrika'::character varying, 'selesai'::character varying, 'diambil'::character varying, 'dibatalkan'::character varying])::text[])))
);


--
-- Name: bookings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bookings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bookings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bookings_id_seq OWNED BY public.bookings.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customers (
    id bigint NOT NULL,
    user_id bigint,
    name character varying(255) NOT NULL,
    phone character varying(255),
    address text,
    gender character varying(255),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT customers_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying])::text[])))
);


--
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customers_id_seq OWNED BY public.customers.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    booking_id bigint NOT NULL,
    payment_code character varying(255) NOT NULL,
    payment_date timestamp(0) without time zone NOT NULL,
    payment_method character varying(255) NOT NULL,
    amount_paid numeric(12,2) NOT NULL,
    total_bill numeric(12,2) NOT NULL,
    change_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    payment_status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    notes text,
    processed_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT payments_payment_method_check CHECK (((payment_method)::text = ANY ((ARRAY['cash'::character varying, 'transfer'::character varying, 'ewallet'::character varying])::text[]))),
    CONSTRAINT payments_payment_status_check CHECK (((payment_status)::text = ANY ((ARRAY['unpaid'::character varying, 'partial'::character varying, 'paid'::character varying])::text[])))
);


--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.services (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    price_per_kg numeric(10,2) NOT NULL,
    estimated_days integer DEFAULT 2 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: services_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.services_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.services_id_seq OWNED BY public.services.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'user'::character varying NOT NULL
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: bookings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings ALTER COLUMN id SET DEFAULT nextval('public.bookings_id_seq'::regclass);


--
-- Name: customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers ALTER COLUMN id SET DEFAULT nextval('public.customers_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: services id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services ALTER COLUMN id SET DEFAULT nextval('public.services_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: bookings; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.bookings (id, booking_code, user_id, customer_id, service_id, booking_date, estimated_finish_date, weight, total_price, pickup_type, status, notes, created_at, updated_at) FROM stdin;
2	LDY-2026-0002	\N	2	4	2026-05-26	2026-05-27	3.25	58500.00	pickup	diterima	Pickup sore hari.	2026-05-25 18:26:29	2026-05-25 18:26:29
3	LDY-2026-0003	\N	3	1	2026-05-25	2026-05-27	6.00	48000.00	antar_sendiri	dicuci	\N	2026-05-25 18:26:29	2026-05-25 18:26:29
4	LDY-2026-0004	\N	4	3	2026-05-24	2026-05-25	2.75	13750.00	pickup	selesai	Pisahkan pakaian warna terang dan gelap.	2026-05-25 18:26:29	2026-05-25 18:26:29
5	LDY-2026-0005	3	1	5	2026-05-26	2026-05-29	4.00	100000.00	antar_sendiri	selesai	\N	2026-05-26 10:39:06	2026-05-26 11:59:24
6	LDY-2026-0006	3	1	3	2026-05-26	2026-05-27	2.00	10000.00	pickup	diterima	\N	2026-05-26 11:56:34	2026-05-26 11:59:51
12	LDY-2026-0012	3	1	5	2026-05-30	2026-06-02	3.00	75000.00	antar_sendiri	dicuci	\N	2026-05-30 00:33:33	2026-05-30 00:35:54
11	LDY-2026-0011	4	5	5	2026-05-30	2026-06-02	2.00	50000.00	antar_sendiri	dicuci	\N	2026-05-30 00:28:01	2026-05-30 00:36:15
10	LDY-2026-0010	3	1	1	2026-05-30	2026-06-01	1.00	10000.00	antar_sendiri	selesai	\N	2026-05-30 00:22:29	2026-05-30 00:36:34
1	LDY-2026-0001	3	1	2	2026-05-26	2026-05-29	4.50	54000.00	antar_sendiri	diambil	Pakaian harian, parfum lembut.	2026-05-25 18:26:29	2026-05-30 00:53:58
7	LDY-2026-0007	3	1	6	2026-05-26	2026-05-30	0.90	13500.00	antar_sendiri	diambil	\N	2026-05-26 19:18:30	2026-05-30 00:53:58
9	LDY-2026-0009	4	5	4	2026-05-30	2026-05-31	5.00	100000.00	antar_sendiri	dicuci	\N	2026-05-30 00:01:20	2026-05-30 00:54:18
8	LDY-2026-0008	3	1	5	2026-05-26	2026-05-29	16.00	400000.00	pickup	selesai	\N	2026-05-26 20:29:45	2026-05-30 00:54:29
13	LDY-2026-0013	3	1	6	2026-06-14	2026-06-18	2.00	30000.00	pickup	dicuci	\N	2026-06-14 05:04:08	2026-06-14 05:16:51
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.customers (id, user_id, name, phone, address, gender, notes, created_at, updated_at) FROM stdin;
1	3	Andi Pratama	081234567890	Jl. Melati No. 12	male	Pelanggan reguler, preferensi parfum lembut.	2026-05-25 17:57:45	2026-05-25 17:57:45
2	\N	Siti Aminah	082112223333	Jl. Kenanga No. 5	female	Sering menggunakan layanan express.	2026-05-25 17:57:45	2026-05-25 17:57:45
3	\N	Budi Santoso	085677889900	Perumahan Harmoni Blok C3	male	\N	2026-05-25 17:57:45	2026-05-25 17:57:45
4	\N	Rina Lestari	\N	Jl. Anggrek No. 18	female	Minta pakaian dipisah warna terang dan gelap.	2026-05-25 17:57:45	2026-05-25 17:57:45
5	4	andreas	\N	\N	\N	Dibuat otomatis dari flow Pesan Laundry.	2026-05-30 00:01:20	2026-05-30 00:01:20
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_05_25_172151_add_role_to_users_table	2
5	2026_05_25_173756_create_services_table	3
6	2026_05_25_180000_create_customers_table	4
7	2026_05_25_181500_create_bookings_table	5
8	2026_05_25_183000_create_payments_table	6
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.payments (id, booking_id, payment_code, payment_date, payment_method, amount_paid, total_bill, change_amount, payment_status, notes, processed_by, created_at, updated_at) FROM stdin;
1	1	PAY-2026-0001	2026-05-26 09:00:00	cash	0.00	54000.00	-54000.00	unpaid	Belum ada pembayaran.	\N	2026-05-25 21:18:31	2026-05-25 21:18:31
2	2	PAY-2026-0002	2026-05-26 10:30:00	transfer	25000.00	58500.00	-33500.00	partial	Pembayaran DP.	2	2026-05-25 21:18:31	2026-05-25 21:18:31
3	3	PAY-2026-0003	2026-05-26 13:15:00	ewallet	48000.00	48000.00	0.00	paid	Pembayaran lunas via e-wallet.	2	2026-05-25 21:18:31	2026-05-25 21:18:31
4	4	PAY-2026-0004	2026-05-26 16:00:00	cash	23750.00	13750.00	10000.00	paid	Pembayaran tunai dengan kembalian.	1	2026-05-25 21:18:31	2026-05-25 21:18:31
5	5	PAY-2026-0005	2026-05-26 10:56:00	cash	100000.00	100000.00	0.00	paid	\N	1	2026-05-26 10:56:42	2026-05-26 10:56:42
6	6	PAY-2026-0006	2026-05-26 11:56:56	ewallet	10000.00	10000.00	0.00	paid	Via: QRIS	3	2026-05-26 11:56:34	2026-05-26 11:56:56
7	7	PAY-2026-0007	2026-05-26 19:18:30	cash	0.00	13500.00	0.00	unpaid	COD / Bayar di Tempat	\N	2026-05-26 19:18:30	2026-05-26 19:18:30
8	8	PAY-2026-0008	2026-05-26 20:29:46	ewallet	400000.00	400000.00	0.00	paid	Pembayaran customer via QRIS	\N	2026-05-26 20:29:46	2026-05-26 20:29:46
9	9	PAY-2026-0009	2026-05-30 00:01:23	ewallet	100000.00	100000.00	0.00	paid	Pembayaran customer via QRIS	\N	2026-05-30 00:01:23	2026-05-30 00:01:23
10	10	PAY-2026-0010	2026-05-30 00:22:30	ewallet	0.00	10000.00	0.00	unpaid	payment_channel=qris; QRIS mock payment	\N	2026-05-30 00:22:30	2026-05-30 00:22:30
11	11	PAY-2026-0011	2026-05-30 00:28:21	ewallet	50000.00	50000.00	0.00	paid	payment_channel=qris; Pembayaran customer dikonfirmasi via QRIS	4	2026-05-30 00:27:59	2026-05-30 00:28:21
12	12	PAY-2026-0012	2026-05-30 00:33:50	transfer	75000.00	75000.00	0.00	paid	payment_channel=transfer; Pembayaran customer dikonfirmasi via Transfer Bank BCA	3	2026-05-30 00:33:34	2026-05-30 00:33:50
13	13	PAY-2026-0013	2026-06-14 05:04:34	ewallet	30000.00	30000.00	0.00	paid	payment_channel=qris; Pembayaran customer dikonfirmasi via QRIS	3	2026-06-14 05:04:09	2026-06-14 05:04:34
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.services (id, name, description, price_per_kg, estimated_days, is_active, created_at, updated_at) FROM stdin;
2	Cuci Setrika	Layanan cuci lengkap dengan setrika rapi.	12000.00	3	t	2026-05-25 17:39:52	2026-05-25 17:39:52
5	Laundry Sepatu	Perawatan dan pencucian sepatu.	25000.00	3	t	2026-05-25 17:39:52	2026-05-25 17:39:52
6	Laundry Bedcover	Cuci bedcover, sprei, dan linen besar.	15000.00	4	t	2026-05-25 17:39:52	2026-05-25 17:39:52
1	Cuci Kering	Layanan cuci tanpa setrika, cocok untuk pakaian sehari-hari.	10000.00	2	t	2026-05-25 17:39:52	2026-05-26 19:25:02
3	Setrika Saja	Layanan setrika pakaian yang sudah dicuci sendiri.	7000.00	1	t	2026-05-25 17:39:52	2026-05-29 23:57:30
4	Laundry Express	Layanan cepat selesai dalam 1 hari.	20000.00	1	t	2026-05-25 17:39:52	2026-05-29 23:58:18
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
tPngCiPZ5RH8QPnMbxkVrdAuNZFEQZc62avUsaFa	\N	172.20.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHJnRk1SQVVydGJEZFlaV21EejVuQmlFdVNJNWNKWjJMNzJyN1JzMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO047fX0=	1781587346
yhQVqVxLUNMxj3RjIV1rjY4st34Qvu1Py9arXBgx	\N	172.20.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoidHVCWHhNbVExUUQyNFdOVksxTWh4VFpGUGhqQkpYelR6QXNvTnJqcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1781608514
U40KrYpLS1NH03m4XH1m08kn1guooxNTfNI5yz7B	\N	172.20.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVlZTjFhc2RXNXRaV3dCOVVSUVc5MjExM3RyYnhLZzdERmdTajN6cCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO047fX0=	1781608823
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
4	andreas	andreas@gmail.com	\N	$2y$12$Ph0odwHZsxO9F3r33hJMgOO/CoOyOWwWIXRa4hYWwibe3JnGhk2iq	\N	2026-05-30 00:00:42	2026-05-30 00:00:42	user
3	User	user@laundry.test	2026-05-25 21:18:30	$2y$12$MKldJ5CVuRTVsl9YpRBoheCjSilxGOrPv72K105TbEU5uIqcgigx.	x8LKb0E5YCoS93V7IyCyQr1HO1ZX92JlQABWF6JRe69zZXitBfKqK6vegIGE	2026-05-25 17:22:38	2026-05-25 21:18:30	user
2	Kasir	kasir@laundry.test	2026-05-25 21:18:30	$2y$12$yX8CO1OdFCieyAvXbHr6heDnfMlfIKY4SlduQQy4zbPL2s4vkTWLO	wpSAuFRR85aHcC3tcyIAv7c5wjALUQey71pfxt1F0rkwF0kT4Dl0aAoBTNIq	2026-05-25 17:22:38	2026-05-25 21:18:30	kasir
1	Admin	admin@laundry.test	2026-05-25 21:18:29	$2y$12$qaosvFpLQO662Nh7sMbPfOgzy3LHqg7wZhHcJ9bcSKbWgXTK58x9C	UmxREZMCdhfDibQTLLlEauaK3GfNPKOyRLATuDc6gYMhdoVH6PtTQul4DXcQ	2026-05-25 17:22:38	2026-05-25 21:18:30	admin
\.


--
-- Name: bookings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.bookings_id_seq', 13, true);


--
-- Name: customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.customers_id_seq', 5, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 8, true);


--
-- Name: payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.payments_id_seq', 13, true);


--
-- Name: services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.services_id_seq', 6, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- Name: bookings bookings_booking_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_booking_code_unique UNIQUE (booking_code);


--
-- Name: bookings bookings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_payment_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_payment_code_unique UNIQUE (payment_code);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: bookings bookings_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE SET NULL;


--
-- Name: bookings bookings_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id) ON DELETE CASCADE;


--
-- Name: bookings bookings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: customers customers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: payments payments_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.bookings(id) ON DELETE CASCADE;


--
-- Name: payments payments_processed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--


