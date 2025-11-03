<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\IgrmsMaster;
use frontend\models\VcManualParticipants;

$this->title = 'Online Statement';

// Register Bootstrap assets
\yii\bootstrap\BootstrapAsset::register($this);
\yii\web\JqueryAsset::register($this);

// Then Bootstrap JS
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Default classes
$defaultBtnClass = 'btn btn-navy'; 

// Send Notice button
$noticeBtnClass = $participant->user_feedback == 1 ? 'btn btn-success' : $defaultBtnClass;

// Send VC Link button
$vcLinkBtnClass = $participant->user_feedback == 1 ? 'btn btn-success' : $defaultBtnClass;
$vcLinkDisabled = $participant->user_feedback != 1 ? 'disabled' : '';

// Voice Statement button
$statementBtnClass = $participant->voice_statement_flag == 1 ? 'btn btn-success' : $defaultBtnClass;
?>
<!-- jQuery (must be loaded first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap CSS (also required for modal styles) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
    body {
        margin-top: 100px;
    }
    .btn-group {
        margin-top: 20px;
    }
    
    /* Modal Styles */
    #manualModal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    }
    /* Custom CSS for modal width */
	.modal-dialog {
	    max-width: 80%;  /* Adjusts the modal width */
	}
	/* Custom CSS for the textarea to be resizable horizontally and vertically */
	textarea.form-control {
	    resize: both;  /* Enables both horizontal and vertical resizing */
	    min-width: 100%; /* Ensures that the textarea takes up the full width initially */
	    min-height: 150px; /* Ensures a minimum height for the textarea */
	}
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover {
        color: black;
        cursor: pointer;
    }
    .btn-success {
    background-color: green !important;
    color: white;
    }
    table.dataTable thead th {
        background-color: #34495e;
        color: white;
        font-size: 18px;
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after {
    background-color: #34495e !important;
    color: white !important; 
    }
    /* Proper fix for DataTables double sorting arrow issue */
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_desc:after {
        content: ""; /* Hide old arrows */
        display: none !important;
        background-color: #34495e !important;
    color: white !important; 
    }
    #participantsTable {
        border-collapse: collapse;
    }
    #participantsTable td, #participantsTable th {
        border: 2px solid #000000 !important;
    }
    .btn {
        padding: 4px 8px; /* Reduce padding */
        font-size: 12px;   /* Reduce font size */
    }
    
  
    .toast-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 1056;
    }

     body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }
    #btnContainer {
      padding: 20px;
      text-align: center;
    }
    #iframeContainer {
      display: none;
      height: 90vh;
      display: flex;
      margin-top: 25px;
    }
    iframe {
      flex: 1;
      border: none;
    }

</style>

<?php if (isset($_GET['iframe'])): ?>
<style>
    header, footer, .menu-bar {
        display: none !important;
    }
    /* Add any other styles needed for iframe mode */
    body {
        padding: 0;
        margin: 0;
    }
    

</style>
<?php endif; ?>

<div class="igrms-master-view">
    <h3 align="center"><?= Html::encode($this->title) ?> for Application ID: <?= $id ?></h3>

    <div class="ca" align="right">
   
            <?= Html::button("<i class='fas fa-sync-alt'></i> ", [
		    'class' => 'btn btn-info btn-sm',
		    'style' => 'color: white;',
		    'onclick' => 'location.reload();',
		    'title' => 'Refresh Page'
	    ]) ?>
	    
	    <?= Html::button("<i class='fas fa-arrow-left'></i> ", [
		    'class' => 'btn btn-secondary btn-sm',
		    'style' => 'background-color: #34495e; color: white;',
                    'onclick' => 'window.location.href = "https://sanpri.co.in/HRMSDEV/igrms-master/update_view?id=' . Yii::$app->request->get('id') . '";'
	    ]) ?>
</div>


    <table id="myTable" class="table table-bordered" style="display:none;">
        <tbody>
            <tr>
                <td>Application Type</td>
                <td><?= $data[0]['channel_name'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Grievance No.</td>
                <td><?= $data[0]['id'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Date</td>
                <td><?= $data[0]['created_date'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Applicant Name</td>
                <td><?= $data[0]['reporter_name'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Applicant No.</td>
                <td><?= $data[0]['contact_number'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Applicant Address</td>
                <td><?= $data[0]['applicant_address'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Incidence</td>
                <td><?= $data[0]['incidence_type'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Police Station</td>
                <td><?= $data[0]['unit_name'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Non Applicant Name</td>
                <td><?= $data[0]['non_app_name'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Non Applicant No.</td>
                <td><?= $data[0]['na_contact_number'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Non Applicant Address</td>
                <td><?= $data[0]['na_address'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Attachment</td>
                <td>
                    <?php
                    $grievence_id = $data[0]['id'];
                    $ig_obj = new \frontend\models\IgrmsMaster;
                    $attachment = $ig_obj->getAttachmentDataOfOpen($grievence_id);

                    if (!empty($attachment)) {
                        foreach ($attachment as $doc) {
                            echo Html::a($doc['document_name'], Yii::$app->request->baseUrl . "/images/grievance_doc/" . $doc['document_name']) . "<br>";
                        }
                    } else {
                        echo "No Attachments";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?= $data[0]['description'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Area</td>
                <td><?= $data[0]['area'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $data[0]['status_name'] ?? '-' ?></td>
            </tr>
        </tbody>
    </table>

   <div class="btn-group" style="margin-top: 20px; display: none;">
        <div class="col-sm-2 text-end">
            <button class="btn" onclick="openModal()" 
                style="background: none; border: none; padding: 0; margin: 0;">
                <i style="font-size: 40px; color: #34495e;" class="fa fa-plus-square" aria-hidden="true"></i>
            </button>
	    </div>
   </div>

   <!-- Form for new entry in modal -->
    <div id="manualModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            
            <h4>Application ID: <?= $id ?></h4>
            
            <form id="manualForm" method="post">
                <div class="form-group">
                    <label>Participant Type</label>
                    <select class="form-control" name="participant_type" required>
                        <option value="">Select Type</option>
                        <option value="applicant">Applicant Statement</option>
                        <option value="non_applicant">Non Applicant Statement</option>
                        <option value="witness">Witness</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" class="form-control" name="mobile_number" 
	           pattern="[0-9]{10}" maxlength="10" minlength="10" required">
	           <div id="mobile-error" class="text-danger" style="font-size: 0.9em;"></div>

                </div>
                
                <!-- Hidden input for Grievance ID -->
                <input type="hidden" name="grievance_id" value="<?= $id ?>">
                
                <div style="margin-top: 20px;">
                   <button type="button" class="btn btn-primary" id="sendNoticeBtn" onclick="sendNoticeFromModal();">
                        Send Notice
                    </button>   
                    
                    <!-- <button type="button" class="btn btn-primary send-notice-btn">
                        Send Notice
                    </button>  -->               
                </div>
            </form>
        </div>
    </div>
    
    <?php //echo "<pre>";print_r($data);die;?>
    
<!-- Success Message Div -->    
<div id="notice-success-message" style="display: none; padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 10px;"></div>
<div id="vc-success-message" style="display: none; padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 10px;"></div>

<!-- VC Room Confirmation Modal -->
<div id="vcRoomConfirmModal" style="display: none; position: fixed; top: 0; left: 0; 
    width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; 
    justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 320px; text-align: center;">
        <p>VC Link has been sent.<br>Do you want to join the VC Room now?</p>
        <button class="btn btn-primary" onclick="joinVcRoom()" style="margin-right: 10px; padding:5px 10px;">Yes</button>
        <button class="btn btn-primary" onclick="closeVcRoomModal()" style="padding:5px 10px;">No</button>
    </div>
</div>

    <!-- Participants Grid -->
    <div class="participants-grid" style="margin-top: 40px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="margin: 0;">Participants List</h4>
            <button class="btn" onclick="openModal()" 
                style="background: none; border: none; padding: 0; margin: 0;">
                <i style="font-size: 40px; color: #0f2d7d;" class="fa fa-plus-square" aria-hidden="true"></i>
            </button>
        </div>    
	    <table id="participantsTable" class="table table-bordered table-striped">
            <thead style="background-color: #34495e; color: white;">
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Date</th>
                    <th>Actions</th> <!-- Action Column -->
                </tr>
            </thead>
            <tbody>
            <!-- Applicant Info -->
	            <tr style="display:none;">
	                <td>Applicant</td>
	                <td><?= $data[0]['reporter_name'] ?? '-' ?></td>
	                <td><?= $data[0]['contact_number'] ?? '-' ?></td>
	                <td><?= isset($data[0]['created_date']) ? date('d-m-Y', strtotime($data[0]['created_date'])) : '-' ?></td>
	                <td>
	                    <!-- Notice Button -->
	                    <button class="btn btn-success"
	                        data-name="<?= Html::encode($data[0]['reporter_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['contact_number']) ?>"
	                        data-type="Applicant"
	                        data-id="<?= Html::encode($data[0]['id']) ?>"
	                        onclick="sendNotice(this)">
	                        Send Notice
	                    </button>
	                    
	                    <!-- VC Link Button -->
	                    <?php
	                        $hasFeedback = isset($data[0]['user_feedback']) && $data[0]['user_feedback'] == 1;
	                        $vcCompleted = isset($data[0]['vc_status']) && $data[0]['vc_status'] == 1;
	                        
	                        if (!$hasFeedback) {
	                            $vcClass = 'btn btn-primary';
	                            $tooltip = 'Participant feedback not received for online meeting';
	                        } elseif ($vcCompleted) {
	                            $vcClass = 'btn btn-success';
	                            $tooltip = '';
	                        } else {
	                            $vcClass = 'btn btn-primary';
	                            $tooltip = '';
	                        }
	                    ?>
	                    <button class="<?= $vcClass ?>" 
	                        data-name="<?= Html::encode($data[0]['reporter_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['contact_number']) ?>"
	                        data-type="Applicant"
	                        data-id="<?= Html::encode($data[0]['id']) ?>"
	                        title="<?= $tooltip ?>"
	                        onclick="<?= $hasFeedback ? 'sendVcLink(this)' : 'showFeedbackMessage(event)' ?>"
	                        <?= $hasFeedback ? '' : 'disabled' ?>>
	                        Send VC Link
	                    </button>
	                    
	                    <!-- Statement Button -->
	                    <?php
	                        $statementFlag = isset($data[0]['voice_statement_flag']) ? $data[0]['voice_statement_flag'] : 0;
	                        $statementClass = in_array($statementFlag, [1, 2, 3]) ? 'btn btn-success' : 'btn btn-primary';
	                    ?>
	                    <button class="<?= $statementClass ?>" 
	                        onclick="confirmAndRedirect('<?= Yii::$app->urlManager->createUrl(['igrms-master/voice-statement', 'id' => $data[0]['id']]) ?>')">
	                        Statement
	                    </button>
	                    
	                    <!-- Remark Button -->
	                    <?php
	                        $vcRoom = isset($data[0]['vc_room']) ? $data[0]['vc_room'] : '';
	                        
	                        $saveRecordingUrl = Yii::$app->urlManager->createUrl([
	                            'igrms-master/online-statement',
	                            'id' => $id,
	                            'save_recording' => 1,
	                            'participant_name' => $data[0]['reporter_name'],
	                            'participant_number' => $data[0]['contact_number'],
	                        ]);
	                    ?>
	                    <button class="btn btn-primary"
	                        data-url="<?= $saveRecordingUrl ?>"
	                        data-id="<?= $data[0]['id'] ?>"
	                        data-grievance="<?= $id ?>"
	                        data-name="<?= Html::encode($data[0]['reporter_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['contact_number']) ?>"
	                        data-vc-room="<?= $vcRoom ?>"
	                        onclick="openRecordingRemarkModal(this)">
	                        Remark
	                    </button>
	                </td>
	            </tr>
	
	            <!-- Non-Applicant Info -->
	            <tr style="display:none;">
	                <td>Non Applicant</td>
	                <td><?= $data[0]['non_app_name'] ?? '-' ?></td>
	                <td><?= $data[0]['na_contact_number'] ?? '-' ?></td>
	                <td><?= isset($data[0]['created_date']) ? date('d-m-Y', strtotime($data[0]['created_date'])) : '-' ?></td>
	                <td>
	                    <!-- Notice Button -->
	                    <button class="btn btn-success"
	                        data-name="<?= Html::encode($data[0]['non_app_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['na_contact_number']) ?>"
	                        data-type="Non Applicant"
	                        data-id="<?= Html::encode($data[0]['id']) ?>"
	                        onclick="sendNotice(this)">
	                        Send Notice
	                    </button>
	                    
	                    <!-- VC Link Button -->
	                    <?php
	                        $hasFeedback = isset($data[0]['user_feedback']) && $data[0]['user_feedback'] == 1;
	                        $vcCompleted = isset($data[0]['vc_status']) && $data[0]['vc_status'] == 1;
	                        
	                        if (!$hasFeedback) {
	                            $vcClass = 'btn btn-primary';
	                            $tooltip = 'Participant feedback not received for online meeting';
	                        } elseif ($vcCompleted) {
	                            $vcClass = 'btn btn-success';
	                            $tooltip = '';
	                        } else {
	                            $vcClass = 'btn btn-primary';
	                            $tooltip = '';
	                        }
	                    ?>
	                    <button class="<?= $vcClass ?>" 
	                        data-name="<?= Html::encode($data[0]['non_app_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['na_contact_number']) ?>"
	                        data-type="Non Applicant"
	                        data-id="<?= Html::encode($data[0]['id']) ?>"
	                        title="<?= $tooltip ?>"
	                        onclick="<?= $hasFeedback ? 'sendVcLink(this)' : 'showFeedbackMessage(event)' ?>"
	                        <?= $hasFeedback ? '' : 'disabled' ?>>
	                        Send VC Link
	                    </button>
	                    
	                    <!-- Statement Button -->
	                    <?php
	                        $statementFlag = isset($data[0]['voice_statement_flag']) ? $data[0]['voice_statement_flag'] : 0;
	                        $statementClass = in_array($statementFlag, [1, 2, 3]) ? 'btn btn-success' : 'btn btn-primary';
	                    ?>
	                    <button class="<?= $statementClass ?>" 
	                        onclick="confirmAndRedirect('<?= Yii::$app->urlManager->createUrl(['igrms-master/voice-statement', 'id' => $data[0]['id']]) ?>')">
	                        Statement
	                    </button>
	                    
	                    <!-- Remark Button -->
	                    <?php
	                        $vcRoom = isset($data[0]['vc_room']) ? $data[0]['vc_room'] : '';
	                        
	                        $saveRecordingUrl = Yii::$app->urlManager->createUrl([
	                            'igrms-master/online-statement',
	                            'id' => $id,
	                            'save_recording' => 1,
	                            'participant_name' => $data[0]['non_app_name'],
	                            'participant_number' => $data[0]['na_contact_number'],
	                        ]);
	                    ?>
	                    <button class="btn btn-primary"
	                        data-url="<?= $saveRecordingUrl ?>"
	                        data-id="<?= $data[0]['id'] ?>"
	                        data-grievance="<?= $id ?>"
	                        data-name="<?= Html::encode($data[0]['non_app_name']) ?>"
	                        data-number="<?= Html::encode($data[0]['na_contact_number']) ?>"
	                        data-vc-room="<?= $vcRoom ?>"
	                        onclick="openRecordingRemarkModal(this)">
	                        Remark
	                    </button>
	                </td>
	            </tr>

                <!-- Manually Added Participants -->
                    <?php
                        $manualParticipants = VcManualParticipants::find()
                            ->where(['case_app_id' => $id])
                            ->orderBy(['modified_date' => SORT_DESC])
                            ->all();
                        foreach ($manualParticipants as $participant): 
                    ?>
                <tr>
                    <td><?= Html::encode(VcManualParticipants::getStatementTypeName($participant->type)) ?></td>
                    <td><?= Html::encode($participant->name) ?></td>
                    <td><?= Html::encode($participant->number) ?></td>
                    <td><?= date('d-m-Y', strtotime($participant->modified_date)) ?></td>
                    <td>
                        <!-- Add Send VC Link & Send Notice buttons for each participant -->
                        
                        
                        
                        <button class="btn btn-success" 
                            style='background-color:green --!important;'
                            data-name="<?= Html::encode($participant->name) ?>" 
                            data-number="<?= Html::encode($participant->number) ?>"
                            data-type="<?= Html::encode(VcManualParticipants::getStatementTypeName($participant->type)) ?>" 
                            data-id="<?= $participant->id ?>"
                            onclick="sendNotice(this)">
                            Send Notice 
                        </button> 
                        
                        <!-- <button class="btn btn-success send-notice" style='background-color:green --!important;'
                            data-name="<?= Html::encode($participant->name) ?>" 
                            data-number="<?= Html::encode($participant->number) ?>"
                            data-type="<?= Html::encode(VcManualParticipants::getStatementTypeName($participant->type)) ?>" 
                            data-id="<?= $participant->id ?>"
                        >
                            Send Notice  
                        </button>-->
                        
                        
                        
                
                        <?php
                            $hasFeedback = $participant->user_feedback == 1;
                            $vcCompleted = $participant->vc_status == 1;
                            
                            if (!$hasFeedback) {
                                $vcClass = 'btn btn-primary'; // navy (default)
                                $tooltip = 'Participant feedback not received for online meeting';
                            } elseif ($vcCompleted) {
                                $vcClass = 'btn btn-success'; // green
                                $tooltip = '';
                            } else {
                                $vcClass = 'btn btn-primary'; // navy (default)
                                $tooltip = '';
                            }
                        ?>
                        <button class="<?= $vcClass ?>" 
                            data-name="<?= Html::encode($participant->name) ?>" 
                            data-number="<?= Html::encode($participant->number) ?>"
                            data-type="<?= Html::encode(VcManualParticipants::getStatementTypeName($participant->type)) ?>" 
                            data-id="<?= $participant->id ?>"
                            title="<?= $tooltip ?>"
                            onclick="<?= $hasFeedback ? 'sendVcLink(this)' : 'showFeedbackMessage(event)' ?>"
                            <?= $hasFeedback ? '' : 'disabled' ?>>
                            Send VC Link
                        </button>

                        
			<?php
			
			// Get flag from vc_notice_details_transaction
			$flagRow = Yii::$app->db->createCommand("
			    SELECT voice_statement_flag 
			    FROM vc_notice_details_transaction 
			    WHERE participant_id = :participant_id 
			    ORDER BY statement_confirm_date DESC
			    LIMIT 1
			")->bindValue(':participant_id', $participant->id)->queryOne();
			
			$transactionFlag = $flagRow['voice_statement_flag'] ?? null;
			$participantFlag = $participant->voice_statement_flag;
			
			// Determine final class
			$statementClass = (
			    in_array($transactionFlag, [1, 2, 3]) || in_array($participantFlag, [1, 2, 3])
			) ? 'btn btn-success' : 'btn btn-primary';
			?>

			                    
                        <button class="<?= $statementClass ?>" onclick="confirmAndRedirect('<?= Yii::$app->urlManager->createUrl(['igrms-master/voice-statement', 'id' => $participant->id]) ?>')">
                            Statement
                        </button>
                
                        <?php
                            // Fetch vcNotice using participant ID
                            $vcNotice = VcManualParticipants::find()->where(['id' => $participant->id])->one(); // Adjust 'participant_id' if the column is different
                            $vcRoom = $vcNotice ? $vcNotice->vc_room : '';
                            
                            $saveRecordingUrl = Yii::$app->urlManager->createUrl([
                                'igrms-master/online-statement',
                                'id' => $id,
                                'save_recording' => 1,
                                'participant_name' => $participant->name,
                                'participant_number' => $participant->number,
                            ]);
                        ?>
                        
                        
                       <?php if (!empty($vcRoom)) : ?>
			    <?php
			        $status = $participant->remark_status;
                                $disabled = ($status == 0 || $status == 2) ? 'disabled' : '';
			        $btnClass = 'btn ';
			
			        if ($status == 2) {
			            $btnClass .= 'btn-success'; // Green
			        } elseif ($status == 1) {
			            $btnClass .= 'btn-primary'; // Blue
			        } else {
			            $btnClass .= 'btn-secondary'; // Optional: greyed out look if disabled
			        }
			    ?>
			    <button 
			        class="<?= $btnClass ?>" 
			        onclick="openRecordingRemarkModal(this)"
			        data-id="<?= $participant->id ?>"
			        data-grievance="<?= $participant->case_app_id ?>" 
			        data-vc-room="<?= strtolower($vcRoom) ?>" 
			        data-name="<?= $participant->name ?>" 
			        data-url="<?= Yii::$app->urlManager->createUrl(['igrms-master/save-recordings']) ?>"
			        <?= $disabled ?>
			    >
			        Remark
			    </button>
			<?php endif; ?>



                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal HTML -->
<div class="modal fade" id="recordingRemarkModal" tabindex="-1" style="display: none; margin: 100px; padding-right: 0px;" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin:0px; width: 80%; height:auto;"> <!-- Increased width here -->
        <form id="recordingRemarkForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">IO Remark</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="participant_id" id="participant_id">
                    <input type="hidden" name="grievance_id" id="grievance_id">
                    <input type="hidden" name="vc_room" id="vc_room"> <!-- Hidden input for VC room -->
                    <div class="form-group">
                        <label for="grievanceId">Application ID:</label>
                        <label id="grievanceId"></label> <!-- This will display the Grievance ID -->
                    </div>
                    
                    <div class="form-group">
                        <label for="remark">Remark:</label>
                        <textarea class="form-control" name="remark" id="remark" required style="width:100%; height:150px; resize: both;"></textarea> <!-- Textarea is now resizable -->
                    </div>
                    <div class="form-group" style="display:none;">
                        <label for="vcRoom">VC Room</label>
                        <p id="vcRoom"></p> <!-- This will display the VC room -->
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Input for URL -->
<input type="hidden" id="save_recording_url" value="<?= Yii::$app->urlManager->createUrl(['igrms-master/save-recordings']) ?>">


<input type="hidden" name="participant_id" id="participant_id">
<input type="hidden" name="grievance_id" id="grievance_id">

<!-- Toast Container (top-right) -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1056;">
    <div id="recordingToast" class="toast align-items-center text-white bg-info border-0" role="alert" style="background-color: #17a2b8 !important;"> <!-- bg-info color with !important -->
        <div class="d-flex">
            <div class="toast-body" id="recordingToastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<div id="iframeContainer" style="position: relative;">
    <div id="iframeButtons" style="position: fixed; display: none; right: 9px; z-index: 1000;"> 
        <button onclick="refreshVoiceStatement()"
            style="right: 50px; z-index: 1000;"
            class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button onclick="closeIframeContainer()" 
            style="right: 15px; z-index: 1000;"
            class="btn btn-primary">
            <i class="bi bi-x-circle"></i> 
        </button>
    </div>
    <iframe id="vcIframe" allow="camera; microphone; fullscreen; display-capture"></iframe>
    <iframe id="voiceIframe"></iframe>
</div>



<?php
$this->registerCssFile('https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdn.datatables.net/plug-ins/1.13.6/sorting/datetime-moment.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.6/sorting/datetime-moment.js"></script>

<script>
    // Modal Functions
    function openModal() {
        document.getElementById('manualModal').style.display = 'block';
         
            $('#manualModal').show(); // Or use Bootstrap's modal() if using Bootstrap

	    // Reset the form
	    $('#manualForm')[0].reset();
	
	    // Clear previous error messages
	    $('#mobile-error').text('');
	    
	    // Optional: set focus to the first input
	    $('input[name="name"]').focus();
    }

    function closeModal() {
        document.getElementById('manualModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('manualModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<?php
    $script = <<< JS
    $(function() {
        // Ensure moment plugin is loaded before using it
        if ($.fn.dataTable.moment) {
            $.fn.dataTable.moment('DD-MM-YYYY'); // your date format
        }

        $('#participantsTable').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],

            "ordering": true,
            "order": [[3, "desc"]], // Sort by 4th column (Date)
            "searching": true,
            "info": true
        });
    });
    JS;
    $this->registerJs($script);
?>

<script>

    <script>
        function redirectToDocPage(participantId) {
            window.location.href = voice-statement?id= + participantId;
        }
    </script>
    
    <script>
        function confirmAndRedirect(url) {
            window.location.href = url;
       }
    </script>

</script>

<script>
const vpar='';
    function sendNotice(button) {
   
	    // Disable the clicked button
	    button.disabled = true;
	    const originalText = button.textContent;
	    button.textContent = 'Sending... Please wait';
	
	    // Re-enable after 30 seconds
	    setTimeout(() => {
	        button.disabled = false;
	        button.textContent = originalText;
	    }, 30000); // 30 seconds
	    
        const name = button.getAttribute('data-name');
        const number = button.getAttribute('data-number');
        const typeLabel = button.getAttribute('data-type');
        const userId = button.getAttribute('data-id');
        const type = (typeLabel.toLowerCase().includes('applicant')) ? 0 : 1;
        const grievanceId = <?= json_encode($id) ?>;

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['igrms-master/send-notice']) ?>',
            type: 'POST',
            data: {
                user_name: name,
                user_number: number,
                user_id: userId,
                type: type,
                grievance_id: grievanceId
            },
            success: function (response) {
                showSuccessMessage(response.message || 'Notice sent successfully.');
                setTimeout(() => location.reload(), 2000); // Reload after 2 sec
            },
            error: function () {
                console.log('Error while sending notice.');
            }
        });
    }

    function showSuccessMessage(message) {
        const msgDiv = document.getElementById('notice-success-message');
        msgDiv.textContent = message;
        msgDiv.style.display = 'block';
    }
</script>

<script>


 function sendNoticeNew(button,name,number,userId,type,grievanceId,actionType) {
               
	    // Disable the clicked button
	    button.disabled = true;
	    const originalText = button.textContent;
	    button.textContent = 'Sending... Please wait';
	
	    // Re-enable after 30 seconds
	    setTimeout(() => {
	        button.disabled = false;
	        button.textContent = originalText;
	    }, 30000); // 30 seconds
	    

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['igrms-master/send-notice']) ?>',
            type: 'POST',
            data: {
                user_name: name,
                user_number: number,
                user_id: userId,
                type: type,
                grievance_id: grievanceId,
                actionType:actionType
            },
            success: function (response) {
                showSuccessMessage(response.message || 'Notice sent successfully.');
                setTimeout(() => location.reload(), 2000); // Reload after 2 sec
            },
            error: function () {
                console.log('Error while sending notice.');
            }
        });
    }


$(document).on('click','.send-notice',function(){

       const name = $(this).data('name');
       const number = $(this).data('number');
       const typeLabel = $(this).data('type');
       const userId = $(this).data('id');
       const type = (typeLabel.toLowerCase().includes('applicant')) ? 0 : 1;
       const grievanceId = <?= json_encode($id) ?>;
       
	Swal.fire({
	  title: "Do you want to start taking the statement immediately?",
	  showDenyButton: true,
	  showCancelButton: false,
	  confirmButtonText: "Yes",
	  denyButtonText: "Schedule",
	  customClass: {
	    denyButton: 'my-deny-button'
	  }
	}).then((result) => {
	  /* Read more about isConfirmed, isDenied below */
	  if (result.isConfirmed) {
	    sendNoticeNew(this,name,number,userId,type,grievanceId,'start');
	  } else if (result.isDenied) {
	     sendNoticeNew(this,name,number,userId,type,grievanceId,'schedule');
	   
	  }
	});

})

</script>

<script>
    function sendNoticeFromModal(actionType) {
    
    const $button = $('#sendNoticeBtn');
    $button.prop('disabled', true).text('Please wait...');

    // Start 30-second timer to re-enable button
    setTimeout(() => {
        $button.prop('disabled', false).text('Send Notice');
    }, 30000); // 30 seconds
    
    const name = $('input[name="name"]').val();
    const number = $('input[name="mobile_number"]').val();
    const typeLabel = $('select[name="participant_type"]').val();
    const grievanceId = $('input[name="grievance_id"]').val();

    let type;
    if (typeLabel === 'applicant') {
        type = 1;
    } else if (typeLabel === 'non_applicant') {
        type = 2;
    } else if (typeLabel === 'witness') {
        type = 3;
    } else {
        return;
    }

    if (!name || !number || !typeLabel) {
        return;
    }
    
     if (!/^\d{10}$/.test(number)) {
        alert('Please enter a valid 10-digit mobile number.');
        $('input[name="mobile_number"]').focus();
        return;
    }

    // Fix: pass numeric type to check-duplicate
    $.ajax({
        url: '<?= \yii\helpers\Url::to(['igrms-master/check-duplicate']) ?>',
        type: 'POST',
        data: {
            grievance_id: grievanceId,
            mobile_number: number,
            participant_type: type,
            name: name

        },
        success: function (res) {
            if (res.exists) {
                alert('Notice has already been sent to this participant.');
            } else {
                // Send notice
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['igrms-master/send-notice']) ?>',
                    type: 'POST',
                    data: {
                        user_name: name,
                        user_number: number,
                        type: type,
                        grievance_id: grievanceId,
                        actionType:actionType
                    },
                    success: function (response) {
                        showSuccessMessage(response.message || 'Notice sent successfully.');
                        closeModal();
                        $('#manualForm')[0].reset();
                        setTimeout(() => location.reload(), 2000);
                    },
                    error: function () {
                        console.log('Error while sending notice.');
                    }
                });
            }
        },
        error: function () {
            console.log('Error checking duplicate.');
            $('#sendNoticeBtn').prop('disabled', false); // Re-enable

        }
    });
}


    function showSuccessMessage(message) {
        const msgDiv = document.getElementById('notice-success-message');
        msgDiv.textContent = message;
        msgDiv.style.display = 'block';
    }
</script>

<script>



$(document).on('click','.send-notice-btn',function(){


Swal.fire({
	  title: "Do you want to start taking the statement immediately?",
	  showDenyButton: true,
	  showCancelButton: false,
	  confirmButtonText: "Yes",
	  denyButtonText: "Schedule",
	  customClass: {
	    denyButton: 'my-deny-button'
	  }
	}).then((result) => {
	  /* Read more about isConfirmed, isDenied below */
	  if (result.isConfirmed) {
	   sendNoticeFromModal('start');
	  } else if (result.isDenied) {
	     sendNoticeFromModal('schedule');
	  }
	});

})
</script>


<script>
    let vcRoomUrl = ''; // Save VC room URL after sending
    let vcRoomUrlP = ''; // Save VC room URL after sending
     let nameP = '';
     let numberP = '';
     let typeLabelP = '';
     let userIdP = '';
     let typeP = '';
     let grievanceIdP = '';

    function sendVcLink(button) {
        const name = button.getAttribute('data-name');
        const number = button.getAttribute('data-number');
        const typeLabel = button.getAttribute('data-type');
        const userId = button.getAttribute('data-id');
        const type = (typeLabel.toLowerCase().includes('applicant')) ? 0 : 1;
        const grievanceId = <?= json_encode($id) ?>;
     nameP = name; // Store for later use if needed
   	 numberP = number;
   	 typeLabelP = typeLabel;
   	 userIdP = userId;
   	  typeP = type;
   	 grievanceIdP = grievanceId;

      // alert(nameP);
       vcRoomUrlP = 
    "name=" + encodeURIComponent(name) +
    "&number=" + encodeURIComponent(number) +
    "&typeLabel=" + encodeURIComponent(typeLabel) +
    "&userId=" + encodeURIComponent(userId) +
    "&type=" + type +
    "&grievanceId=" + grievanceId;

//alert(vcRoomUrlP);

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['igrms-master/send-vc-link']) ?>',
            type: 'POST',
            data: {
                user_name: name,
                user_number: number,
                user_id: userId,
                type: type,
                grievance_id: grievanceId
            },
            success: function (response) {
                if (response.status === 'success' && response.vcpath) {
                    vcRoomUrl = response.vcpath; // Save VC URL for later
                    showVcSuccessMessage('VC Link sent successfully.');
                    setTimeout(() => {
                        openVcRoomConfirmModal();
                    }, 500); // Small delay
                } else {
                    showVcSuccessMessage(response.message || 'Failed to send VC Link.');
                }
            },
            error: function () {
                showVcSuccessMessage('Error while sending VC Link.');
            }
        });
    }

	function showVcSuccessMessage(message) {
	    const msgDiv = document.getElementById('vc-success-message');
	    msgDiv.textContent = message;
	    msgDiv.style.display = 'block';
	
	    // Auto-hide after 2 seconds
	    setTimeout(() => {
	        msgDiv.style.display = 'none';
	    }, 2000);
	}


    function openVcRoomConfirmModal() {
        document.getElementById('vcRoomConfirmModal').style.display = 'flex';
    }

    function closeVcRoomModal() {
        document.getElementById('vcRoomConfirmModal').style.display = 'none';
    }

function openVcVoiceModal(vcUrl, voiceUrl) {
    // Set iframe sources
    document.getElementById('vcModalIframe').src = vcUrl;
    document.getElementById('voiceModalIframe').src = voiceUrl;
    
    // Show fullscreen modal
    $('#vcVoiceModal').modal('show');
}

function joinVcRoom() {
    if (vcRoomUrl) {
        // Hide the main content container
        document.querySelector('.igrms-master-view').style.display = 'none';
        
        // Hide the language dropdown
        const langDropdown = document.getElementById('drpLanguage');
        if (langDropdown) {
            langDropdown.style.display = 'none';
        }
        
        // Show the iframe container
        document.getElementById('iframeContainer').style.display = 'flex';
        
        document.getElementById('iframeButtons').style.display = 'block';

        
        // Set iframe sources
	const voiceUrl = "https://sanpri.co.in/HRMSDEV/igrms-master/voice-statement?id=" + userIdP + "&iframe=1";
        document.getElementById('vcIframe').src = vcRoomUrl;
        document.getElementById('voiceIframe').src = voiceUrl;
       // console.log('vcRoomUrl =>',vcRoomUrl);
    }
    closeVcRoomModal();
}

// New function to refresh ONLY the voice statement iframe
function refreshVoiceStatement() {
    const voiceUrl = "https://sanpri.co.in/HRMSDEV/igrms-master/voice-statement?id=" + userIdP + "&iframe=1&t=" + Date.now();
    document.getElementById('voiceIframe').src = voiceUrl; // Force refresh with cache-busting timestamp
}


function closeIframeContainer() {
    // Hide the iframe container
    document.getElementById('iframeContainer').style.display = 'none';
    
    // Show the main content again
    document.querySelector('.igrms-master-view').style.display = 'block';
    
    // Clear iframe sources to stop any ongoing processes
    document.getElementById('vcIframe').src = '';
    document.getElementById('voiceIframe').src = '';
        location.reload();

}


</script>

<script>
    var login_user_id = '<?= $_SESSION['login_user_id'] ?? '' ?>';
    var unit_id = '<?= $_SESSION['login_unit_id'] ?? '' ?>';
    var sub_unit_id = '<?= $_SESSION['login_sub_unit_id'] ?? '' ?>';
</script>

<?php
    // Add this JS if not already present
    $this->registerJs("
        function showFeedbackMessage(event) {
            event.preventDefault();
            alert('Participant feedback not received for online meeting');
        }
    ");
?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
function showToast(message, type = 'success') {
    const toastEl = $('#recordingToast');
    const toastBody = $('#recordingToastMessage');

    // Set color based on type
    const bgClass = type === 'success' ? 'bg-success' : (type === 'warning' ? 'bg-warning' : 'bg-danger');
    toastEl.removeClass('bg-success bg-warning bg-danger bg-primary').addClass(bgClass);

    toastBody.text(message);
    const toast = new bootstrap.Toast(toastEl[0], { delay: 5000 });
    toast.show();
}

function openRecordingRemarkModal(button) {
    const grievanceId = $(button).data('grievance');
    const participantId = $(button).data('id');
    const vcRoom = $(button).data('vc-room');

    $('#participant_id').val(participantId);
    $('#grievance_id').val(grievanceId);
    $('#vc_room').val(vcRoom);

    $('#grievanceId').text(grievanceId);
    $('#recordingRemarkModal').modal('show');
}

$(function () {
    $('#recordingRemarkForm').on('submit', function (e) {
        e.preventDefault();

        const url = $('#save_recording_url').val();
        const formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function (response) {
		    if (response.status === 'success') {
		        showToast(response.message, 'success');
		        $('#recordingRemarkModal').modal('hide');
		        location.reload(); // Reload immediately or you can delay it
		    } else {
		        showToast(response.message, 'warning');
		        $('#recordingRemarkModal').modal('hide');
		        setTimeout(() => location.reload(), 2000); // Delay added for warning too
		    }
		},

		error: function () {
                showToast('An error occurred while saving the recording info.', 'danger');
            }
        });
    });
});

/*
const domain = "vc.sanpri.in";
const options = {
    roomName: "YourRoomName",
    width: "100%",
    height: 600,
    parentNode: document.querySelector("#jitsi-container"),
    configOverwrite: {},
    interfaceConfigOverwrite: {},
};

const api = new JitsiMeetExternalAPI(domain, options);


api.addEventListener('videoConferenceLeft', () => {
    console.log("Call ended!");
    yourCustomFunction();
});

function yourCustomFunction() {
    alert("Meeting has ended!");
}*/


</script>

<style>
 .my-deny-button {
  background-color: #7066e0 !important; 
  color: white !important;
  border: none !important;
}
</style>
