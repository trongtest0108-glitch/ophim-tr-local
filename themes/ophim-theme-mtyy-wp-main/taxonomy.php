<?php
set_query_var('args', ['title' => single_term_title('', false)]);
get_template_part('netflix-listing');
