<?php
echo '<article class="module width_full">';
echo "<header><h3>".tr("GCONFIG_SOFTWARE_TITLE")."</h3></header>
<div class='module_content'>";
echo '<table width="98%"  class="tablesorter" cellspacing="0" border=0>';
echo '<thead><tr>
          <th>'.tr("GCONFIG_SOFTWARE_NAME").'</th>
          <th>'.tr("GCONFIG_SOFTWARE_LOCAL_VERSION").'</th>
          <th>'.tr("GCONFIG_SOFTWARE_DISTANT_VERSION").'</th>
          <th align="center">'.tr("ACTION").'</th>
          </tr>
      </thead>';
echo '<tr>';
echo '<td>';
echo '<a href="https://github.com/PHPMailer/PHPMailer" target="_blank">PhpMailer</a>';
echo '<form id="'.unique_id().'">';
echo '<input type="hidden" name="soft_id" value="phpmailer" />';
echo "<input type='hidden' name='token' value='$token' />";
echo '</form>';
// get local version

// get distant version :

echo '</td><td width="30%">';
echo '5.2.19';
echo '</td><td>';
echo '5.2.21';
echo '</td><td>';
echo 'UPDATE';
if((int)$row['id']>0) {
    echo ' ('.$row['id'].')&nbsp;';
    echo '<a class="tooltip" title="'.$row['date'].'">'.$row['subject'].'</a>';
}
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</div>';
echo "</article>";
echo "<script type='text/javascript' src='js/jquery.form.min.js'></script>
<script>
$(document).ready(function() { 
    var options = { 
            target:'#output',
            beforeSubmit:beforeSubmit,
            success:afterSuccess,
            resetForm:true
    }; 
    $('#bigimportform').submit(function() { 
        $(this).ajaxSubmit(options);         
        return false; 
    }); 
});
function afterSuccess(){
    $('#submit-btn-biglist').show();
    $('#loading-img').hide();
}
function beforeSubmit(){
    $('#submit-btn-biglist').hide();
    $('#loading-img').show();
    $('#output').html('<h4 class=\'alert_info\'><b>".tr("SOFTWARE_UPDATE_IN_PROGRESS")."</b></h4>');
}
$('input.UpdateSoft').click(function(){
    var hideItem=$(this).parents('form').attr('id');
    $.ajax({type: 'POST',
        url: 'include/software_manager.php',
        data: $(this).parents('form').serialize()+'&'+ encodeURI($(this).attr('name'))+'='+ encodeURI($(this).attr('id')),
        success: function(data){
        alert('#'+hideItem);
            $('#'+hideItem).html(data).show();
        }
    });
});
</script>";






