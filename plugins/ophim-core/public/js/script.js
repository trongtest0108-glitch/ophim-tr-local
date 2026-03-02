jQuery(function($){
    $(document).ready(function() {
        $("[id*=_media_audio-]").remove();
        $("[id*=_recent-posts-]").remove();
        $("[id*=_recent-comments-]").remove();
        $("[id*=_categories-__i__]").remove();
        $("[id*=_rss-__i__]").remove();
        $("[id*=_media_gallery-__i__]").remove();
        $("[id*=_custom_html-__i__]").remove();
        $("[id*=_archives-__i__]").remove();
        $("[id*=_calendar-__i__]").remove();
        $("[id*=_nav_menu-__i__]").remove();
        $("[id*=_meta-__i__]").remove();
        $("[id*=_tag_cloud-__i__]").remove();
        $("[id*=_pages-__i__]").remove();
        $("[id*=_search-__i__]").remove();
        $("[id*=_media_video-]").remove();
        $("[id*=_text-__i__]").remove();
        $("[id*=_media_image-]").remove();
    });
    /*
     * Select/Upload image(s) event
     */
    $('body').on('click', '.ophim_upload_image_button', function(e){
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                document.getElementById("poster").value = attachment.url.replace(window.location.origin, "");
                document.getElementById("imgPoster").src = attachment.url.replace(window.location.origin, "");
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
            })
                .open();
    });
    $('body').on('click', '.ophim_upload_image_thumb_url', function(e){
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                document.getElementById("thumb").value = attachment.url.replace(window.location.origin, "");
                document.getElementById("thumb_url").src = attachment.url.replace(window.location.origin, "");
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
            })
                .open();
    });
    $('body').on('click', '.ophim_upload_image_logo_jwplayer', function(e){
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                document.getElementById("logo_jwplayer").value = attachment.url.replace(window.location.origin, "");
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
            })
                .open();
    });

});

jQuery(function ($) {
    const toIntOrDefault = (value, defaultValue) => {
        const n = parseInt(value, 10);
        return Number.isFinite(n) ? n : defaultValue;
    };
    const parseArrayStorage = (key) => {
        try {
            const raw = localStorage.getItem(key);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            localStorage.removeItem(key);
            return [];
        }
    };
    const dedupeLinks = (links) => {
        const seen = new Set();
        const out = [];
        links.forEach((item) => {
            const v = item.trim();
            if (!v || seen.has(v)) return;
            seen.add(v);
            out.push(v);
        });
        return out;
    };
    var filterType = parseArrayStorage("filterType");
    var filterCategory = parseArrayStorage("filterCategory");
    var filterCountry = parseArrayStorage("filterCountry");
    var filterYear = parseArrayStorage("filterYear");

    var page_from = toIntOrDefault(localStorage.getItem("page_from"), 10);
    var page_to = toIntOrDefault(localStorage.getItem("page_to"), 1);
    $("input[name=page_from]").val(page_from);
    $("input[name=page_to]").val(page_to);

    var timeout_from = toIntOrDefault(localStorage.getItem("timeout_from"), 1000);
    var timeout_to = toIntOrDefault(localStorage.getItem("timeout_to"), 3000);
    $("input[name=timeout_from]").val(timeout_from);
    $("input[name=timeout_to]").val(timeout_to);
    var crawlConcurrency = toIntOrDefault(localStorage.getItem("crawl_concurrency"), 1);
    if (crawlConcurrency < 1) crawlConcurrency = 1;
    if (crawlConcurrency > 10) crawlConcurrency = 10;
    $("input[name=crawl_concurrency]").val(crawlConcurrency);
    $("input[name='crawl_fast_mode']").prop("checked", localStorage.getItem("crawl_fast_mode") === "1");

    $("input[name='filter_type[]']").each(function () {
        if (filterType.includes($(this).val())) {
            $(this).attr("checked", true);
        }
    });
    $("input[name='filter_category[]']").each(function () {
        if (filterCategory.includes($(this).val())) {
            $(this).attr("checked", true);
        }
    });
    $("input[name='filter_country[]']").each(function () {
        if (filterCountry.includes($(this).val())) {
            $(this).attr("checked", true);
        }
    });
    const getFilterYear = () => {
        let years = [];
        $("input[name='filter_year[]']:checked").each(function () {
            years.push($(this).val());
        });
        return years;
    };
    $("input[name='filter_year[]']").each(function () {
        if (filterYear.includes($(this).val())) {
            $(this).attr("checked", true);
        }
    });

    const buttonGetListMovies = $("div#get_list_movies");
    const inputPageFrom = $("input[name=page_from]");
    const inputPageTo = $("input[name=page_to]");
    const divMsg = $("div#msg");
    const divMsgText = $("p#msg_text");
    const divCrawlStats = $("div#crawl_stats");
    const divCrawlStatsText = $("p#crawl_stats_text");
    const textArealistMovies = $("textarea#result_list_movies");
    const divQueueInfo = $("div#crawl_queue_info");
    const divQueueText = $("p#crawl_queue_text");
    const divDashboard = $("div#crawl_dashboard");
    const dashSuccess = $("span#dash_success");
    const dashFailed = $("span#dash_failed");
    const dashFiltered = $("span#dash_filtered");
    const buttonCrawlMovies = $("div#crawl_movies");
    const buttonRollMovies = $("div#roll_movies");
    const divMsgCrawlSuccess = $("div#result_success");
    const divMsgCrawlError = $("div#result_error");
    const textAreaResultSuccess = $("textarea#list_crawl_success");
    const textAreaResultError = $("textarea#list_crawl_error");
    let crawlStats = {
        total: 0,
        done: 0,
        success: 0,
        error: 0,
        filtered: 0
    };
    let crawlTiming = {
        startedAt: 0,
        totalMs: 0,
        minMs: null,
        maxMs: 0,
        measured: 0
    };
    let crawlQueue = [];
    let activeWorkers = 0;
    const formatDuration = (ms) => {
        if (!Number.isFinite(ms) || ms < 0) ms = 0;
        if (ms < 1000) return `${Math.round(ms)}ms`;
        const sec = ms / 1000;
        if (sec < 60) return `${sec.toFixed(2)}s`;
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return `${m}m ${s}s`;
    };
    const recordCrawlDuration = (ms) => {
        if (!Number.isFinite(ms) || ms < 0) return;
        crawlTiming.totalMs += ms;
        crawlTiming.measured++;
        if (crawlTiming.minMs === null || ms < crawlTiming.minMs) crawlTiming.minMs = ms;
        if (ms > crawlTiming.maxMs) crawlTiming.maxMs = ms;
    };
    const updateCrawlStatsText = () => {
        const remain = Math.max(crawlStats.total - crawlStats.done, 0);
        const avgMs = crawlTiming.measured > 0 ? (crawlTiming.totalMs / crawlTiming.measured) : 0;
        const elapsedMs = crawlTiming.startedAt > 0 ? (Date.now() - crawlTiming.startedAt) : 0;
        const minText = crawlTiming.minMs === null ? "-" : formatDuration(crawlTiming.minMs);
        const statsText = `Tổng link: ${crawlStats.total} | Đã crawl: ${crawlStats.done} | Thành công: ${crawlStats.success} | Lỗi: ${crawlStats.error} | Bỏ qua: ${crawlStats.filtered} | Còn lại: ${remain} | Min: ${minText} | TB: ${formatDuration(avgMs)} | Max: ${formatDuration(crawlTiming.maxMs)} | Tổng TG xử lý: ${formatDuration(crawlTiming.totalMs)} | TG đã chạy: ${formatDuration(elapsedMs)}`;
        divMsgText.html(statsText);
        divCrawlStatsText.html(statsText);
    };
    const updateQueueInfo = () => {
        const list = textArealistMovies.val().split("\n").map((item) => item.trim()).filter((item) => item !== "");
        divQueueInfo.show(200);
        divQueueText.html(`Còn lại: ${list.length} link`);
    };
    const updateDashboard = () => {
        dashSuccess.text(crawlStats.success);
        dashFailed.text(crawlStats.error);
        dashFiltered.text(crawlStats.filtered);
        divDashboard.show(200);
    };

    buttonRollMovies.on("click", () => {
        var listLink = textArealistMovies.val();
        listLink = listLink.split("\n");
        listLink.sort(() => Math.random() - 0.5);
        listLink = listLink.join("\n");
        textArealistMovies.val(listLink);
        updateQueueInfo();
    });

    buttonGetListMovies.on("click", () => {
        divMsg.show(300);
        textArealistMovies.show(300);
        let fromPage = toIntOrDefault(inputPageFrom.val(), page_from);
        let toPage = toIntOrDefault(inputPageTo.val(), page_to);
        if (fromPage < toPage) {
            const tmp = fromPage;
            fromPage = toPage;
            toPage = tmp;
        }
        inputPageFrom.val(fromPage);
        inputPageTo.val(toPage);
        localStorage.setItem("page_from", fromPage.toString());
        localStorage.setItem("page_to", toPage.toString());
        crawl_page_callback(fromPage);
    });
    const crawl_page_callback = (currentPage) => {
        var url_api = $("#url_api").val();
        const toPage = toIntOrDefault(inputPageTo.val(), 1);
        if (!Number.isFinite(currentPage)) {
            divMsgText.html("Lỗi page crawl: From/To không hợp lệ.");
            buttonGetListMovies.show(300);
            return false;
        }

        var urlPageCrawl = url_api+`?page=${currentPage}`;

        if (currentPage < toPage) {

            var text = $("#result_list_movies").val();
            var lines = text.split(/\r|\r\n|\n/);
            var count = lines.length;
            divMsgText.html("Done! " + count +" Phim");
            buttonCrawlMovies.show(300);
            buttonGetListMovies.show(300);
            updateQueueInfo();
            return false;
        }
        divMsgText.html(`Crawl Page: ${urlPageCrawl}`);
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "crawl_ophim_page",
                url: urlPageCrawl,
            },
            beforeSend: function () {
                buttonGetListMovies.hide(300);
            },
            success: function (res) {
                let currentList = textArealistMovies.val();
                if (currentList != "") currentList += "\n" + res;
                else currentList += res;

                textArealistMovies.val(currentList);
                updateQueueInfo();
                currentPage--;
                crawl_page_callback(currentPage);
            },
            error: function () {
                divMsgText.html("Get List lỗi: không lấy được dữ liệu từ API.");
                buttonGetListMovies.show(300);
            },
        });
    };

    var inputFilterType = [];
    var inputFilterCategory = [];
    var inputFilterCountry = [];
    var inputFilterYear = [];
    const parseErrorMessage = (xhr, thrownError) => {
        let reason = "";
        if (xhr && xhr.responseText) {
            try {
                const json = JSON.parse(xhr.responseText);
                if (json && json.msg) reason = json.msg;
            } catch (e) {
                reason = xhr.responseText.replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim();
            }
        }
        if (!reason) {
            reason = thrownError || (xhr ? `${xhr.status} ${xhr.statusText}` : "Unknown error");
        }
        if (reason.length > 250) reason = reason.substring(0, 250) + "...";
        return reason;
    };

    buttonCrawlMovies.on("click", () => {
        divMsg.show(300);
        divCrawlStats.show(300);
        divMsgCrawlSuccess.show(300);
        divMsgCrawlError.show(300);
        inputFilterType = [];
        inputFilterCategory = [];
        inputFilterCountry = [];
        inputFilterYear = [];
        let listLink = textArealistMovies.val().split("\n").map((item) => item.trim()).filter((item) => item !== "");
        listLink = dedupeLinks(listLink);
        crawlQueue = listLink.slice();
        textArealistMovies.val(crawlQueue.join("\n"));
        updateQueueInfo();
        crawlStats = {
            total: listLink.length,
            done: 0,
            success: 0,
            error: 0,
            filtered: 0
        };
        crawlTiming = {
            startedAt: Date.now(),
            totalMs: 0,
            minMs: null,
            maxMs: 0,
            measured: 0
        };
        updateCrawlStatsText();
        updateDashboard();

        $("input[name='filter_type[]']:checked").each(function () {
            inputFilterType.push($(this).val());
        });
        $("input[name='filter_category[]']:checked").each(function () {
            inputFilterCategory.push($(this).val());
        });
        $("input[name='filter_country[]']:checked").each(function () {
            inputFilterCountry.push($(this).val());
        });
        inputFilterYear = getFilterYear();
        let workerCount = toIntOrDefault($("input[name='crawl_concurrency']").val(), 1);
        if (workerCount < 1) workerCount = 1;
        if (workerCount > 10) workerCount = 10;
        $("input[name='crawl_concurrency']").val(workerCount);
        localStorage.setItem("crawl_concurrency", workerCount.toString());
        localStorage.setItem("crawl_fast_mode", $("input[name='crawl_fast_mode']").is(":checked") ? "1" : "0");

        buttonCrawlMovies.hide(300);
        buttonRollMovies.hide(300);
        if (crawlQueue.length === 0) {
            updateCrawlStatsText();
            return;
        }
        activeWorkers = Math.min(workerCount, crawlQueue.length);
        for (let i = 0; i < activeWorkers; i++) {
            crawl_movies();
        }
    });
    const crawl_movies = () => {
        let linkCurrent = crawlQueue.shift();
        if (!linkCurrent) {
            activeWorkers--;
            if (activeWorkers <= 0) {
                buttonCrawlMovies.show(300);
                buttonRollMovies.show(300);
                const doneText = `Crawl Done! Tổng: ${crawlStats.total} | Thành công: ${crawlStats.success} | Lỗi: ${crawlStats.error} | Bỏ qua: ${crawlStats.filtered}`;
                divMsgText.html(doneText);
                divCrawlStatsText.html(doneText);
            }
            textArealistMovies.val(crawlQueue.join("\n"));
            updateQueueInfo();
            return false;
        }
        textArealistMovies.val(crawlQueue.join("\n"));
        updateQueueInfo();
        updateCrawlStatsText();
        const requestStartedAt = (typeof performance !== "undefined" && performance.now) ? performance.now() : Date.now();

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "crawl_ophim_movies",
                url: linkCurrent,
                filterType: inputFilterType,
                filterCategory: inputFilterCategory,
                filterCountry: inputFilterCountry,
                filterYear: inputFilterYear,
            },
            beforeSend: function () {
            },
            success: function (res) {
                console.log(res)
                let data;
                try {
                    data = JSON.parse(res);
                } catch (e) {
                    const requestEndedAt = (typeof performance !== "undefined" && performance.now) ? performance.now() : Date.now();
                    recordCrawlDuration(requestEndedAt - requestStartedAt);
                    crawlStats.done++;
                    crawlStats.error++;
                    let currentList = textAreaResultError.val();
                    const errorLine = `${linkCurrent}=====>>Invalid JSON response`;
                    if (currentList != "") currentList += "\n" + errorLine;
                    else currentList += errorLine;
                    textAreaResultError.val(currentList);
                    updateCrawlStatsText();
                    crawl_movies();
                    return;
                }
                if (parseInt(data.schedule_code, 10) === 4) {
                    crawlStats.filtered++;
                    let currentList = textAreaResultError.val();
                    if (currentList != "") currentList += "\n" + linkCurrent;
                    else currentList += linkCurrent;
                    textAreaResultError.val(currentList + "=====>>FILTERED");
                } else if (data.status) {
                    crawlStats.success++;
                    let currentList = textAreaResultSuccess.val();
                    if (currentList != "") currentList += "\n" + linkCurrent;
                    else currentList += linkCurrent;
                    textAreaResultSuccess.val(currentList);
                } else {
                    crawlStats.error++;
                    let currentList = textAreaResultError.val();
                    if (currentList != "") currentList += "\n" + linkCurrent;
                    else currentList += linkCurrent;
                    textAreaResultError.val(currentList + "=====>>" + data.msg);
                }
                const requestEndedAt = (typeof performance !== "undefined" && performance.now) ? performance.now() : Date.now();
                recordCrawlDuration(requestEndedAt - requestStartedAt);
                crawlStats.done++;

                var wait_timeout = 1000;
                if (data.wait) {
                    let timeout_from = $("input[name=timeout_from]").val();
                    let timeout_to = $("input[name=timeout_to]").val();
                    let maximum = Math.max(timeout_from, timeout_to);
                    let minimum = Math.min(timeout_from, timeout_to);
                    wait_timeout = Math.floor(Math.random() * (maximum - minimum + 1)) + minimum;
                }
                if ($("input[name='crawl_fast_mode']").is(":checked")) {
                    wait_timeout = 0;
                }
                updateCrawlStatsText();
                updateDashboard();
                setTimeout(() => {
                    crawl_movies();
                }, wait_timeout);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                const requestEndedAt = (typeof performance !== "undefined" && performance.now) ? performance.now() : Date.now();
                recordCrawlDuration(requestEndedAt - requestStartedAt);
                crawlStats.done++;
                crawlStats.error++;
                let currentList = textAreaResultError.val();
                const reason = parseErrorMessage(xhr, thrownError);
                const errorLine = `${linkCurrent}=====>>${reason}`;
                if (currentList != "") currentList += "\n" + errorLine;
                else currentList += errorLine;
                textAreaResultError.val(currentList);

                updateCrawlStatsText();
                updateDashboard();
                crawl_movies();
            },
        });
    };

    $("input[name='filter_type[]']").change(() => {
        var saveFilterData = [];
        $("input[name='filter_type[]']:checked").each(function () {
            saveFilterData.push($(this).val());
        });
        localStorage.setItem("filterType", JSON.stringify(saveFilterData));
    });

    $("input[name='filter_category[]']").change(() => {
        var saveFilterData = [];
        $("input[name='filter_category[]']:checked").each(function () {
            saveFilterData.push($(this).val());
        });
        localStorage.setItem("filterCategory", JSON.stringify(saveFilterData));
    });

    $("input[name='filter_country[]']").change(() => {
        var saveFilterData = [];
        $("input[name='filter_country[]']:checked").each(function () {
            saveFilterData.push($(this).val());
        });
        localStorage.setItem("filterCountry", JSON.stringify(saveFilterData));
    });
    $("input[name='filter_year[]']").change(() => {
        localStorage.setItem("filterYear", JSON.stringify(getFilterYear()));
    });

    $("input[name=page_from]").change((e) => {
        localStorage.setItem("page_from", $("input[name=page_from]").val());
    });
    $("input[name=page_to]").change((e) => {
        localStorage.setItem("page_to", $("input[name=page_to]").val());
    });
    $("input[name=timeout_from]").change((e) => {
        localStorage.setItem("timeout_from", $("input[name=timeout_from]").val());
    });
    $("input[name=timeout_to]").change((e) => {
        localStorage.setItem("timeout_to", $("input[name=timeout_to]").val());
    });
    $("input[name=crawl_concurrency]").change(() => {
        let workerCount = toIntOrDefault($("input[name=crawl_concurrency]").val(), 1);
        if (workerCount < 1) workerCount = 1;
        if (workerCount > 10) workerCount = 10;
        $("input[name=crawl_concurrency]").val(workerCount);
        localStorage.setItem("crawl_concurrency", workerCount.toString());
    });
    $("input[name='crawl_fast_mode']").change(() => {
        localStorage.setItem("crawl_fast_mode", $("input[name='crawl_fast_mode']").is(":checked") ? "1" : "0");
    });


    // Crawler Schedule
    $("#save_crawl_ophim_schedule").on("click", () => {
        let pageFrom = $("input[name=page_from]").val();
        let pageTo = $("input[name=page_to]").val();
        let crawl_resize_size_thumb = $("input[name=crawl_resize_size_thumb]:checked").val();
        let crawl_resize_size_thumb_w = $("input[name=crawl_resize_size_thumb_w]").val();
        let crawl_resize_size_thumb_h = $("input[name=crawl_resize_size_thumb_h]").val();
        let crawl_resize_size_poster = $("input[name=crawl_resize_size_poster]:checked").val();
        let crawl_resize_size_poster_w = $("input[name=crawl_resize_size_poster_w]").val();
        let crawl_resize_size_poster_h = $("input[name=crawl_resize_size_poster_h]").val();
        let crawl_convert_webp = $("input[name=crawl_convert_webp]:checked").val();
        let filterType = [];
        $("input[name='filter_type[]']:checked").each(function () {
            filterType.push($(this).val());
        });

        let filterCategory = [];
        $("input[name='filter_category[]']:checked").each(function () {
            filterCategory.push($(this).val());
        });

        let filterCountry = [];
        $("input[name='filter_country[]']:checked").each(function () {
            filterCountry.push($(this).val());
        });
        let filterYear = getFilterYear();

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "crawl_ophim_save_settings",
                pageFrom,
                pageTo,
                filterType,
                filterCategory,
                filterCountry,
                filterYear,
                crawl_resize_size_thumb,
                crawl_resize_size_thumb_w,
                crawl_resize_size_thumb_h,
                crawl_resize_size_poster,
                crawl_resize_size_poster_w,
                crawl_resize_size_poster_h,
                crawl_convert_webp,
            },
            success: function (res) {
                alert("Lưu thành công!")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu cấu hình thất bại!");
            },
        });
    })

    $("#crawl_ophim_schedule_enable").on("click", (e) => {
        let enable = $("#crawl_ophim_schedule_enable").is(":checked");
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "crawl_ophim_schedule_enable",
                enable
            },
            success: function (res) {

            },
            error: function (xhr, ajaxOptions, thrownError) {
            },
        });
    })
    $("#save_crawl_ophim_schedule_secret").on("click", (e) => {

        let secret_key = $("input[name='crawl_ophim_schedule_secret']").val();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "save_crawl_ophim_schedule_secret",
                secret_key
            },
            success: function (res) {
                alert("Lưu thành công!");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu thất bại!");
            },
        });
    })

    $('.add-to-featured').click(function() {
        var postid = $(this).data("postid");
        var nonce = $(this).data("nonce");
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                 action: "dt_add_featured",
                 postid,
                 nonce,
            },
            success: function (res) {
                $("#feature-del-"+postid).show();
                $("#feature-add-"+postid).hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu thất bại!");
            },
        });
    });
    $('.del-of-featured').click(function() {
        var postid = $(this).data("postid");
        var nonce = $(this).data("nonce");
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                 action: "dt_remove_featured",
                 postid,
                 nonce,
            },
            success: function (res) {
                $("#feature-del-"+postid).hide();
                $("#feature-add-"+postid).show();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu thất bại!");
            },
        });
    });
    $('#add-server-btn').click(function() {
        let namesv = $("input[name='name-server-ophim']").val();
        var postid = $(this).data("postid");
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "add_server_phim",
                namesv,
                postid
            },
            success: function (res) {
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu thất bại!");
            },
        });
    });



    // save css js
    $("#save_config_cssjs").on("click", () => {
        var css = $("#ophim_css").val();
        var js = $("#ophim_js").val();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "ophim_save_config_cssjs",
                css,
                js,
            },
            success: function (res) {
                alert("Lưu thành công!")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Lưu cấu hình thất bại!");
            },
        });
    })

});
