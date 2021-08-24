<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<input type="hidden" name="id" value="{ROW.id}" /> <input type="hidden" name="typeid" value="{ROW.typeid}" />
 <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                  
                    <th class="text-center">Thông tin</th>
                    <th class="text-center"></th>
                    
                  
                </tr>
            </thead>
            <tbody>
               
                <tr>
                    <td>Module</td>
                   
                    <td> 
					<div class="col-xs-24 col-sm-24 col-md-6">
					{LANG.select_module}
					<select name="news_module"class="form-control" >
                        <!-- BEGIN: news_module -->
                        <option value="{NEWS.module_file}-{NEWS.title}"{NEWS.selected}>{NEWS.custom_title}</option>
                        <!-- END: news_module -->
                    </select>
					</div>
					<div id="news_catid"  class="col-xs-24 col-sm-24 col-md-6">
					</div>
					</td>
			
                   
                </tr>	
                <tr>
                   <td>{LANG.date_from}</td>     
                    <td title="{ROW.status}">
					<div class="col-xs-24 col-sm-24 col-md-6">
					<input class="form-control" type="text" name="date_from" id="date_from" value="{date_from}" style="width: 90%;" maxlength="10"> 
					</div>
					<div class="col-xs-24 col-sm-24 col-md-6">
					{LANG.date_to} <input class="form-control" type="text" name="date_to" id="date_to" value="{date_to}" style="width: 90%;" maxlength="10"> 
					</div>
					</td>
         
                   
                </tr>
				
				
             <tr>
			  <td>{LANG.choose_config_replace}</td>
			   <td> <select name="configid" class="col-xs-24 col-sm-24 col-md-6">
                        <!-- BEGIN: replace -->
                        <option value="{CONFIG.id}">{CONFIG.config_name}</option>
                        <!-- END: replace -->
                    </select>
				</td>
				
			 </tr>
			 
			 <tr>
			  <td>{LANG.config_replace}</td>
			   <td>  <input type="checkbox" name="autoreplace" value="1"
                    <!-- BEGIN: autoreplace_disable -->disabled="disabled"<!-- END: autoreplace_disable --> {ROW.ck_autoreplace} />{LANG.config_replace_config}
				</td>
				
			 </tr>
			  <tr>
			  <td>{LANG.config_autolink}</td>
			   <td>  <input type="checkbox" name="autolink" value="1"
                    <!-- BEGIN: autolink_disable -->disabled="disabled"<!-- END: autolink_disable --> {ROW.ck_autolink} />{LANG.config_autolink_note}
				</td>
				
			 </tr>
			 <tr>
			  <td>{LANG.config_hometext}</td>
			   <td>  <input type="checkbox" name="hometext" value="1"
                    <!-- BEGIN: hometext_disable -->disabled="disabled"<!-- END: hometext_disable --> {ROW.ck_hometext} />{LANG.config_hometext_note}
				</td>
				
			 </tr>
			 
            </tbody>
            <tfoot>
                <tr class="text-left">
                    <td colspan="3">
					 <input class="btn btn-primary" name="submit" type="button" value="{LANG.search_submit}" />
                   </td>
                </tr>
            </tfoot>
        </table>
    </div>





	
	
<div class="table-responsive">
	 <table class="table table-striped table-bordered table-hover">
		<thead id="titletable">
			
		</thead>
		<tbody id="title" >
		</tbody>
	</table> 
</div> 


<div id="dbresult">
	<div id="dbrowresult">
	</div>
</div> 

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
	var CFG = [];
	CFG.news_catid = '{ROW.news_catid}';
	CFG.news_groups = '{ROW.news_groups}';
	CFG.is_edit = '{ROW.is_edit}';

	nv_load_news_catid();

	$('select[name=news_module]').change(function(){
		nv_load_news_catid();
	});
	
	$('select[name="groups_id"]').change(function(){
		var groupid = $(this).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(), 'check_type=1&groupid=' + groupid, function(res) {
			var r_split = res.split('_');
			if(r_split[0] == 'OK' && r_split[1] == 1){
				$('#item_url').text('{LANG.items_url}');
				$('input[name="typeid"]').val(1);
			}
			return !1;
		});
	});

	function nv_load_news_catid()
	{
		var module = $('select[name="news_module"]').val();
		$('#news_catid').html('<p class="text-center" style="margin-top: 20px"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>').load( script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=ajax&load_cat=2&module=" + module + '&value=' + CFG['news_catid'] );
	}
	async function delay(delayInms) {
      return new Promise(resolve  => {
        setTimeout(() => {
          resolve(2);
        }, delayInms);
      });
    }
	function replace(rmodule,rlistid,rcatid,rautoreplace,rautolink,rconfigid,rhometext) {
		
		$.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&replace=3&nocache=' + new Date().getTime(), 'module=' + rmodule + '&id=' + rlistid + '&catid=' + rcatid + '&autoreplace=' + rautoreplace + '&autolink=' + rautolink + '&configid=' + rconfigid + '&hometext=' + rhometext, function(data) {
		if(data['success']!='NO')
			$('#title_result_body_' + data['id']).append('<div class="col-xs-24 col-sm-24 col-md-24">{LANG.bodytext_result} ' + data['body_result'] + '</div>'); 
			$('#title_result_home_' + data['id']).append('<div class="col-xs-24 col-sm-24 col-md-24">{LANG.hometext_result} ' + data['home_result'] + '</div>'); 
			
		});	
    }
	function title(rmodule,rlistid,rcatid) {
		
		$.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&replace=2&nocache=' + new Date().getTime(), 'module=' + rmodule + '&id=' + rlistid + '&catid=' + rcatid ,function(data) {
			if(data['success']!='NO')
				$('#title').append('<tr>' 
					+ '<td>'
						+ '<a class="btn btn-primary btn-xs btn_edit" href="' + data['adminlink'] + '" target="_blank"><em class="fa fa-edit margin-right"></em> {LANG.edit}</a> &nbsp; <a class="btn btn-primary btn-xs btn_edit" href="' + data['link'] + '" target="_blank"><em class="fa fa-view margin-right"></em> {LANG.preview}</a>'
					+ '</td>'
					+ '<td>'
						+'<a href="' + data['link'] + '" >' + data['id'] + '-' + data['title'] + '</a>'
					+ '</td>'
					+ '<td>'
						+ '<div id="title_result_body_' + data['id'] + '" class="col-xs-24 col-sm-24 col-md-24">'
						+ '</div>'
						+ '<div class="clearfix"></div>'
						+ '<div id="title_result_home_' + data['id'] + '" class="col-xs-24 col-sm-24 col-md-24">'
						+ '</div>'
						+ '<div class="clearfix"></div>'
					+'</td>'
				+'</tr>'); 
			else{
				$('#title').append('<tr><td colspan="3">' + data['title'] + '</td></tr>'); 
			}
			
		});	
    }

	$('input[name="submit"]').click(function(){
		var module = $('select[name="news_module"]').val();
		var catid = $('select[name="news_catid"]').val();
		var autoreplace = $('input[name="autoreplace"]').is(":checked");
		if(autoreplace == true) autoreplace = 1; else  autoreplace = 0;
		var autolink = $('input[name="autolink"]').is(":checked");
		if(autolink == true) autolink = 1; else  autolink = 0;
		var configid = $('select[name="configid"]').val();
		var date_from = $('input[name="date_from"]').val();
		var date_to = $('input[name="date_to"]').val();
		var hometext = $('input[name="hometext"]').is(":checked");
		if(hometext == true) hometext = 1; else  hometext = 0;
		$('#title').html('');
		$('#titletable').html('');
		$('#titletable').append('<tr><td>{LANG.action}</td><td>{LANG.title}</td><td>{LANG.update_status}</td></tr>'); 
		$('#title_result').html('');
		
		if(autoreplace>0 || autolink >0){
			 $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&replace=1&nocache=' + new Date().getTime(), 'module=' + module + '&catid=' + catid + '&date_from=' + date_from + '&date_to=' + date_to,async function(res) {
				$('#dbrowresult').html('');
				var listid=res.split(',');
				for (var i=0;i<listid.length;i++){
					title(module,listid[i],catid);
					let delayres = await delay(2000);
					replace(module,listid[i],catid,autoreplace,autolink,configid,hometext);
					let delayres2 = await delay(500);
				}
			});
		}else{
			$('#title').append('<tr><td colspan="3">{LANG.action_no}</td></tr>');
		}
	});
	$("#date_from,#date_to").datepicker({
        showOn : "both",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });
	$('select').select2();
</script>
<!-- END: main -->