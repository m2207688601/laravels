<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <title>我的潮购</title>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />

    <link href="css/comm.css" rel="stylesheet" type="text/css" /><link href="css/member.css" rel="stylesheet" type="text/css" /><script src="js/jquery190_1.js" language="javascript" type="text/javascript"></script>
</head>
<body class="g-acc-bg">
    
    <div class="welcome" style="display: none">
        <p>Hi，等你好久了！</p>
        <a href="" class="orange">登录</a>
        <a href="" class="orange">注册</a>
    </div>

    <div class="welcome">
        <i class="set"></i>
        <div class="login-img clearfix">
            <ul>
            @foreach($data as $v)
                <li><img src="images/goods2.jpg" alt=""></li>
                <li class="name">
                    <h3>{{$v->tel}}</h3>
                </li>
                <li class="next fr"><s></s></li>
            @endforeach
            </ul>
        </div>
        <div class="chao-money">
            <ul class="clearfix">
                <li class="br">
                    <p>潮购值</p>
                    <span>822</span>
                </li>
                <li class="br">
                    <p>余额（元）</p>
                    <span>0</span>
                </li>
                <li>
                    <a href="" class="recharge">去充值</a>
                </li>
            </ul>
        </div>

    </div>
    <!--获得的商品-->
    
    <!--导航菜单-->
    
    <div class="sub_nav marginB person-page-menu">
        <a href="/v44/member/goodsbuylist.do"><s class="m_s1"></s>潮购记录<i></i></a>
        <a href="/v44/member/orderlist.do"><s class="m_s2"></s>获得的商品<i></i></a>
        <a href="{{url('share')}}"><s class="m_s3"></s>我的晒单<i></i></a>
        <a href="/v44/member/mywallet.do"><s class="m_s4"></s>我的钱包<i></i></a>
        <a href="{{url('writeaddr')}}"><s class="m_s5"></s>收货地址<i></i></a>
        <a href="/v44/help/help.do" class="mt10"><s class="m_s6"></s>帮助与反馈<i></i></a>
        <a href="/v44/help/help.do"><s class="m_s7"></s>二维码分享<i></i></a>
        <p class="colorbbb">客服热线：400-666-2110  (工作时间9:00-17:00)</p>
    </div>
<div class="footer clearfix">
    <ul>
        <li class="f_home"><a href="{{url('/')}}" ><i></i>潮购</a></li>
        <li class="f_single"><a href="{{url('willshare')}}" ><i></i>晒单</a></li>
        <li ><a href="/v41/lottery/" ><i> {{--class="f_announced"最新揭晓--}}</i></a></li>
        <li class="f_car"><a id="btnCart" href="{{url('shopcart')}}" ><i></i>购物车</a></li>
        <li class="f_personal"><a href="{{url('userpage')}}" class="hover"><i></i>我的潮购</a></li>
    </ul>
</div>

    <script>
        function goClick(obj, href) {
            $(obj).empty();
            location.href = href;
        }
        // if (navigator.userAgent.toLowerCase().match(/MicroMessenger/i) != "micromessenger") {
        //     $(".m-block-header").show();
        // }
    </script>
</body>
</html>
