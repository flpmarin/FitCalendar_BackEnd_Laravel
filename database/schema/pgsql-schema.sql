--
-- PostgreSQL database dump
--

-- Dumped from database version 15.12 (Debian 15.12-1.pgdg120+1)
-- Dumped by pg_dump version 17.4 (Ubuntu 17.4-1.pgdg24.04+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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
-- Name: availability_slots; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.availability_slots (
    id bigint NOT NULL,
    coach_id bigint NOT NULL,
    sport_id bigint,
    weekday smallint NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    is_online boolean DEFAULT false NOT NULL,
    location character varying(255),
    capacity smallint DEFAULT '1'::smallint NOT NULL,
    "unique" character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: availability_slots_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.availability_slots_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: availability_slots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.availability_slots_id_seq OWNED BY public.availability_slots.id;


--
-- Name: bookings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bookings (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    coach_id bigint,
    availability_slot_id bigint,
    specific_availability_id bigint,
    class_id bigint,
    type character varying(255) NOT NULL,
    session_at timestamp(0) with time zone NOT NULL,
    session_duration_minutes smallint NOT NULL,
    status character varying(255) DEFAULT 'Pending'::character varying NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    platform_fee numeric(10,2) NOT NULL,
    currency character varying(255) DEFAULT 'EUR'::character varying NOT NULL,
    cancelled_at timestamp(0) with time zone,
    cancelled_reason character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT bookings_status_check CHECK (((status)::text = ANY ((ARRAY['Pending'::character varying, 'Confirmed'::character varying, 'Cancelled'::character varying, 'Completed'::character varying, 'Rejected'::character varying])::text[]))),
    CONSTRAINT bookings_type_check CHECK (((type)::text = ANY ((ARRAY['Personal'::character varying, 'Group'::character varying])::text[])))
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
-- Name: classes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.classes (
    id bigint NOT NULL,
    coach_id bigint NOT NULL,
    sport_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    starts_at timestamp(0) with time zone NOT NULL,
    duration_minutes smallint NOT NULL,
    location_detail character varying(255),
    is_online boolean DEFAULT false NOT NULL,
    price_per_person numeric(10,2) NOT NULL,
    max_capacity smallint NOT NULL,
    min_required smallint DEFAULT '1'::smallint NOT NULL,
    enrollment_deadline timestamp(0) with time zone,
    status character varying(255) DEFAULT 'Scheduled'::character varying NOT NULL,
    cancelled_at timestamp(0) with time zone,
    cancelled_reason character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT classes_status_check CHECK (((status)::text = ANY ((ARRAY['Scheduled'::character varying, 'ReadyToConfirm'::character varying, 'Confirmed'::character varying, 'Cancelled'::character varying, 'Completed'::character varying])::text[])))
);


--
-- Name: classes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.classes_id_seq OWNED BY public.classes.id;


--
-- Name: coach_sports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.coach_sports (
    id bigint NOT NULL,
    coach_id bigint NOT NULL,
    sport_id bigint NOT NULL,
    specific_price numeric(10,2),
    specific_location character varying(255),
    session_duration_minutes smallint DEFAULT '60'::smallint NOT NULL,
    "primary" character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: coach_sports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.coach_sports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: coach_sports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.coach_sports_id_seq OWNED BY public.coach_sports.id;


--
-- Name: coaches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.coaches (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    description text,
    city character varying(255),
    country character varying(255),
    coach_type character varying(255) DEFAULT 'Individual'::character varying NOT NULL,
    verified boolean DEFAULT false NOT NULL,
    organization_id bigint,
    payment_info character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT coaches_coach_type_check CHECK (((coach_type)::text = ANY ((ARRAY['Individual'::character varying, 'Club'::character varying])::text[])))
);


--
-- Name: coaches_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.coaches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: coaches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.coaches_id_seq OWNED BY public.coaches.id;


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
-- Name: group_interests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.group_interests (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    sport_id bigint NOT NULL,
    desired_time character varying(255),
    location_pref character varying(255),
    is_online_pref boolean,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: group_interests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.group_interests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: group_interests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.group_interests_id_seq OWNED BY public.group_interests.id;


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
-- Name: organizations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.organizations (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    owner_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: organizations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.organizations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: organizations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.organizations_id_seq OWNED BY public.organizations.id;


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
    stripe_payment_intent_id character varying(255) NOT NULL,
    amount numeric(10,2) NOT NULL,
    currency character varying(255) DEFAULT 'EUR'::character varying NOT NULL,
    status character varying(255) DEFAULT 'Pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT payments_status_check CHECK (((status)::text = ANY ((ARRAY['Pending'::character varying, 'Completed'::character varying, 'Refunded'::character varying, 'Failed'::character varying])::text[])))
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
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: reviews; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.reviews (
    id bigint NOT NULL,
    coach_id bigint NOT NULL,
    student_id bigint NOT NULL,
    booking_id bigint NOT NULL,
    rating smallint NOT NULL,
    comment text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: reviews_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.reviews_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: reviews_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;


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
-- Name: specific_availabilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.specific_availabilities (
    id bigint NOT NULL,
    coach_id bigint NOT NULL,
    sport_id bigint,
    start_at timestamp(0) with time zone NOT NULL,
    end_at timestamp(0) with time zone NOT NULL,
    availability_type character varying(255) DEFAULT 'Available'::character varying NOT NULL,
    capacity smallint,
    is_online boolean,
    location character varying(255),
    reason character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT specific_availabilities_availability_type_check CHECK (((availability_type)::text = ANY ((ARRAY['Available'::character varying, 'Blocked'::character varying])::text[])))
);


--
-- Name: specific_availabilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.specific_availabilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: specific_availabilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.specific_availabilities_id_seq OWNED BY public.specific_availabilities.id;


--
-- Name: sports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sports (
    id bigint NOT NULL,
    name_es character varying(255) NOT NULL,
    name_en character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: sports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sports_id_seq OWNED BY public.sports.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) with time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) NOT NULL,
    language character varying(10) DEFAULT 'es'::character varying NOT NULL,
    profile_picture_url character varying(255),
    stripe_customer_id character varying(255),
    stripe_account_id character varying(255),
    deleted_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['Student'::character varying, 'Coach'::character varying, 'Admin'::character varying])::text[])))
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
-- Name: availability_slots id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.availability_slots ALTER COLUMN id SET DEFAULT nextval('public.availability_slots_id_seq'::regclass);


--
-- Name: bookings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings ALTER COLUMN id SET DEFAULT nextval('public.bookings_id_seq'::regclass);


--
-- Name: classes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes ALTER COLUMN id SET DEFAULT nextval('public.classes_id_seq'::regclass);


--
-- Name: coach_sports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coach_sports ALTER COLUMN id SET DEFAULT nextval('public.coach_sports_id_seq'::regclass);


--
-- Name: coaches id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coaches ALTER COLUMN id SET DEFAULT nextval('public.coaches_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: group_interests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.group_interests ALTER COLUMN id SET DEFAULT nextval('public.group_interests_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: organizations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations ALTER COLUMN id SET DEFAULT nextval('public.organizations_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: reviews id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);


--
-- Name: specific_availabilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specific_availabilities ALTER COLUMN id SET DEFAULT nextval('public.specific_availabilities_id_seq'::regclass);


--
-- Name: sports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sports ALTER COLUMN id SET DEFAULT nextval('public.sports_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: availability_slots availability_slots_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.availability_slots
    ADD CONSTRAINT availability_slots_pkey PRIMARY KEY (id);


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
-- Name: classes classes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_pkey PRIMARY KEY (id);


--
-- Name: coach_sports coach_sports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coach_sports
    ADD CONSTRAINT coach_sports_pkey PRIMARY KEY (id);


--
-- Name: coaches coaches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_pkey PRIMARY KEY (id);


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
-- Name: group_interests group_interests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.group_interests
    ADD CONSTRAINT group_interests_pkey PRIMARY KEY (id);


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
-- Name: organizations organizations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations
    ADD CONSTRAINT organizations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: payments payments_stripe_payment_intent_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_stripe_payment_intent_id_unique UNIQUE (stripe_payment_intent_id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: specific_availabilities specific_availabilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specific_availabilities
    ADD CONSTRAINT specific_availabilities_pkey PRIMARY KEY (id);


--
-- Name: sports sports_name_en_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sports
    ADD CONSTRAINT sports_name_en_unique UNIQUE (name_en);


--
-- Name: sports sports_name_es_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sports
    ADD CONSTRAINT sports_name_es_unique UNIQUE (name_es);


--
-- Name: sports sports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sports
    ADD CONSTRAINT sports_pkey PRIMARY KEY (id);


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
-- Name: users users_stripe_account_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_stripe_account_id_unique UNIQUE (stripe_account_id);


--
-- Name: users users_stripe_customer_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_stripe_customer_id_unique UNIQUE (stripe_customer_id);


--
-- Name: availability_slots_weekday_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX availability_slots_weekday_index ON public.availability_slots USING btree (weekday);


--
-- Name: bookings_session_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bookings_session_at_index ON public.bookings USING btree (session_at);


--
-- Name: classes_starts_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX classes_starts_at_index ON public.classes USING btree (starts_at);


--
-- Name: coaches_city_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX coaches_city_index ON public.coaches USING btree (city);


--
-- Name: coaches_country_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX coaches_country_index ON public.coaches USING btree (country);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: availability_slots availability_slots_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.availability_slots
    ADD CONSTRAINT availability_slots_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: availability_slots availability_slots_sport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.availability_slots
    ADD CONSTRAINT availability_slots_sport_id_foreign FOREIGN KEY (sport_id) REFERENCES public.sports(id);


--
-- Name: bookings bookings_availability_slot_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_availability_slot_id_foreign FOREIGN KEY (availability_slot_id) REFERENCES public.availability_slots(id);


--
-- Name: bookings bookings_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.classes(id);


--
-- Name: bookings bookings_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: bookings bookings_specific_availability_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_specific_availability_id_foreign FOREIGN KEY (specific_availability_id) REFERENCES public.specific_availabilities(id);


--
-- Name: bookings bookings_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookings
    ADD CONSTRAINT bookings_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.users(id);


--
-- Name: classes classes_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: classes classes_sport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_sport_id_foreign FOREIGN KEY (sport_id) REFERENCES public.sports(id);


--
-- Name: coach_sports coach_sports_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coach_sports
    ADD CONSTRAINT coach_sports_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: coach_sports coach_sports_sport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coach_sports
    ADD CONSTRAINT coach_sports_sport_id_foreign FOREIGN KEY (sport_id) REFERENCES public.sports(id);


--
-- Name: coaches coaches_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.organizations(id);


--
-- Name: coaches coaches_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.coaches
    ADD CONSTRAINT coaches_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: group_interests group_interests_sport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.group_interests
    ADD CONSTRAINT group_interests_sport_id_foreign FOREIGN KEY (sport_id) REFERENCES public.sports(id);


--
-- Name: group_interests group_interests_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.group_interests
    ADD CONSTRAINT group_interests_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.users(id);


--
-- Name: organizations organizations_owner_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations
    ADD CONSTRAINT organizations_owner_id_foreign FOREIGN KEY (owner_id) REFERENCES public.users(id);


--
-- Name: payments payments_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.bookings(id);


--
-- Name: reviews reviews_booking_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_booking_id_foreign FOREIGN KEY (booking_id) REFERENCES public.bookings(id);


--
-- Name: reviews reviews_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: reviews reviews_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.users(id);


--
-- Name: specific_availabilities specific_availabilities_coach_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specific_availabilities
    ADD CONSTRAINT specific_availabilities_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES public.coaches(id);


--
-- Name: specific_availabilities specific_availabilities_sport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specific_availabilities
    ADD CONSTRAINT specific_availabilities_sport_id_foreign FOREIGN KEY (sport_id) REFERENCES public.sports(id);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 15.12 (Debian 15.12-1.pgdg120+1)
-- Dumped by pg_dump version 17.4 (Ubuntu 17.4-1.pgdg24.04+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_05_04_215945_create_personal_access_tokens_table	1
5	2025_05_10_232324_create_organizations_table	1
6	2025_05_10_232325_create_sports_table	1
7	2025_05_10_232326_create_coaches_table	1
8	2025_05_10_232327_create_coach_sports_table	1
9	2025_05_10_232328_create_availability_slots_table	1
10	2025_05_10_232329_create_specific_availabilities_table	1
11	2025_05_10_232330_create_classes_table	1
12	2025_05_10_232331_create_bookings_table	1
13	2025_05_10_232332_create_payments_table	1
14	2025_05_10_232333_create_reviews_table	1
15	2025_05_10_232334_create_group_interests_table	1
16	2025_05_10_232651_update_users_table_with_new_fields	1
17	2025_05_11_004143_update_email_verified_at_column_type	2
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 17, true);


--
-- PostgreSQL database dump complete
--

