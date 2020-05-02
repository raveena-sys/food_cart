<div id="footer">
<footer>
    <div class="container">
        <ul class="foot_nav">
            <li><a href="{{url('about_us')}}">About Us</a></li>
            <li><a href="{{url('contact_us')}}">Contact Us</a></li>
           <!--  
            <li><a href="{{url('career')}}">Career</a></li> -->
            <li><a href="{{url('privacy_policy')}}">Privacy Policy</a></li>
        </ul>

        <ul class="follow_us">
            <span>Follow Us</span>
            <li>
                <a href=""><img src="{{ asset('img/facebook_ico.png')}}" /></a>
            </li>
            <li>
                <a href=""><img src="{{ asset('img/whatsapp_ico.png')}}" /></a>
            </li>
            <li>
                <a href=""><img src="{{ asset('img/twitter_ico.png')}}" /></a>
            </li>
            <li>
                <a href=""><img src="{{ asset('img/instagram_ico.png')}}" /></a>
            </li>
        </ul>
    </div>
</footer>
<section class="bottom_line">
    Developed By <a href="https://integratedmarketing.pro"><img src="{{asset('img/integrated_marketting.png')}}"></a></br>
    Copy Right @ <?php echo date('Y'); ?> | All Rights Reserved
</section>
</div>