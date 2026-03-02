<?php
require_once get_template_directory() . '/inc/netflix-ui.php';
mtyy_nfx_shared_assets();
get_header();
$template_args = get_query_var('args', []);
?>
<script>
    var element = document.querySelector("#nav");
    if (element) {
        element.classList.replace("head-c", "no-null");
    }
</script>
<?php
$title = isset($template_args['title']) ? $template_args['title'] : (is_search() ? ('Tim kiem: ' . get_search_query()) : single_tag_title('', false));
?>
<div class="nfx-page">
    <section class="nfx-container" style="padding-top:92px;">
        <h1 class="nfx-heading"><?php echo esc_html($title ?: 'Danh sach phim'); ?></h1>
        <p class="nfx-subheading"><?php echo esc_html($wp_query->found_posts ?? 0); ?> ket qua</p>

        <?php if (have_posts()): ?>
            <div class="nfx-grid">
                <?php while (have_posts()): the_post(); mtyy_nfx_card(get_the_ID()); endwhile; ?>
            </div>
            <div style="margin-top:20px;">
                <?php ophim_pagination(); ?>
            </div>
        <?php else: ?>
            <div class="nfx-panel">
                <p class="nfx-subheading" style="margin:0;">Khong tim thay du lieu phim.</p>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php get_footer(); ?>
