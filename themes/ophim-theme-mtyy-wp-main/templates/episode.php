<?php
require_once get_template_directory() . '/inc/netflix-ui.php';
mtyy_nfx_shared_assets();

$episode_m3u8 = m3u8EpisodeUrl();
$episode_embed = embedEpisodeUrl();
$episode_name = episodeName();
$poster_url = mtyy_nfx_movie_image(get_the_ID());
$watch_url = mtyy_nfx_watch_link(get_the_ID());
$all_servers = episodeList();
?>
<style>
    .nfx-watch-layout {
        padding-top: 88px;
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(320px, 1fr);
        gap: 16px;
    }
    .nfx-player { position: relative; border:1px solid var(--nfx-border); border-radius:16px; overflow:hidden; background:#080c14; aspect-ratio:16/9; }
    .nfx-player video,.nfx-player iframe{ width:100%; height:100%; border:0; display:block; background:#000; }
    .nfx-server-list { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
    .nfx-server-btn { border:1px solid rgba(255,255,255,.2); border-radius:10px; background:rgba(255,255,255,.09); color:#eaf0ff; font-weight:700; padding:8px 12px; cursor:pointer; }
    .nfx-server-btn.active { color:#fff; border-color: transparent; background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%); }
    .nfx-episode-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(96px, 1fr)); gap:8px; }
    .nfx-episode-link { display:inline-flex; align-items:center; justify-content:center; border-radius:10px; border:1px solid rgba(255,255,255,.16); padding:8px 10px; text-decoration:none; color:#edf3ff; background: rgba(255,255,255,.05); font-size:13px; font-weight:700; }
    .nfx-episode-link.current { border-color: transparent; background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%); }
    @media (max-width: 1080px) { .nfx-watch-layout { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .nfx-watch-layout { padding-top: 74px; } }
</style>

<div class="nfx-page">
    <section class="nfx-container nfx-watch-layout">
        <div>
            <h1 class="nfx-heading" style="margin-bottom:8px;"><?php the_title(); ?> - Tap <?php echo esc_html($episode_name); ?></h1>
            <p class="nfx-subheading" style="margin-bottom:12px;">
                <?php echo esc_html(op_get_year('')); ?> <?php if (op_get_quality()): ?>| <?php echo esc_html(op_get_quality()); ?><?php endif; ?>
                <?php if (op_get_lang()): ?>| <?php echo esc_html(op_get_lang()); ?><?php endif; ?>
            </p>
            <div class="nfx-player" id="nfx-player-wrapper"></div>
            <div class="nfx-server-list">
                <?php if ($episode_m3u8): ?><button class="nfx-server-btn active" data-type="m3u8" data-link="<?php echo esc_url($episode_m3u8); ?>">Nguon M3U8</button><?php endif; ?>
                <?php if ($episode_embed): ?><button class="nfx-server-btn <?php echo $episode_m3u8 ? '' : 'active'; ?>" data-type="embed" data-link="<?php echo esc_url($episode_embed); ?>">Nguon Embed</button><?php endif; ?>
                <button class="nfx-server-btn nfx-mylist-toggle"
                        data-id="<?php echo esc_attr(get_the_ID()); ?>"
                        data-title="<?php echo esc_attr(get_the_title()); ?>"
                        data-url="<?php echo esc_url(get_permalink()); ?>"
                        data-watch-url="<?php echo esc_url($watch_url); ?>"
                        data-image="<?php echo esc_url($poster_url); ?>"
                        data-year="<?php echo esc_attr(op_get_year('')); ?>"
                        data-quality="<?php echo esc_attr(op_get_quality()); ?>"
                        data-age="<?php echo esc_attr(op_get_is_copyright() ? '18+' : ''); ?>"
                        data-trailer-embed="<?php echo esc_url(mtyy_nfx_trailer_embed_url(get_the_ID())); ?>">My List</button>
            </div>
            <div class="nfx-panel" style="margin-top:14px;">
                <h3 style="margin:0 0 8px;">Noi dung</h3>
                <p class="nfx-subheading" style="margin:0;"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_content()), 70, '...')); ?></p>
            </div>
        </div>
        <div class="nfx-panel">
            <h3 style="margin:0 0 12px;">Danh sach tap</h3>
            <?php foreach ($all_servers as $server): ?>
                <p class="nfx-subheading" style="margin:0 0 8px;font-weight:700;"><?php echo esc_html($server['server_name']); ?></p>
                <div class="nfx-episode-grid" style="margin-bottom:14px;">
                    <?php foreach ($server['server_data'] as $episode): ?>
                        <a class="nfx-episode-link <?php echo ($episode == getEpisode()) ? 'current' : ''; ?>" href="<?php echo esc_url($episode['getUrl']); ?>">
                            <?php echo esc_html($episode['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="nfx-container nfx-section">
        <div class="nfx-panel">
            <div class="nfx-row-header"><h2 class="nfx-row-title">De xuat</h2></div>
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

<script src="<?= OFIM_PUBLIC_URL ?>/js/hls.min.js"></script>
<script>
    (function () {
        const wrapper = document.getElementById('nfx-player-wrapper');
        const serverButtons = document.querySelectorAll('.nfx-server-btn[data-type]');
        const watchRecord = {
            id: "<?php echo esc_js(get_the_ID()); ?>",
            title: "<?php echo esc_js(get_the_title()); ?> - Tap <?php echo esc_js($episode_name); ?>",
            url: "<?php echo esc_js(get_permalink()); ?>",
            watchUrl: "<?php echo esc_js($watch_url); ?>",
            image: "<?php echo esc_js($poster_url); ?>",
            year: "<?php echo esc_js(op_get_year('')); ?>",
            quality: "<?php echo esc_js(op_get_quality()); ?>",
            age: "<?php echo esc_js(op_get_is_copyright() ? '18+' : ''); ?>",
            episode: "Tap <?php echo esc_js($episode_name); ?>"
        };

        if (window.NFX) {
            window.NFX.saveContinue(watchRecord);
        }

        function renderPlayer(type, link) {
            if (!wrapper || !link) return;
            const safeLink = link.replace(/^http:\/\//i, 'https://');
            wrapper.innerHTML = '';
            if (type === 'embed') {
                const iframe = document.createElement('iframe');
                iframe.src = safeLink;
                iframe.allow = 'autoplay; fullscreen';
                iframe.setAttribute('allowfullscreen', 'allowfullscreen');
                wrapper.appendChild(iframe);
                return;
            }
            const video = document.createElement('video');
            video.controls = true;
            video.autoplay = true;
            video.playsInline = true;
            wrapper.appendChild(video);
            if (window.Hls && Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(safeLink);
                hls.attachMedia(video);
                video.addEventListener('timeupdate', function () {
                    if (!window.NFX) return;
                    window.NFX.saveContinue(Object.assign({}, watchRecord, {progress: Math.floor(video.currentTime || 0)}));
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = safeLink;
            }
        }

        serverButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                serverButtons.forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                renderPlayer(btn.dataset.type, btn.dataset.link);
            });
        });

        const active = document.querySelector('.nfx-server-btn.active[data-type]');
        if (active) renderPlayer(active.dataset.type, active.dataset.link);
    })();
</script>
