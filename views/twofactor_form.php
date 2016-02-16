<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--[if IE 8]>
        <html xmlns="http://www.w3.org/1999/xhtml" class="ie8" lang="en-US">
    <![endif]-->
    <!--[if !(IE 8) ]><!-->
        
    <!--<![endif]-->
    <title>Psy-zo › Log In</title>
    <link rel="stylesheet" id="buttons-css" href="{{ site_url }}/wp-includes/css/buttons.min.css?ver=4.4.2" type="text/css" media="all">
    <link rel="stylesheet" id="open-sans-css" href="https://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&amp;subset=latin%2Clatin-ext&amp;ver=4.4.2" type="text/css" media="all">
    <link rel="stylesheet" id="dashicons-css" href="{{ site_url }}/wp-includes/css/dashicons.min.css?ver=4.4.2" type="text/css" media="all">
    <link rel="stylesheet" id="login-css" href="{{ site_url }}/wp-admin/css/login.min.css?ver=4.4.2" type="text/css" media="all">
    <meta name="robots" content="noindex,follow">
</head>
<body class="login login-action-login wp-core-ui  locale-en-us">
    <div id="login">
        <h1><a href="https://wordpress.org/" title="Powered by WordPress" tabindex="-1">Psy-zo!</a></h1>
        
        <div class="loginErrorContainer"></div>

        <form name="loginform" id="loginform" action="{{ site_url }}/wp-login.php" method="post">
            <p>
                <label for="user_login">Twee staps verificatiecode:<br>
                <input type="text" name="code" id="user_login" aria-describedby="login_error" class="input" value="" size="20"></label>
            </p>
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In">
                <input type="hidden" name="redirect_to" value="{{ site_url }}/wp-admin/">
                <input type="hidden" name="testcookie" value="1">
            </p>
            <input type="hidden" name="username" value="{{ username }}"/>
            <input type="hidden" name="password" value="{{ password }}"/>
        </form>

        <p id="nav">
            <a href="{{ site_url }}/wp-login.php" title="Password Lost and Found">← Back to login</a>
        </p>

        <script type="text/javascript">
        function wp_attempt_focus(){
        setTimeout( function(){ try{
        d = document.getElementById('user_login');
        d.focus();
        d.select();
        } catch(e){}
        }, 200);
        }

        wp_attempt_focus();
        if(typeof wpOnload=='function')wpOnload();
        </script>

        <p id="backtoblog"><a href="{{ site_url }}/" title="Are you lost?">← Back to Psy-zo!</a></p>
    
    </div>
</body>
</html>
