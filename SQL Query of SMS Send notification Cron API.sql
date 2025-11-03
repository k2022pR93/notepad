 $sqlQuery = "SELECT iat.application_type,
    ig.id,
    ig.reporter_name,
    ig.contact_number,
    ig.description,
    ig.area,
    it.incidence_type,
    ic.channel_name,
    ig.created_date,
    to_char(ig.created_date, 'Month'::text) AS created_month,
    date_part('year'::text, ig.created_date) AS created_year,
    to_char(ig.created_date, 'Mon YY'::text) AS created_month_year,
    dm.designation_name,
    e.dist_cd,
    max(igd.forward_to_user) AS max,
        CASE
            WHEN ig.status = ANY (ARRAY[0, 1]) THEN NULL::text
            ELSE concat(em.emp_firstname, ' ', em.emp_middlename, ' ', em.emp_lastname)
        END AS eo_name,
    max(igd.created_date)::date AS assigned_date,
    to_char(max(igd.created_date), 'Month'::text) AS last_update_month,
    date_part('year'::text, max(igd.created_date)) AS last_update_year,
    to_char(max(igd.created_date), 'Mon YY'::text) AS last_update_month_year,
    cu.unit_name AS created_unit,
    pu.unit_name AS pending_unit,
    ig.status AS ig_status,
        CASE
            WHEN ig.status = 0 THEN 0
            WHEN ig.status = 1 THEN 1
            WHEN ig.status = 2 THEN 2
            ELSE 3
        END AS status,
        CASE
            WHEN ig.status = 0 THEN 'Open'::text
            WHEN ig.status = 1 THEN 'Forwarded'::text
            WHEN ig.status = 2 THEN 'Allocated'::text
            WHEN ig.status = 5 THEN 'Work in Progress'::text
            ELSE 'Close'::text
        END AS status_name,
        CASE
            WHEN cu.unit_name::text = pu.unit_name::text THEN 'PS'::text
            WHEN cu.unit_name::text = 'APPLICATION BRANCH'::text THEN 'CP OFFICE'::text
            WHEN cu.unit_name::text ~~ '%DCP%'::text THEN 'DCP OFFICE'::text
            WHEN cu.unit_name::text ~~ '%ACP%'::text THEN 'ACP OFFICE'::text
            ELSE 'Other PS'::text
        END AS application_source,
        CASE
            WHEN ig.status = ANY (ARRAY[2, 5]) THEN date_part('day'::text, CURRENT_DATE::timestamp without time zone - max(igd.created_date))
            ELSE 0::double precision
        END AS days_since_allocated,
        CASE
            WHEN ig.status = ANY (ARRAY[2, 5]) THEN
            CASE
                WHEN date_part('day'::text, CURRENT_DATE::timestamp without time zone - max(igd.created_date)) <= 15::double precision THEN '15 DAYS'::text
                WHEN date_part('day'::text, CURRENT_DATE::timestamp without time zone - max(igd.created_date)) <= 30::double precision THEN '1 MONTH'::text
                WHEN date_part('day'::text, CURRENT_DATE::timestamp without time zone - max(igd.created_date)) <= 90::double precision THEN '3 MONTH'::text
                ELSE 'MORE THAN 3 MONTH'::text
            END
            ELSE NULL::text
        END AS pending_month,
    CURRENT_DATE::timestamp without time zone - max(igd.created_date) AS pending_since_in_days,
        CASE
            WHEN ig.status = ANY (ARRAY[0, 1]) THEN 'Open'::text
            WHEN ig.status = ANY (ARRAY[2, 5]) THEN 'Work in Progress'::text
            ELSE 'Close'::text
        END AS status_category,
        CASE
            WHEN pu.id = ANY (ARRAY[1984186::numeric, 199072350::numeric, 1984141::numeric, 1984103::numeric, 1984196::numeric, 1990727608::numeric, 1984131::numeric, 1984151::numeric, 1984195::numeric, 1990727607::numeric, 1984156::numeric, 1990728357::numeric, 1984115::numeric, 1984157::numeric]) THEN 'ZONE 1'::text
            WHEN pu.id = ANY (ARRAY[1984153::numeric, 1984148::numeric, 1990727621::numeric, 1990724205::numeric, 1990727609::numeric, 1984154::numeric, 1984198::numeric, 1984197::numeric, 1990728379::numeric, 1984150::numeric, 1984152::numeric, 1984149::numeric, 1984147::numeric, 1990728386::numeric, 1990724164::numeric]) THEN 'ZONE 2'::text
            WHEN pu.id = ANY (ARRAY[1990727626::numeric, 1990727641::numeric, 1990727639::numeric, 1990727630::numeric, 1990727635::numeric, 1990727629::numeric, 1990727628::numeric, 1990727624::numeric, 1990727637::numeric, 1990727642::numeric, 1990727623::numeric, 1990727627::numeric, 1990727633::numeric, 1990727631::numeric, 1990727640::numeric, 1990728257::numeric, 1990727625::numeric, 1990728212::numeric, 199072363::numeric]) THEN 'TRAFFIC'::text
            WHEN pu.id = ANY (ARRAY[1990727646::numeric, 1990727645::numeric, 1984190::numeric, 1990728224::numeric, 1990728204::numeric, 1984176::numeric, 1984189::numeric, 19841100::numeric]) THEN 'SPECIAL BR.'::text
            WHEN pu.id = ANY (ARRAY[1990728202::numeric, 1990727685::numeric, 1990727689::numeric, 1990727675::numeric, 19841104::numeric, 1990727677::numeric, 1990728210::numeric, 1990727674::numeric, 1990727683::numeric, 1984194::numeric, 1990727688::numeric, 1984158::numeric, 1984185::numeric, 19841102::numeric, 199072362::numeric, 1990728376::numeric, 1990727687::numeric, 1990727690::numeric, 19841111::numeric, 1990728211::numeric, 1990728377::numeric, 1990727672::numeric, 1990727673::numeric]) THEN 'CRIME Br.'::text
            ELSE 'HQ'::text
        END AS pending_unit_category,
    ig.attachment,
    ig.emergency_id,
        CASE
            WHEN ig.emergency_id = 1 THEN 'High'::text
            WHEN ig.emergency_id = 2 THEN 'Medium'::text
            ELSE 'LOW'::text
        END AS emergency_name,
    max(igd.id) AS max_grievance_dtl_id
   FROM igrms_master ig
     JOIN incidence_type_master it ON it.id = ig.incidence_type_id
     JOIN emp_master e ON e.id = ig.created_by
     LEFT JOIN unit_master cu ON cu.id = ig.login_sub_unit_cd
     JOIN igrms_channels ic ON ic.id = ig.grievence_id
     JOIN igrms_grievence_dtl igd ON igd.grievance_master_id = ig.id
     LEFT JOIN igmrs_application_type iat ON iat.id = ig.application_from
     JOIN unit_master pu ON pu.id = ig.forward_to_subunit
     JOIN emp_master em ON em.id = igd.forward_to_user
     JOIN designation_master dm ON dm.desig_master_id::numeric = em.designation_master_id
  WHERE igd.active_status = 1 AND igd.forward_to_unit = 19841::numeric
  GROUP BY iat.application_type, ig.id, ig.reporter_name, ig.contact_number, ig.description, ig.area, it.incidence_type, ic.channel_name, ig.created_date, igd.forward_to_user, em.emp_firstname, em.emp_middlename, em.emp_lastname, cu.unit_name, pu.unit_name, pu.id, ig.status, ig.attachment, ig.emergency_id, dm.designation_name,e.dist_cd";
       