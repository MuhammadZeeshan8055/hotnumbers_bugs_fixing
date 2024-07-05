<div id="cookie-notice-bar">
    <p>We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.</p>
    <a href="#" class="button" onclick="cookie_notice_close();return false;">Ok</a>
    <a href="#" class="icon-close" onclick="cookie_notice_close();return false;"></a>
</div>

<script>
    const cookie_notice_init = ()=> {
        const cookie_accepted = localStorage.getItem('cookie_notice_accepted');
        if(!cookie_accepted) {
            document.querySelector('#cookie-notice-bar').classList.add('show');
        }
    }

    const cookie_notice_close = ()=>{
        document.querySelector('#cookie-notice-bar').style.opacity = 0;
        setTimeout(()=>{
             localStorage.setItem('cookie_notice_accepted',1);
             document.querySelector('#cookie-notice-bar').classList.remove('show');
        },400);

    }

    cookie_notice_init();
</script>