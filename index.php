<?php

    require_once "headless-cms.php";

    $page = handle_request();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Insert any custom head contents -->
    <?php echo $page->head_content ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="manifest" href="/resources/pwa/manifest.json" />

    <meta name="theme-color" content="#202020">
    <link rel="icon" href="/resources/images/icon.png" type="image/png">

    <!-- Import Alpine JS -->
    <!-- Remove this if you don't wish to use Alpine JS across you webpages -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs-requests@1.x.x/dist/plugin.min.js"></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="/scripts/alpine-requests.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Import html-ajax -->
    <script defer src="/scripts/html-ajax.js"></script>

    <!-- If the title property is set, insert here. -->
    <?php echo $page->get_property('title') ?>

    <!-- If the description property is set, insert here. -->
    <?php echo $page->get_property('description') ?>

    <!-- If the og-image property is set, insert here. -->
    <?php echo $page->get_property('og-image') ?>

    <!-- If the og-type property is set, insert here. -->
    <?php echo $page->get_property('og-type') ?>

    <!-- If the og-url property is set, insert here. -->
    <?php echo $page->get_property('og-url') ?>

    <!-- If the favicon property is set, insert here. Default value is '/resources/favicon.png' -->
    <?php echo $page->get_property('favicon') ?>




    <!-- Add global stylesheet imports here -->
    <link rel="stylesheet" href="/resources/styles/defaults.css">
    <link rel="stylesheet" href="/resources/styles/globals.css">
    <link rel="stylesheet" href="/resources/styles/utils.css">
    <link rel="stylesheet" href="/resources/styles/header.css">
    <link rel="stylesheet" href="/resources/styles/mobile.css">
    <link rel="stylesheet" href="/resources/styles/forms.css">

</head>
<body
    x-data="{
        authenticated: $persist(null),

        get miniAppName() {
            let pathParts = window.location.pathname.split('/').filter(Boolean);
            return pathParts.length > 0 ? pathParts[0] : null;
        },

        async pageHasLoaded() {
            let res = await fetch('/backend/api/auth/GET-user.php')

            if(res.status === 200) {
                let userData = await res.json()
                this.authenticated = userData
            } else {
                this.authenticated = null
            }
        }
    }"

    x-init="pageHasLoaded"
    @get="console.log(await $event.detail.response); if(await $event.detail.response.status === 200) {authenticated = await $event.detail.response.json()}"
>

    <header>
        <div class="header-top" x-cloak>
            <small>
                <a href="/">🏠</a>
            </small>

            <small x-show="authenticated === null">
                <a
                    href="/auth/login"
                    style="color: red;"
                >Not logged in</a>
            </small>

            <small x-show="authenticated !== null">
                <a href="/auth/account">
                    Logged in as:
                    <b class="text-primary" x-text="authenticated.username"></b>
                </a>
            </small>
        </div>

        <div class="header-bottom">
            <a x-bind:href="'/' + miniAppName">
                <h2 x-text="miniAppName"></h2>
            </a>
        </div>
    </header>
    
    <main class="page-padding-right page-padding-left" x-cloak>
        <!-- Insert the page content in here -->
        <?php echo $page->content; ?>
    </main>


    <footer>

    </footer>

</body>
</html>