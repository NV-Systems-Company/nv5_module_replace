<!-- BEGIN: list_cat -->
<table class="table">
    <thead>
        <tr>
            <th>{LANG.news_cat}</th>
            <th class="text-center">{LANG.news_catid_main}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td><label>{CAT.space}<input type="checkbox" name="news_catid[]" value="{CAT.catid}" {CAT.catid_checked} />{CAT.title}
            </label></td>
            <td class="text-center"><input type="radio" name="news_catid_default" value="{CAT.catid}" id="default_{CAT.catid}" {CAT.catdefault_checked} {CAT.catdefault_display} /></td>
        </tr>
        <!-- END: loop -->
    </tbody>
</table>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js" rel="stylesheet" />
<script>
$(document).ready(function() {
	$("input[name='news_catid[]']").click(function() {
		var catid = $("input:radio[name=news_catid_default]:checked").val();
		var radios_catid = $("input:radio[name=news_catid_default]");
		var catids = [];
		$("input[name='news_catid[]']").each(function() {
			if ($(this).prop('checked')) {
				$("#default_" + $(this).val()).removeAttr('disabled');
				catids.push($(this).val());
			} else {
				$("#default_" + $(this).val()).attr('disabled', 'disabled');
				if ($(this).val() == catid) {
					radios_catid.filter("[value=" + catid + "]").prop("checked", false);
				}
			}
		});

		if (catids.length > 1) {
			for ( i = 0; i < catids.length; i++) {
				$("#default_" + catids[i]).show();
			};
			catid = parseInt($("input:radio[name=news_catid_default]:checked").val() + "");
			if (!catid) {
				radios_catid.filter("[value=" + catids[0] + "]").prop("checked", true);
			}
		}
	});
});
</script>
<!-- END: list_cat -->

<!-- BEGIN: list_groups -->
<!-- BEGIN: loop -->
<label class="show"><input type="checkbox" value="{BLOCKS.bid}" name="news_groups[]"{BLOCKS.checked}>{BLOCKS.title}</label>
<!-- END: loop -->
<!-- END: list_groups -->

<!-- BEGIN: list_cat_2 -->
{LANG.select_cat}
<select name="news_catid" class="form-control"> 
<!-- BEGIN: loop -->
	<option value="{CAT.catid}" {CAT.catid_checked} >{CAT.space}{CAT.title}</option>
<!-- END: loop -->
</select>
<script>
$(document).ready(function() {
	$('select').select2();
});
</script>
<!-- END: list_cat_2 -->