<?php
require_once get_template_directory() . '/inc/netflix-ui.php';
mtyy_nfx_shared_assets();

$poster_url = mtyy_nfx_movie_image(get_the_ID());
$watch_url = mtyy_nfx_watch_link(get_the_ID());
$trailer_embed = mtyy_nfx_trailer_embed_url(get_the_ID());
$episodes = function_exists('episodeList') ? episodeList() : [];

$meta_chips = array_filter([
    op_get_year(''),
    op_get_status(),
    op_get_quality(),
    op_get_lang(),
    op_get_episode(),
]);
?>
<style>
    .nfx-detail-hero {
        position: relative;
        min-height: 66vh;
        display: flex;
        align-items: flex-end;
        padding: 96px 5vw 34px;
        overflow: hidden;
    }
    .nfx-detail-bg { position: absolute; inset: 0; background: center/cover no-repeat; filter: brightness(.52) saturate(1.06); transform: scale(1.03); }
    .nfx-detail-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(7,9,14,.3) 0%, rgba(7,9,14,.96) 88%); }
    .nfx-detail-content { position: relative; z-index: 2; max-width: 760px; }
    .nfx-detail-title { margin: 0 0 10px; font-size: clamp(30px, 4.8vw, 56px); line-height: 1.08; color: #f6f8ff; }
    .nfx-chip-row { display:flex; flex-wrap:wrap; gap:8px; margin-bottom: 14px; }
    .nfx-chip { padding: 5px 10px; border-radius: 999px; background: rgba(255,255,255,.13); color:#ecf2ff; font-size:12px; font-weight:700; }
    .nfx-detail-desc { margin:0 0 18px; color:#dce5f6; font-size:16px; line-height:1.66; max-width: 68ch; }
    .nfx-btn-row { display:flex; flex-wrap:wrap; gap:10px; }
    .nfx-btn { border:0; border-radius:12px; padding:11px 18px; text-decoration:none; font-size:14px; font-weight:700; cursor:pointer; }
    .nfx-btn.primary { color:#fff; background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%); }
    .nfx-btn.soft { color:#edf2ff; background: rgba(255,255,255,.14); }
    .nfx-detail-body { margin-top: 14px; display:grid; gap:16px; }
    .nfx-episode-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap:8px; }
    .nfx-ep-link { border:1px solid rgba(255,255,255,.16); border-radius:10px; padding:8px 10px; text-decoration:none; color:#edf3ff; text-align:center; font-weight:600; font-size:13px; background: rgba(255,255,255,.06); }
    .nfx-ep-link:hover { border-color: rgba(242,56,90,.62); }
    @media (max-width:640px){ .nfx-detail-hero{ padding:74px 16px 24px; min-height:48vh; } }
</style>

<div class="nfx-page">
    <section class="nfx-detail-hero">
        <div class="nfx-detail-bg" style="background-image:url('<?php echo esc_url($poster_url); ?>')"></div>
        <div class="nfx-detail-overlay"></div>
        <div class="nfx-detail-content">
            <h1 class="nfx-detail-title"><?php the_title(); ?></h1>
            <div class="nfx-chip-row">
                <?php foreach ($meta_chips as $chip): ?><span class="nfx-chip"><?php echo esc_html($chip); ?></span><?php endforeach; ?>
                <?php if (op_get_is_copyright()): ?><span class="nfx-chip">18+</span><?php endif; ?>
            </div>
            <p class="nfx-detail-desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_content()), 45, '...')); ?></p>
            <div class="nfx-btn-row">
                <?php if ($watch_url): ?><a class="nfx-btn primary" href="<?php echo esc_url($watch_url); ?>">Xem phim</a><?php endif; ?>
                <button class="nfx-btn soft nfx-mylist-toggle"
                        data-id="<?php echo esc_attr(get_the_ID()); ?>"
                        data-title="<?php echo esc_attr(get_the_title()); ?>"
                        data-url="<?php echo esc_url(get_permalink()); ?>"
                        data-watch-url="<?php echo esc_url($watch_url); ?>"
                        data-image="<?php echo esc_url($poster_url); ?>"
                        data-year="<?php echo esc_attr(op_get_year('')); ?>"
                        data-quality="<?php echo esc_attr(op_get_quality()); ?>"
                        data-age="<?php echo esc_attr(op_get_is_copyright() ? '18+' : ''); ?>"
                        data-trailer-embed="<?php echo esc_url($trailer_embed); ?>">My List</button>
                <?php if ($trailer_embed): ?><a class="nfx-btn soft fancybox fancybox.iframe" href="<?php echo esc_url($trailer_embed); ?>">Trailer</a><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="nfx-container nfx-detail-body">
        <div class="nfx-panel">
            <h3 style="margin:0 0 12px;">Thong tin phim</h3>
            <p class="nfx-subheading" style="margin:0;"><?php the_content(); ?></p>
            <p class="nfx-subheading" style="margin-top:10px;"><strong>Dao dien:</strong> <?php echo op_get_directors(15, ', '); ?></p>
            <p class="nfx-subheading" style="margin-top:4px;"><strong>Dien vien:</strong> <?php echo op_get_actors(20, ', '); ?></p>
            <p class="nfx-subheading" style="margin-top:4px;"><strong>The loai:</strong> <?php echo op_get_genres(', '); ?></p>
            <p class="nfx-subheading" style="margin-top:4px;"><strong>Quoc gia:</strong> <?php echo op_get_regions(', '); ?></p>
        </div>

        <?php if (!empty($episodes)): ?>
            <div class="nfx-panel">
                <h3 style="margin:0 0 12px;">Danh sach tap</h3>
                <?php foreach ($episodes as $server): ?>
                    <p class="nfx-subheading" style="margin:0 0 8px;font-weight:700;"><?php echo esc_html($server['server_name']); ?></p>
                    <div class="nfx-episode-grid" style="margin-bottom:14px;">
                        <?php foreach ($server['server_data'] as $episode): ?>
                            <a class="nfx-ep-link" href="<?php echo esc_url($episode['getUrl']); ?>"><?php echo esc_html($episode['name']); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="nfx-panel">
            <div class="nfx-row-header"><h3 style="margin:0;">De xuat</h3></div>
            <?php
            $related = new WP_Query([
                'post_type' => 'ophim',
                'post__not_in' => [get_the_ID()],
                'posts_per_page' => 18,
                'orderby' => 'rand',
            ]);
            ?>
            <?php if ($related->have_posts()): ?>
                <div class="nfx-row-track">
                    <?php while ($related->have_posts()): $related->the_post(); mtyy_nfx_card(get_the_ID()); endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else: ?>
                <p class="nfx-subheading" style="margin:0;">Chua co phim de xuat.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php add_action('wp_footer', function () { ?>
    <script src="<?= get_template_directory_uri() ?>/assets/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <link rel="stylesheet" type="text/css"
          href="<?= get_template_directory_uri() ?>/assets/source/jquery.fancybox.css?v=2.1.5" media="screen"/>
    <script>
        jQuery(function ($) {
            $(".fancybox").fancybox({
                maxWidth: 1024,
                maxHeight: 720,
                fitToView: false,
                width: '80%',
                height: '80%',
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none'
            });
        });
    </script>
<?php }); ?>
