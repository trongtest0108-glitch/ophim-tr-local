<?php

//ajax search
add_action('wp_footer', 'ajax_fetch');
function ajax_fetch()
{
    ?>
    <script type="text/javascript">
        function fetch() {
            $("#result").html('');
            key = jQuery('#dsSoInput').val();
            if (!key) {
                $("#result").html('');
                return;
            }
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: {action: 'search_film', keyword: key, limit: 5},
                success: function (res) {
                    $("#result").html('');
                    let data = JSON.parse(res);
                    $.each(data, function (key, value) {
                        $('#result').append('<a href="' + value.slug + '"><div class="rowsearch"> <div class="column lefts"> <img src="' + value.image + '" width="50" /> </div> <div class="column rights"><p> ' + value.title + ' ' + '</p><p> ' + value.original_title + '| ' + value.year + ' </p></div> </div></a>')
                    });
                }
            });

        }

        document.body.addEventListener("click", function (event) {
            $("#result").html('');
        });
    </script>

    <?php
}

// Search and archive filters for ophim
function mySearchFilter($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return $query;
    }

    $is_ophim_context = $query->is_search() || $query->is_post_type_archive('ophim') || $query->get('post_type') === 'ophim';
    if (!$is_ophim_context) {
        return $query;
    }

    if ($query->is_search()) {
        $query->set('post_type', 'ophim');
    }

    $regions = sanitize_text_field(oIsset($_GET, 'regions'));
    $genres = sanitize_text_field(oIsset($_GET, 'genres'));
    $years = sanitize_text_field(oIsset($_GET, 'years'));
    $categories = sanitize_text_field(oIsset($_GET, 'categories'));

    $tax_query = $query->get('tax_query');
    if (!is_array($tax_query)) {
        $tax_query = [];
    }

    $append_tax = function ($taxonomy, $terms) use (&$tax_query) {
        if (!$terms) {
            return;
        }
        $term_list = array_values(array_filter(array_map('trim', explode(',', $terms))));
        if (empty($term_list)) {
            return;
        }
        $tax_query[] = [
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term_list,
        ];
    };

    $append_tax('ophim_categories', $categories);
    $append_tax('ophim_years', $years);
    $append_tax('ophim_genres', $genres);
    $append_tax('ophim_regions', $regions);

    if (!empty($tax_query)) {
        if (count($tax_query) > 1 && !isset($tax_query['relation'])) {
            $tax_query['relation'] = 'AND';
        }
        $query->set('tax_query', $tax_query);
    }

    return $query;
}

add_filter('pre_get_posts', 'mySearchFilter');
