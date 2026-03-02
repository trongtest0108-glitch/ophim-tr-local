<?php
$title = single_tag_title('', false);
if (!$title) {
    $title = post_type_archive_title('', false) ?: 'Danh sach';
}
set_query_var('args', ['title' => $title]);
get_template_part('netflix-listing');
