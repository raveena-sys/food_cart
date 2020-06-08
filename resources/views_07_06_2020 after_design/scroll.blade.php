<!DOCTYPE HTML>
<html>

<head>
    <title>ScrollMenu.js | Horizontal Scrollbar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   
    <link rel="shortcut icon" type="image/png" href="http://ignitersworld.com/lab/scrollmenu/css/scrollmenu.csshttp://ignitersworld.com/lab/scrollmenu/images/favicon.png" />
    <link rel="shortcut icon" type='image/x-icon' href='http://ignitersworld.com/lab/scrollmenu/css/scrollmenu.csshttp://ignitersworld.com/lab/scrollmenu/images/favicon.ico' />
    <link rel="stylesheet" type="text/css" href="http://ignitersworld.com/lab/scrollmenu/css/scrollmenu.css">
    <link rel="stylesheet" type="text/css" href="http://ignitersworld.com/lab/scrollmenu/css/base.css">
    <link rel="stylesheet" type="text/css" href="http://ignitersworld.com/lab/scrollmenu/css/demo.css"> 
<script type="text/javascript" src="http://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=vZsg7sHt52crUq4EFTC7eZJxCnR6q55_9RSjHEgr8UQNlGHuh3SOULfNP7hPe2CoDh7Laf7GXS16xtakKzvYtmS3yVseehl8J0QRbZjDSrzrbKLcXPO5bqYTglpQ5WGX" charset="UTF-8"></script></head>

<body class="" >
    <div class='' style="margin-top:30px;">
    <section class="section" id="section1">
        <div class="content">
            Section 1
        </div>
    </section>
    <section class="section" id="section2">
        <div class="content">Section 2</div>
    </section>
    <section class="section" id="section3">
        <div class="content">Section 3</div>
    </section>
    <section class="section" id="section4">
        <div class="content">Section 4</div>
    </section>
    <section class="section" id="section5">
        <div class="content">Section 5</div>
    </section>
     <section class="section" id="section6">
        <div class="content">Section 6</div>
    </section>
     <section class="section" id="section7">
        <div class="content">Section 7</div>
    </section>
     <section class="section" id="section8">
        <div class="content">Section 8</div>
    </section>
     <section class="section" id="section9">
        <div class="content">Section 9</div>
    </section>

</div>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js'></script>
    <script type='text/javascript' src="http://ignitersworld.com/lab/scrollmenu/js/scrollmenu.js"></script>
    <script>
        //base setup
        var setupOption = {
            template: '<div class="menu-wrap"><div class="menu"><%= label %></div></div>',
            menuType: 'horizontal',
            anchorSetup: [
                /*{
                    backgroundColor: "#dc767d",
                    label: "Demo"
                    },
                {
                    backgroundColor: "#36d278",
                    label: "Demo 2"
                    },
                {
                    backgroundColor: "#22cfc6",
                    label: "Demo 3"
                    },
                {
                    backgroundColor: "#8794a1",
                    label: "Section 4"
                },
                {
                    backgroundColor: "#1ccdaa",
                    label: "Section 5"
                },
                {
                    backgroundColor: "#1ccdaa",
                    label: "Section 6"
                },
                {
                    backgroundColor: "#1ccdaa",
                    label: "Section 7"
                },
                {
                    backgroundColor: "#1ccdaa",
                    label: "Section 8"
                }*/]
        };

       
        var scrollMenu = ScrollMenu(setupOption);
 
    </script>
    
    <script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-44908334-1', 'ignitersworld.com');
    ga('send', 'pageview');
</script>

</body>

</html>