<!-- BEGIN: main -->
<div class="autoget-news">
    <!-- BEGIN: loop -->
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="{DATA.url}" rel="nofollow" title="{DATA.title}" target="_blank"><img alt="{DATA.homeimagealt}" src="{DATA.thumb}" class="img-thumbnail pull-left imghome" width="100" /></a>
            <h3>
                <a href="{DATA.url}" rel="nofollow" title="{DATA.title}" target="_blank">{DATA.title}</a>
            </h3>
            <p>{DATA.hometext}</p>
            <!-- BEGIN: source -->
            <p>
                <strong>{LANG.source}:</strong> <a rel="nofollow" href="{DATA.source_url}" target="_blank" title="{DATA.source_text}">{DATA.source_text}</a>
            </p>
            <!-- END: source -->
        </div>
    </div>
    <!-- END: loop -->

    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: main -->