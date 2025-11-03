
 SELECT DISTINCT ON (a.id) 
    a.id,
    CONCAT(a.accused_first_name, ' ', a.accused_middle_name, ' ', a.accused_last_name) AS name,
    um.id AS sub_unit_id,
    a.accused_photo AS file_name, 
    STRING_AGG(DISTINCT acd.fir_no, ', ') AS fir_numbers,  
    STRING_AGG(DISTINCT al.name, ', ') AS aliases,        
    apd.age,
    apd.mobile_no AS mobile, 
    om.offense_type AS crime_type,
    a.accused_first_name AS "FIRST_NAME",
    a.accused_middle_name AS "MIDDLE_NAME",
    a.accused_last_name AS "LAST_NAME"
FROM 
    accused_details AS a
LEFT JOIN 
    city_master AS cm ON a.login_unit = cm.id
LEFT JOIN 
    unit_master AS um ON a.login_sub_unit = um.id
LEFT JOIN 
    accused_crime_dtls AS acd ON a.id = acd.fk_accused_id
LEFT JOIN 
    offense_master AS om ON acd.type_of_offense = CAST(om.id AS character varying)
LEFT JOIN 
    accused_personal_details AS apd ON a.id = apd.fk_accused_id AND apd.status = 1
LEFT JOIN 
    accused_address_details AS aad ON a.id = aad.fk_accused_id AND aad.status = 1
LEFT JOIN 
    accused_alias_name AS al ON al.fk_accused_id = a.id
LEFT JOIN 
    state_master AS sm ON aad.add_state = sm.id
LEFT JOIN 
    major_head_master AS mj ON mj.major_id = acd.major_head_id
LEFT JOIN 
    minor_head_master AS mn ON mn.id = acd.minor_head_id

    WHERE a.status='1' and (a.login_sub_unit =1984141 or acd.police_station=1984141 )  and aad.residential_sub_unit = 1984141  -- Add the search condition here if present
    GROUP BY 
    a.id, 
    um.id, 
    apd.age, 
    apd.mobile_no, 
    om.offense_type, 
    a.accused_first_name, 
    a.accused_middle_name, 
    a.accused_last_name, 
    a.accused_photo
ORDER BY 
    a.id




$created_by=$_SESSION['login_user_id'];
$model_dtl->created_by=$created_by;
$model->login_unit_cd = $_SESSION['login_unit_id'];
$model->login_sub_unit_cd= $_SESSION['login_sub_unit_id'];

$record->created_by=$session['login_user_id'];
$record->login_unit_cd = $session['login_unit_id'];
$record->login_sub_unit_cd= $session['login_subunit_id'];

$session = Yii::$app->session;
$model->updated_by=$session->get('login_user_id');