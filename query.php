$connection = Yii::$app->getDb();
	
	
	 
	
//echo $from_date;die;
	// echo "<pre>";print_r($inventory );die;
	if($inventory == 0){
		
			$wuery = "
				SELECT 
				    ed.id AS evidence_id,
				    ed.io_ps AS subunit_id,
				    um.unit_name,
				    ew.warehouse_name,
				    -- STATUS LOGIC (PostgreSQL Safe)
				    CASE 
				        WHEN wt.type IS NULL OR wt.type = '' THEN 'Pending Inward'
				        ELSE wt.type
				    END AS status,
				 
				    eet.name AS evidence_type,
				    wt.warehouse_id,
				    ed.case_number AS cr_no,
				    ed.other_evidence_type,
				    ed.evidence_description,
				    wt.creation_dt,
				    ed.created_date AS evidence_creation_dt,
				    wt.to_name,
				    wt.to_designation,
				    dig.designation_name,
				    wt.to_mobile_number
				FROM evidence_details AS ed
				 
				-- Warehouse transaction: LEFT JOIN because some evidence may not be in warehouse yet
				LEFT JOIN ems_warehouse_evidence_transaction AS wt 
				    ON ed.id = wt.evidence_id 
				    AND wt.status = 1
				 
				-- Warehouse Master
				LEFT JOIN ems_warehouse ew 
				    ON ew.id = wt.warehouse_id
				 
				-- Unit / Police Station (IO_PS)
				LEFT JOIN unit_master AS um 
				    ON ed.io_ps = um.id
				 
				-- Evidence Type
				LEFT JOIN ems_evidence_types AS eet 
				    ON eet.id = ed.evidence_type
				 
				-- Designation Master (safe numeric-only validation)
				LEFT JOIN designation_master dig
				    ON dig.desig_master_id = (
				        CASE 
				            WHEN wt.to_designation ~ '^[0-9]+$' 
				            THEN CAST(wt.to_designation AS INTEGER)
				            ELSE NULL
				        END
				    )
				 
				WHERE ed.status = 1
				  AND (
				        -- If user selected Inward/Outward
				        wt.type = '$status'
				        -- If user wants Pending Inward
				        OR ('$status' = 'Pending Inward' AND (wt.type IS NULL OR wt.type = ''))
				      )
				  AND CAST(wt.creation_dt AS DATE) BETWEEN '$from_date' AND '$to_date'
				";
		
		$command = $connection->createCommand($wuery);
		$data = $command->queryAll();
		// echo "<pre>";print_r($data);die;
		
		$connection->close();
        }
        
	if($inventory == 1){
	
			$wuery = "
				SELECT 
				    wt.id,
				    ed.cr_no,
				    ed.incident_type,
				    wt.document_id,
				    wt.case_id,
				    wt.name,
				    wt.designation,
				    wt.mobile_number,
				    wt.unit_id,
				    ed.police_station,
				    um.unit_name,
				    wt.type,
				    wt.warehouse_id,
				    ew.warehouse_name,
				    wt.creation_dt,
				 
				    -- STATUS LOGIC (PostgreSQL)
				    CASE 
				        WHEN wt.type IS NULL OR wt.type = '' THEN 'Pending Inward'
				        ELSE wt.type
				    END AS status
				 
				FROM ems_document_data AS ed
				 
				-- Warehouse transactions (LEFT JOIN so Pending Inward appears)
				LEFT JOIN ems_warehouse_document_transaction AS wt
				    ON ed.id = wt.document_id
				    AND wt.status = 1
				 
				-- Warehouse master
				LEFT JOIN ems_warehouse ew
				    ON ew.id = wt.warehouse_id
				 
				-- Police station / unit name
				LEFT JOIN unit_master AS um
				    ON ed.police_station = um.id
				 
				WHERE 
				    ed.status = 1
				    AND ed.incident_type != 'APPLICATION'
				    AND (
				            -- Inward / Outward selection
				            wt.type = '$status'
				 
				            -- Pending Inward selection
				            OR ('$status' = 'Pending Inward' AND (wt.type IS NULL OR wt.type = ''))
				        )
				    AND CAST(wt.creation_dt AS DATE) BETWEEN '$from_date' AND '$to_date'
				";
		
		$command = $connection->createCommand($wuery);
		$data = $command->queryAll();
		
		// echo "<pre>";print_r($data);die;
		
		$connection->close();
        }
        if($inventory == 2){
	
			$wuery = "
				SELECT 
				    ed.id,
				    ed.application_no,
				    ed.application_type,
				    ed.application_id,
				    ed.applicant_name,
				    ed.io_name,
				    ed.non_applicant_name,
				    ed.police_station,
				    um.unit_name,
				    wt.warehouse_id,
				    ew.warehouse_name,
				    wt.creation_dt,
				    DATE(ed.received_date) AS received_date,
				 
				    -- STATUS LOGIC
				    CASE 
				        WHEN wt.type IS NULL OR wt.type = '' THEN 'Pending Inward'
				        ELSE wt.type
				    END AS status
				 
				FROM ems_application_data AS ed
				 
				-- LEFT JOIN so that Pending Inward applications also appear
				LEFT JOIN ems_warehouse_application_transaction AS wt
				    ON ed.id = wt.application_id
				    AND wt.status = 1
				 
				-- Warehouse name join
				LEFT JOIN ems_warehouse ew
				    ON ew.id = wt.warehouse_id
				 
				-- Unit / Police Station
				LEFT JOIN unit_master AS um 
				    ON ed.police_station = um.id
				 
				WHERE 
				    ed.status = 1
				    AND (
				            -- If user selects standard statuses (Inward/Outward)
				            wt.type = '$status'
				 
				            -- If user selects Pending Inward
				            OR ('$status' = 'Pending Inward' AND (wt.type IS NULL OR wt.type = ''))
				        )
				    AND (
				            -- Date filter only applies if warehouse transaction exists
				            wt.creation_dt IS NOT NULL 
				            AND CAST(wt.creation_dt AS DATE)
				                BETWEEN '$from_date' AND '$to_date'
				        )
				";
		
		$command = $connection->createCommand($wuery);
		$data = $command->queryAll();
		
		// echo "<pre>";print_r($data);die;
		
		$connection->close();
        }
        return $data;