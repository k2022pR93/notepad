emp_ticket_details

select *,tpf.created_on AS received_date,etd.created_on as submited_date,tpf.status as process_status,em.id,
			(SELECT type_name FROM mst_ticket_types WHERE pk_type_id = etd.fk_subject_id) AS subject_name,
			case when processed_by is not null then  (SELECT CONCAT(emp_firstname, ' ', emp_lastname) FROM emp_master WHERE id = 
			(select processed_by from (select processed_by, rank() over(partition by fk_ticket_id order by id desc) rnk FROM ticket_process_flow
			where fk_ticket_id=etd.id and  status=1
			order by fk_ticket_id , id desc) a where a.rnk=1
			)
			) else (SELECT CONCAT(emp_firstname, ' ', emp_lastname) FROM emp_master WHERE id =etd.fk_emp_master_id) end
			
			AS full_name
			from emp_ticket_details etd
			inner join emp_master em on em.id=etd.fk_emp_master_id
			inner join unit_master um on em.unit_master_id=um.id
			inner join ticket_process_flow tpf on tpf.fk_ticket_id=etd.id
			where  tpf.status=0   and etd.final_save='1' and em.active_status=1 and tpf.unit_id = 19841 and tpf.handler_by_id='PROF1' 

			====================

			select *,tpf.created_on AS received_date,etd.created_on as submited_date,tpf.status as process_status,em.id,
			(SELECT type_name FROM mst_ticket_types WHERE pk_type_id = etd.fk_subject_id) AS subject_name,
			case when processed_by is not null then  (SELECT CONCAT(emp_firstname, ' ', emp_lastname) FROM emp_master WHERE id = 
			(select processed_by from (select processed_by, rank() over(partition by fk_ticket_id order by id desc) rnk FROM ticket_process_flow
			where fk_ticket_id=etd.id and  status=1
			order by fk_ticket_id , id desc) a where a.rnk=1
			)
			) else (SELECT CONCAT(emp_firstname, ' ', emp_lastname) FROM emp_master WHERE id =etd.fk_emp_master_id) end
			
			AS full_name
			from emp_ticket_details etd
			inner join emp_master em on em.id=etd.fk_emp_master_id
			inner join unit_master um on em.unit_master_id=um.id
			inner join ticket_process_flow tpf on tpf.fk_ticket_id=etd.id
			where  tpf.status=0   and etd.final_save='1' and em.active_status=1 and tpf.handler_by_id='101857' 