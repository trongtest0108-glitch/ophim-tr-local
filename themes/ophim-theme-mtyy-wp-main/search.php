<?php
set_query_var('args', ['title' => 'Tim kiem: ' . get_search_query()]);
get_template_part('netflix-listing');
