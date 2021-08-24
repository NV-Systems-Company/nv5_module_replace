<!-- BEGIN: main -->

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center">{LANG.stt}</th>
				<th class="text-center">{LANG.config_replace}</th>
				<th>{LANG.author}</th>
				<th>{LANG.status}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>	
			<!-- BEGIN: list -->
			<tr>
				<td class="text-center">{LIST.no}</td>
				<td class="text-center">{LIST.config_name}</td>

				<td class="text-center">{LIST.username}</td>
				<td>
					<select id="change_status_{LIST.id}" onchange="nv_chang_status('{LIST.id}');" class="form-control">
						<!-- BEGIN: status -->
							<option value="{STATUS.key}" {STATUS.selected}>{STATUS.val}</option>
						<!-- END: status -->
				</select>
				</td>
				<td>
                        <a class="btn btn-primary btn-xs btn_edit" href="{LIST.link_edit}"><em class="fa fa-edit margin-right"></em> {LANG.edit}</a> <a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_del_content({LIST.id}, '{LIST.checksum}','{NV_BASE_ADMINURL}', 0)"><em class="fa fa-trash-o margin-right"></em> {LANG.delete}</a>
                    </td>
			</tr>
			<!-- END: list -->
		</tbody>

	</table>
</div>


<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			<em class="fa fa-file-text-o">&nbsp;</em>{action_mod}
		</caption>
		<tbody>
			<tr>
				<td>
					<form action="" method="post" class="form-horizontal">
						<div class="panel panel-default">
							<div class="panel-heading">{LANG.replace_config}</div>
							<div class="panel-body">
								<div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><strong>{LANG.title} {LANG.replace} </strong> <em class="fa fa-info-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_name} {LANG.replace}">&nbsp;</em></label>
										<div class="col-sm-20">
											<input type="text" name="config_name" value="{DATA.config_name}" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">{LANG.config_replace}</div>
							<div class="panel-body">
								<div class="form-group">
									<label><input type="checkbox" name="case_replace" value="1" {DATA.ck_case_replace} />{LANG.config_autolink_casesens}</label>
								</div>
								<div class="form-group">
									<div id=replace>
										<!-- BEGIN: replace -->
										<div class="row m-bottom">
											<div class="col-xs-12">
												<input type="text" name="config_replace[{REPLACE.number}][find]" value="{REPLACE.find}" class="form-control" placeholder="{LANG.config_find}" />
											</div>
											<div class="col-xs-12">
												<input type="text" name="config_replace[{REPLACE.number}][replace]" value="{REPLACE.replace}" class="form-control" placeholder="{LANG.config_replace_do}" />
											</div>
										</div>
										<!-- END: replace -->
									</div>
									<button class="btn btn-success btn-xs" onclick="nv_add_replace(); return !1;">{LANG.config_replace_add}</button>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">{LANG.config_keywords}</div>
							<div class="panel-body">
								<div class="form-group">
									<label><input type="checkbox" name="casesens" value="1" {DATA.ck_casesens} />{LANG.config_autolink_casesens}</label>
								</div>
								<div class="form-group">
									<div id="keywords">
										<!-- BEGIN: keywords -->
										<div class="row m-bottom">
											<div class="col-xs-6">
												<input type="text" name="config_keywords[{KEYWORDS.number}][keywords]" value="{KEYWORDS.keywords}" class="form-control" placeholder="{LANG.config_keywords}" />
											</div>
											<div class="col-xs-6">
												<input type="url" name="config_keywords[{KEYWORDS.number}][link]" value="{KEYWORDS.link}" class="form-control" placeholder="{LANG.config_link}" />
											</div>
											<div class="col-xs-6">
												<select class="form-control" name="config_keywords[{KEYWORDS.number}][target]">
													<!-- BEGIN: target -->
													<option value="{TARGET.index}"{TARGET.selected}>{TARGET.value}</option>
													<!-- END: target -->
												</select>
											</div>
											<div class="col-xs-6">
												<input type="number" name="config_keywords[{KEYWORDS.number}][limit]" value="{KEYWORDS.limit}" class="form-control" placeholder="{LANG.config_limit}" />
											</div>
										</div>
										<!-- END: keywords -->
									</div>
									<button class="btn btn-success btn-xs" onclick="nv_add_keywords(); return !1;">{LANG.config_keywords_add}</button>
								</div>
							</div>
						</div>
						<div class="text-center">
							<input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
						</div>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
	<script>
		function nv_chang_status(vid) {
			var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
			var new_status = $('#change_status_' + vid).val();
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&changestatus=1&nocache=' + new Date().getTime(), 'id=' + vid + '&new_status=' + new_status, function(res) {
				nv_chang_weight_res(res);
			});
			return;
		}
		function nv_chang_weight_res(res) {
			var r_split = res.split("_");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
				clearTimeout(nv_timer);
			} else {
				window.location.href = window.location.href;
			}
			return;
		}
	
	
		$('input[name="save_type"]').change(function() {
			if ($(this).val() == 0) {
				$("#save_type_0").removeClass('hidden');
				$("#save_type_1").addClass('hidden');
			} else {
				$("#save_type_1").removeClass('hidden');
				$("#save_type_0").addClass('hidden');
			}
		});

		var keywords_count = {KEYWORDS_COUNT};
		function nv_add_keywords() 
		{
			var html = '';
			html += '<div class="row m-bottom">';
			html += '	<div class="col-xs-6">';
			html += '		<input type="text" name="config_keywords[' + keywords_count + '][keywords]" class="form-control" placeholder="{LANG.config_keywords}" />';
			html += '	</div>';
			html += '	<div class="col-xs-6">';
			html += '		<input type="url" name="config_keywords[' + keywords_count + '][link]" class="form-control" placeholder="{LANG.config_link}" />';
			html += '	</div>';
			html += '	<div class="col-xs-6">';
			html += '		<select class="form-control" name="config_keywords[' + keywords_count + '][target]">';
								<!-- BEGIN: target_js -->
			html += '			<option value="{TARGET.index}" {TARGET.selected}>{TARGET.value}</option>';
								<!-- END: target_js -->
			html += '		</select>';
			html += '	</div>';
			html += '<div class="col-xs-6">';
			html += '	<input type="number" name="config_keywords[' + keywords_count + '][limit]" class="form-control" placeholder="{LANG.config_limit}" />';
			html += '</div>';
			html += '</div>';
			$('#keywords').append(html);
			keywords_count += 1;
		}
		
		var replace_count = {REPLACE_COUNT};
		function nv_add_replace()
		{
			var html = '';
			html += '<div class="row m-bottom">';
			html += '	<div class="col-xs-12">';
			html += '		<input type="text" name="config_replace[' + replace_count + '][find]" class="form-control" placeholder="{LANG.config_find}" />';
			html += '	</div>';
			html += '	<div class="col-xs-12">';
			html += '		<input type="text" name="config_replace[' + replace_count + '][replace]" class="form-control" placeholder="{LANG.config_replace_do}" />';
			html += '	</div>';
			html += '</div>';
			$('#replace').append(html);
			replace_count += 1;
		}
	</script>
</div>
<!-- BEGIN: main -->