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

    <meta name="theme-color" content="#202020">
    <link rel="icon" href="/resources/images/icon.png" type="image/png">

    <!-- Import Alpine JS -->
    <!-- Remove this if you don't wish to use Alpine JS across you webpages -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs-requests@1.x.x/dist/plugin.min.js"></script> -->
    <script defer src="/scripts/alpine-persist.js"></script>
    <script defer src="/scripts/alpine-requests.js"></script>
    <script defer src="/scripts/alpine.js"></script>

    <script src="/headless-cms-scripts/"></script>

    <!-- Import html-ajax -->
    <!-- <script defer src="/scripts/html-ajax.js"></script> -->

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
                console.log('User is already logged in')
                let userData = await res.json()
                this.authenticated = userData
            } else {
                console.log('User is NOT already logged in')

                if(!this.authenticated) {
                    return
                }

                try {

                    // Try login with token
                    let authRes = await fetch('/backend/api/auth/POST-login-with-token.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `token=${encodeURIComponent(this.authenticated?.token)}&username=${encodeURIComponent(this.authenticated?.username)}`
                    })

                    if(authRes.status === 200) {
                        let auth = await authRes.json()
                        console.log('NEW TOKEN', auth.token)
                        this.authenticated = auth
                    } else {
                        this.authenticated = null
                    }   

                } catch (error) {

                    console.log(error)

                }
            }
        }
    }"

    x-init="pageHasLoaded"
    @get="console.log(await $event.detail.response); if(await $event.detail.response.status === 200) {authenticated = await $event.detail.response.json()}"
>

    <header>
        <div class="header-top" x-cloak>
            <a href="/">
                <small>
                    🏠 Rapid Apps
                </small>
            </a>

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

        <div x-show="miniAppName" class="header-bottom">
            <a x-bind:href="'/' + miniAppName">
                <h2 x-text="miniAppName.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"></h2>
            </a>
        </div>
    </header>
    
    <template x-if="authenticated !== null || window.location.pathname.startsWith('/auth')" x-cloak>
        <main class="page-padding-right page-padding-left" x-cloak>
            <!-- Insert the page content in here -->
            <?php echo $page->content; ?>
        </main>
    </template>

    <template x-if="authenticated === null" x-cloak>
        <div>
            <p class="text-center">Please <a class="primary underline" href="/auth/login">log in</a></p>
        </div>
    </template>

    <footer>

    </footer>

</body>
</html>