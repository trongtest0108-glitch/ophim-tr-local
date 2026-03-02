<?php

if (!function_exists('mtyy_nfx_normalize_image')) {
    function mtyy_nfx_normalize_image($url)
    {
        if (!$url) {
            return '';
        }
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            return $url;
        }
        return home_url($url);
    }
}

if (!function_exists('mtyy_nfx_movie_image')) {
    function mtyy_nfx_movie_image($post_id)
    {
        $poster = get_post_meta($post_id, 'ophim_poster_url', true);
        $thumb = get_post_meta($post_id, 'ophim_thumb_url', true);
        return mtyy_nfx_normalize_image($poster ?: $thumb);
    }
}

if (!function_exists('mtyy_nfx_watch_link')) {
    function mtyy_nfx_watch_link($post_id)
    {
        if (function_exists('watchUrl')) {
            $old_post = $GLOBALS['post'] ?? null;
            $GLOBALS['post'] = get_post($post_id);
            setup_postdata($GLOBALS['post']);
            $url = watchUrl();
            wp_reset_postdata();
            if ($old_post instanceof WP_Post) {
                $GLOBALS['post'] = $old_post;
            }
            if ($url) {
                return $url;
            }
        }
        return get_permalink($post_id);
    }
}

if (!function_exists('mtyy_nfx_trailer_embed_url')) {
    function mtyy_nfx_trailer_embed_url($post_id)
    {
        $url = get_post_meta($post_id, 'ophim_trailer_url', true);
        if (!$url) {
            return '';
        }
        parse_str(parse_url($url, PHP_URL_QUERY), $q);
        if (!empty($q['v'])) {
            return 'https://www.youtube.com/embed/' . $q['v'] . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $q['v'];
        }
        return '';
    }
}

if (!function_exists('mtyy_nfx_shared_assets')) {
    function mtyy_nfx_shared_assets()
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            :root {
                --nfx-bg: #070b13;
                --nfx-bg-soft: #0f1622;
                --nfx-panel: rgba(14, 20, 30, .92);
                --nfx-text: #e8eefc;
                --nfx-text-soft: #c6d3eb;
                --nfx-accent-1: #f2385a;
                --nfx-accent-2: #fd6f54;
                --nfx-border: rgba(255, 255, 255, .1);
            }

            .nfx-page {
                background: radial-gradient(circle at 16% 20%, #1a2435 0%, #090d15 46%, #06080e 100%);
                color: var(--nfx-text);
                min-height: 100vh;
                padding-bottom: 48px;
            }

            .nfx-container {
                padding: 0 5vw;
            }

            .nfx-heading {
                margin: 0 0 12px;
                font-size: clamp(22px, 2.2vw, 34px);
                color: #f4f8ff;
                letter-spacing: .01em;
            }

            .nfx-subheading {
                margin: 0 0 14px;
                color: var(--nfx-text-soft);
                font-size: 14px;
            }

            .nfx-section {
                margin-top: 20px;
            }

            .nfx-row-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }

            .nfx-row-title {
                margin: 0;
                font-size: 22px;
                color: #f0f5ff;
            }

            .nfx-row-track {
                display: grid;
                grid-auto-flow: column;
                grid-auto-columns: minmax(170px, 1fr);
                gap: 12px;
                overflow-x: auto;
                padding: 6px 2px 10px;
                scroll-snap-type: x mandatory;
            }

            .nfx-row-track::-webkit-scrollbar {
                height: 8px;
            }

            .nfx-row-track::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, .2);
                border-radius: 999px;
            }

            .nfx-card {
                position: relative;
                border-radius: 14px;
                overflow: hidden;
                border: 1px solid var(--nfx-border);
                background: #121927;
                text-decoration: none;
                color: inherit;
                scroll-snap-align: start;
                transition: transform .22s ease, border-color .2s ease, box-shadow .25s ease;
                min-height: 255px;
            }

            .nfx-card:hover,
            .nfx-card:focus-visible {
                transform: translateY(-4px) scale(1.02);
                border-color: rgba(242, 56, 90, .65);
                box-shadow: 0 16px 34px rgba(0, 0, 0, .42);
                outline: none;
            }

            .nfx-card img {
                width: 100%;
                aspect-ratio: 2/3;
                object-fit: cover;
                display: block;
                background: #0f1520;
            }

            .nfx-card-overlay {
                position: absolute;
                inset: auto 0 0;
                background: linear-gradient(180deg, transparent 0%, rgba(4, 7, 12, .96) 78%);
                padding: 45px 10px 10px;
            }

            .nfx-card-title {
                margin: 0;
                font-size: 13px;
                line-height: 1.45;
                color: #f3f7ff;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .nfx-card-meta {
                margin-top: 6px;
                font-size: 12px;
                color: #c2cde3;
            }

            .nfx-card-badges {
                position: absolute;
                top: 8px;
                left: 8px;
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
                z-index: 2;
            }

            .nfx-badge {
                padding: 3px 8px;
                border-radius: 999px;
                font-size: 11px;
                font-weight: 700;
                color: #fff;
                background: rgba(8, 15, 24, .72);
                border: 1px solid rgba(255, 255, 255, .22);
            }

            .nfx-badge.age {
                background: rgba(242, 56, 90, .92);
                border-color: transparent;
            }

            .nfx-card-actions {
                position: absolute;
                top: 8px;
                right: 8px;
                z-index: 3;
                display: flex;
                gap: 6px;
            }

            .nfx-card-btn {
                border: 1px solid rgba(255, 255, 255, .3);
                background: rgba(7, 13, 22, .72);
                color: #fff;
                width: 28px;
                height: 28px;
                border-radius: 999px;
                cursor: pointer;
                font-size: 14px;
                line-height: 1;
                padding: 0;
            }

            .nfx-card-btn.active {
                background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%);
                border-color: transparent;
            }

            .nfx-preview {
                position: absolute;
                inset: 0;
                display: none;
                z-index: 1;
                background: #000;
            }

            .nfx-preview iframe {
                width: 100%;
                height: 100%;
                border: 0;
            }

            .nfx-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 14px;
            }

            .nfx-panel {
                border: 1px solid var(--nfx-border);
                border-radius: 16px;
                background: var(--nfx-panel);
                padding: 16px;
            }

            .nfx-filter-form {
                display: grid;
                grid-template-columns: 1fr 1fr auto auto;
                gap: 10px;
                align-items: end;
                margin-bottom: 14px;
            }

            .nfx-filter-group {
                display: grid;
                gap: 6px;
            }

            .nfx-filter-label {
                font-size: 12px;
                color: #9fb0cf;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .04em;
            }

            .nfx-filter-input {
                height: 42px;
                border-radius: 10px;
                border: 1px solid rgba(255, 255, 255, .16);
                background: rgba(255, 255, 255, .08);
                color: #eef4ff;
                padding: 0 12px;
                font-size: 14px;
            }

            .nfx-filter-btn {
                height: 42px;
                border-radius: 10px;
                border: 0;
                padding: 0 14px;
                cursor: pointer;
                font-size: 13px;
                font-weight: 700;
            }

            .nfx-filter-btn.apply {
                color: #fff;
                background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%);
            }

            .nfx-filter-btn.clear {
                color: #e4ebfa;
                background: rgba(255, 255, 255, .14);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .nfx-search-launch {
                position: fixed;
                right: 16px;
                bottom: 16px;
                width: 48px;
                height: 48px;
                border-radius: 999px;
                border: 0;
                color: #fff;
                background: linear-gradient(135deg, var(--nfx-accent-1) 0%, var(--nfx-accent-2) 100%);
                box-shadow: 0 12px 28px rgba(0, 0, 0, .32);
                z-index: 10001;
                cursor: pointer;
                font-size: 18px;
            }

            .nfx-search-overlay {
                position: fixed;
                inset: 0;
                z-index: 10002;
                display: none;
                background: rgba(4, 7, 12, .86);
                backdrop-filter: blur(4px);
                padding: 24px 16px;
            }

            .nfx-search-modal {
                width: min(760px, 100%);
                margin: 0 auto;
                background: #0d1422;
                border: 1px solid rgba(255, 255, 255, .14);
                border-radius: 16px;
                padding: 14px;
            }

            .nfx-search-input {
                width: 100%;
                height: 46px;
                border-radius: 10px;
                border: 1px solid rgba(255, 255, 255, .18);
                background: rgba(255, 255, 255, .08);
                color: #eef4ff;
                padding: 0 14px;
                font-size: 15px;
            }

            .nfx-search-results {
                margin-top: 10px;
                max-height: 62vh;
                overflow: auto;
                display: grid;
                gap: 8px;
            }

            .nfx-search-item {
                display: grid;
                grid-template-columns: 64px 1fr;
                gap: 10px;
                padding: 8px;
                border-radius: 10px;
                text-decoration: none;
                color: #e9f0ff;
                background: rgba(255, 255, 255, .05);
            }

            .nfx-search-item img {
                width: 64px;
                aspect-ratio: 2/3;
                object-fit: cover;
                border-radius: 8px;
            }

            @media (max-width: 640px) {
                .nfx-container {
                    padding: 0 16px;
                }

                .nfx-row-track {
                    grid-auto-columns: minmax(138px, 42vw);
                }

                .nfx-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 10px;
                }

                .nfx-filter-form {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <?php
        $filter_regions = get_terms([
            'taxonomy' => 'ophim_regions',
            'hide_empty' => true,
            'number' => 0
        ]);
        $filter_years = get_terms([
            'taxonomy' => 'ophim_years',
            'hide_empty' => true,
            'number' => 0,
            'orderby' => 'name',
            'order' => 'DESC'
        ]);
        $filter_genres = get_terms([
            'taxonomy' => 'ophim_genres',
            'hide_empty' => true,
            'number' => 0
        ]);
        $filter_categories = get_terms([
            'taxonomy' => 'ophim_categories',
            'hide_empty' => true,
            'number' => 0
        ]);
        $filter_action = get_post_type_archive_link('ophim') ?: home_url('/');
        ?>

        <button class="nfx-search-launch" id="nfx-search-launch" type="button" aria-label="Search">🔎</button>
        <div class="nfx-search-overlay" id="nfx-search-overlay">
            <div class="nfx-search-modal">
                <div style="display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;">
                    <input type="text" class="nfx-search-input" id="nfx-search-input" placeholder="Tim phim...">
                    <button type="button" class="nfx-filter-btn clear" id="nfx-toggle-advanced-filter">Bo loc</button>
                </div>
                <form class="nfx-panel nfx-filter-form" id="nfx-advanced-filter" method="get"
                      action="<?php echo esc_url($filter_action); ?>"
                      style="margin-top:10px;display:none;">
                    <div class="nfx-filter-group">
                        <label class="nfx-filter-label" for="nfx-filter-regions">Quoc gia</label>
                        <select class="nfx-filter-input" id="nfx-filter-regions" name="regions">
                            <option value="">Tat ca quoc gia</option>
                            <?php foreach ($filter_regions as $region): ?>
                                <option value="<?php echo esc_attr($region->slug); ?>"><?php echo esc_html($region->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="nfx-filter-group">
                        <label class="nfx-filter-label" for="nfx-filter-years">Nam</label>
                        <select class="nfx-filter-input" id="nfx-filter-years" name="years">
                            <option value="">Tat ca nam</option>
                            <?php foreach ($filter_years as $year): ?>
                                <option value="<?php echo esc_attr($year->slug); ?>"><?php echo esc_html($year->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="nfx-filter-group">
                        <label class="nfx-filter-label" for="nfx-filter-genres">The loai</label>
                        <select class="nfx-filter-input" id="nfx-filter-genres" name="genres">
                            <option value="">Tat ca the loai</option>
                            <?php foreach ($filter_genres as $genre): ?>
                                <option value="<?php echo esc_attr($genre->slug); ?>"><?php echo esc_html($genre->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="nfx-filter-group">
                        <label class="nfx-filter-label" for="nfx-filter-categories">Danh muc</label>
                        <select class="nfx-filter-input" id="nfx-filter-categories" name="categories">
                            <option value="">Tat ca danh muc</option>
                            <?php foreach ($filter_categories as $category): ?>
                                <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="nfx-filter-btn apply" type="submit">Loc phim</button>
                    <a class="nfx-filter-btn clear" href="<?php echo esc_url($filter_action); ?>">Xoa loc</a>
                </form>
                <div class="nfx-search-results" id="nfx-search-results"></div>
            </div>
        </div>

        <script>
            (function () {
                if (window.NFX) return;
                const KEY_MYLIST = "nfx_mylist";
                const KEY_CONTINUE = "nfx_continue";
                const ajaxEndpoint = "<?php echo esc_js(admin_url('admin-ajax.php')); ?>";

                const NFX = {
                    read(key) {
                        try {
                            return JSON.parse(localStorage.getItem(key) || "[]");
                        } catch (e) {
                            return [];
                        }
                    },
                    write(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },
                    upsert(list, item, key = "id") {
                        const idx = list.findIndex((x) => String(x[key]) === String(item[key]));
                        if (idx >= 0) list[idx] = Object.assign({}, list[idx], item);
                        else list.unshift(item);
                        return list;
                    },
                    toggleMyList(item) {
                        const list = NFX.read(KEY_MYLIST);
                        const idx = list.findIndex((x) => String(x.id) === String(item.id));
                        if (idx >= 0) list.splice(idx, 1);
                        else list.unshift(item);
                        NFX.write(KEY_MYLIST, list);
                        NFX.paintMyListButtons();
                        NFX.renderRows();
                    },
                    isInMyList(id) {
                        return NFX.read(KEY_MYLIST).some((x) => String(x.id) === String(id));
                    },
                    saveContinue(item) {
                        const list = NFX.read(KEY_CONTINUE);
                        const next = NFX.upsert(list, item, "id").slice(0, 30);
                        NFX.write(KEY_CONTINUE, next);
                        NFX.renderRows();
                    },
                    paintMyListButtons() {
                        document.querySelectorAll(".nfx-mylist-toggle").forEach((btn) => {
                            const id = btn.getAttribute("data-id");
                            btn.classList.toggle("active", NFX.isInMyList(id));
                            btn.setAttribute("title", NFX.isInMyList(id) ? "Remove from My List" : "Add to My List");
                        });
                    },
                    cardHTML(item) {
                        const badges = [];
                        if (item.year) badges.push(`<span class="nfx-badge">${item.year}</span>`);
                        if (item.age) badges.push(`<span class="nfx-badge age">${item.age}</span>`);
                        if (item.quality) badges.push(`<span class="nfx-badge">${item.quality}</span>`);
                        const trailerAttr = item.trailerEmbed ? ` data-trailer-embed="${item.trailerEmbed}"` : "";
                        return `
                            <a class="nfx-card nfx-dyn-card" href="${item.watchUrl || item.url}"${trailerAttr} data-id="${item.id}">
                                <div class="nfx-card-badges">${badges.join("")}</div>
                                <div class="nfx-card-actions">
                                    <button type="button" class="nfx-card-btn nfx-mylist-toggle" data-id="${item.id}" data-title="${item.title || ""}" data-url="${item.url || "#"}" data-watch-url="${item.watchUrl || item.url || "#"}" data-image="${item.image || ""}" data-year="${item.year || ""}" data-quality="${item.quality || ""}" data-age="${item.age || ""}" data-trailer-embed="${item.trailerEmbed || ""}">+</button>
                                </div>
                                <img src="${item.image || ""}" alt="${item.title || ""}" loading="lazy">
                                <div class="nfx-preview"></div>
                                <div class="nfx-card-overlay">
                                    <p class="nfx-card-title">${item.title || ""}</p>
                                    <div class="nfx-card-meta">${item.episode || ""}</div>
                                </div>
                            </a>
                        `;
                    },
                    renderRows() {
                        const continueBox = document.getElementById("nfx-continue-track");
                        const continueSection = document.getElementById("nfx-continue-section");
                        const myListBox = document.getElementById("nfx-mylist-track");
                        const myListSection = document.getElementById("nfx-mylist-section");
                        if (continueBox) {
                            const list = NFX.read(KEY_CONTINUE).slice(0, 20);
                            continueBox.innerHTML = list.length ? list.map(NFX.cardHTML).join("") : ``;
                            if (continueSection) {
                                continueSection.style.display = list.length ? "" : "none";
                            }
                        }
                        if (myListBox) {
                            const list = NFX.read(KEY_MYLIST).slice(0, 20);
                            myListBox.innerHTML = list.length ? list.map(NFX.cardHTML).join("") : ``;
                            if (myListSection) {
                                myListSection.style.display = list.length ? "" : "none";
                            }
                        }
                        NFX.paintMyListButtons();
                        NFX.bindTrailerPreview();
                    },
                    bindTrailerPreview() {
                        if (window.matchMedia("(hover: none)").matches) return;
                        document.querySelectorAll(".nfx-card[data-trailer-embed]").forEach((card) => {
                            if (card.dataset.previewBound === "1") return;
                            card.dataset.previewBound = "1";
                            let mounted = false;
                            const preview = card.querySelector(".nfx-preview");
                            const url = card.getAttribute("data-trailer-embed");
                            if (!preview || !url) return;
                            card.addEventListener("mouseenter", () => {
                                if (!mounted) {
                                    preview.innerHTML = `<iframe src="${url}" allow="autoplay; encrypted-media"></iframe>`;
                                    mounted = true;
                                }
                                preview.style.display = "block";
                            });
                            card.addEventListener("mouseleave", () => {
                                preview.style.display = "none";
                            });
                        });
                    },
                    bindMyListButtons() {
                        document.body.addEventListener("click", (e) => {
                            const btn = e.target.closest(".nfx-mylist-toggle");
                            if (!btn) return;
                            e.preventDefault();
                            e.stopPropagation();
                            NFX.toggleMyList({
                                id: btn.getAttribute("data-id"),
                                title: btn.getAttribute("data-title"),
                                url: btn.getAttribute("data-url"),
                                watchUrl: btn.getAttribute("data-watch-url"),
                                image: btn.getAttribute("data-image"),
                                year: btn.getAttribute("data-year"),
                                quality: btn.getAttribute("data-quality"),
                                age: btn.getAttribute("data-age"),
                                trailerEmbed: btn.getAttribute("data-trailer-embed")
                            });
                        });
                    },
                    bindSearchOverlay() {
                        const launch = document.getElementById("nfx-search-launch");
                        const overlay = document.getElementById("nfx-search-overlay");
                        const input = document.getElementById("nfx-search-input");
                        const results = document.getElementById("nfx-search-results");
                        const advancedFilter = document.getElementById("nfx-advanced-filter");
                        const advancedFilterToggle = document.getElementById("nfx-toggle-advanced-filter");
                        const legacySearchButton = document.getElementById("dsSo");
                        const legacySearchInput = document.getElementById("dsSoInput");
                        if (!launch || !overlay || !input || !results) return;
                        let timer = null;

                        const open = () => {
                            overlay.style.display = "block";
                            setTimeout(() => input.focus(), 20);
                        };
                        const close = () => {
                            overlay.style.display = "none";
                        };
                        launch.addEventListener("click", (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                            open();
                        });
                        if (legacySearchButton) {
                            legacySearchButton.addEventListener("click", (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                                open();
                            }, true);
                            const legacySearchForm = legacySearchButton.closest("form");
                            if (legacySearchForm) {
                                legacySearchForm.addEventListener("submit", (e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                                    open();
                                }, true);
                            }
                        }
                        if (legacySearchInput) {
                            legacySearchInput.addEventListener("keydown", (e) => {
                                if (e.key === "Enter") {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                                    open();
                                }
                            }, true);
                        }
                        if (advancedFilter && advancedFilterToggle) {
                            advancedFilterToggle.addEventListener("click", () => {
                                const isOpen = advancedFilter.style.display !== "none";
                                advancedFilter.style.display = isOpen ? "none" : "grid";
                            });
                        }
                        overlay.addEventListener("click", (e) => {
                            if (e.target === overlay) close();
                        });
                        document.addEventListener("keydown", (e) => {
                            if (e.key === "Escape") close();
                            if (e.key === "/" && !/input|textarea/i.test((document.activeElement || {}).tagName || "")) {
                                e.preventDefault();
                                open();
                            }
                        });
                        input.addEventListener("input", () => {
                            const keyword = input.value.trim();
                            clearTimeout(timer);
                            if (!keyword) {
                                results.innerHTML = "";
                                return;
                            }
                            timer = setTimeout(() => {
                                fetch(ajaxEndpoint, {
                                    method: "POST",
                                    headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"},
                                    body: new URLSearchParams({action: "search_film", keyword, limit: 10}).toString()
                                }).then((r) => r.text()).then((text) => {
                                    let data = [];
                                    try {
                                        data = JSON.parse(text);
                                    } catch (e) {
                                        data = [];
                                    }
                                    results.innerHTML = (data || []).map((item) => `
                                        <a class="nfx-search-item" href="${item.slug}">
                                            <img src="${item.image || item.image_poster || ""}" alt="${item.title || ""}">
                                            <div>
                                                <div style="font-weight:700;">${item.title || ""}</div>
                                                <div style="font-size:12px;color:#bdc8df;">${item.original_title || ""} ${item.year ? "| " + item.year : ""}</div>
                                            </div>
                                        </a>
                                    `).join("");
                                });
                            }, 220);
                        });
                    },
                    init() {
                        NFX.bindMyListButtons();
                        NFX.bindSearchOverlay();
                        NFX.paintMyListButtons();
                        NFX.renderRows();
                        NFX.bindTrailerPreview();
                    }
                };

                window.NFX = NFX;
                document.addEventListener("DOMContentLoaded", NFX.init);
            })();
        </script>
        <?php
    }
}

if (!function_exists('mtyy_nfx_card')) {
    function mtyy_nfx_card($post_id, $custom_class = '')
    {
        $title = get_the_title($post_id);
        $url = get_permalink($post_id);
        $watch_url = mtyy_nfx_watch_link($post_id);
        $image = mtyy_nfx_movie_image($post_id);
        $year = get_post_meta($post_id, 'ophim_year', true);
        $quality = get_post_meta($post_id, 'ophim_quality', true);
        $episode = get_post_meta($post_id, 'ophim_episode', true);
        $age = get_post_meta($post_id, 'ophim_is_copyright', true) ? '18+' : '';
        $trailer_embed = mtyy_nfx_trailer_embed_url($post_id);
        ?>
        <a class="nfx-card <?php echo esc_attr($custom_class); ?>" href="<?php echo esc_url($watch_url); ?>"
           <?php if ($trailer_embed): ?>data-trailer-embed="<?php echo esc_url($trailer_embed); ?>"<?php endif; ?>
           data-id="<?php echo esc_attr($post_id); ?>">
            <div class="nfx-card-badges">
                <?php if ($year): ?><span class="nfx-badge"><?php echo esc_html($year); ?></span><?php endif; ?>
                <?php if ($age): ?><span class="nfx-badge age"><?php echo esc_html($age); ?></span><?php endif; ?>
                <?php if ($quality): ?><span class="nfx-badge"><?php echo esc_html($quality); ?></span><?php endif; ?>
            </div>
            <div class="nfx-card-actions">
                <button type="button" class="nfx-card-btn nfx-mylist-toggle"
                        data-id="<?php echo esc_attr($post_id); ?>"
                        data-title="<?php echo esc_attr($title); ?>"
                        data-url="<?php echo esc_url($url); ?>"
                        data-watch-url="<?php echo esc_url($watch_url); ?>"
                        data-image="<?php echo esc_url($image); ?>"
                        data-year="<?php echo esc_attr($year); ?>"
                        data-quality="<?php echo esc_attr($quality); ?>"
                        data-age="<?php echo esc_attr($age); ?>"
                        data-trailer-embed="<?php echo esc_url($trailer_embed); ?>">+</button>
            </div>
            <img loading="lazy" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
            <div class="nfx-preview"></div>
            <div class="nfx-card-overlay">
                <p class="nfx-card-title"><?php echo esc_html($title); ?></p>
                <div class="nfx-card-meta"><?php echo esc_html($episode ?: 'Dang cap nhat'); ?></div>
            </div>
        </a>
        <?php
    }
}
