<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>购物车</title>
    <meta content="app-id=518966501" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link href="css/comm.css" rel="stylesheet" type="text/css" />
    <link href="css/cartlist.css" rel="stylesheet" type="text/css" />
</head>
<body id="loadingPicBlock" class="g-acc-bg">
<meta name="csrf-token" content="{{ csrf_token() }}">
<input name="hidUserID" type="hidden" id="hidUserID" value="-1" />
<div>
    <!--首页头部-->
    <div class="m-block-header">
        <a href="/" class="m-public-icon m-1yyg-icon"></a>
        <a href="/" class="m-index-icon">编辑</a>
    </div>
    <!--首页头部 end-->
    <div class="g-Cart-list">
        <ul id="cartBody">
            @foreach($cart as $v)
                <li>
                    <s class="xuan current" num="{$v->num}" goods_id="{{$v->g_id}}"></s>
                    <a class="fl u-Cart-img" href="/v44/product/12501977.do">
                        <img src="/uploads/{{$v->goods_img}}" border="0" alt="">
                    </a>
                    <div class="u-Cart-r">

                        <a href="/v44/product/12501977.do" class="gray6">{{$v->goods_name}}</a>
                        <span class="gray9">
                            <em>剩余{{$v->goods_pnum}}</em>
                        </span>
                        <div class="num-opt">
                            <em class="num-mius dis min" g_id="{{$v->g_id}}"><i></i></em>
                            <input class="text_box"  name="num" price="{{$v->goods_price}}" goods_pnum="{{$v->goods_pnum}}"  maxlength="6" type="text" value="{{$v->num}}" codeid="12501977">
                            <em class="num-add add" g_id="{{$v->g_id}}"><i></i></em>
                        </div>
                        <a href="javascript:;" name="delLink"  cs="{{$v->g_id}}" cid="12501977" isover="0" class="z-del"><s></s></a>

                    </div>
                </li>
            @endforeach
        </ul>
        <div id="divNone" class="empty "  style="display: none"><s></s><p>您的购物车还是空的哦~</p><a href="https://m.1yyg.com" class="orangeBtn">立即潮购</a></div>
    </div>
    <div id="mycartpay" class="g-Total-bt g-car-new" style="">
        <dl>
            <dt class="gray6">
                <s class="quanxuan current"></s>全选
            <p class="money-total">合计<em class="orange total"><span>￥</span></em></p>

            </dt>
            <dd>
                <a href="javascript:;" id="a_payment1"  class="orangeBtn del w_account remove">删除</a>
                <a href="javascript:;" id="a_payment"   class="order orangeBtn w_account com">去结算</a>
            </dd>
        </dl>
    </div>
    <div class="hot-recom">
        <div class="title thin-bor-top gray6">
            <span><b class="z-set"></b>人气推荐</span>
            <em></em>
        </div>
        <div class="goods-wrap thin-bor-top">
            <ul class="goods-list clearfix">
                @foreach($info as $v)
                    <li>
                        <a href="https://m.1yyg.com/v44/products/23458.do" class="g-pic">
                            <img src="/uploads/{{$v->goods_img}}" width="136" height="136">
                        </a>
                        <p class="g-name">
                            <a href="https://m.1yyg.com/v44/products/23458.do">{{$v->goods_name}}</a>
                        </p>
                        <ins class="gray9" price="{{$v->goods_price}}">价值:￥{{$v->goods_price}}</ins>
                        <div class="btn-wrap">
                            <div class="Progress-bar">
                                <p class="u-progress">
                                    <span class="pgbar" style="width:1%;">
                                        <span class="pging"></span>
                                    </span>
                                </p>
                            </div>
                            <div class="gRate" data-productid="23458">
                                <a href="javascript:;"><s></s></a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="footer clearfix">
        <ul>
            <li class="f_home"><a href="/index" ><i></i>潮购</a></li>
            <li class="f_announced"><a href="/v41/lottery/" ><i></i>最新揭晓</a></li>
            <li class="f_single"><a href="/v41/post/index.do" ><i></i>晒单</a></li>
            <li class="f_car"><a id="btnCart" href="/v41/mycart/index.do" class="hover"><i></i>购物车</a></li>
            <li class="f_personal"><a href="/v41/member/index.do" ><i></i>我的潮购</a></li>
        </ul>
    </div>

    <script src="js/jquery-1.11.2.min.js"></script>
    <!---商品加减算总数---->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            $(".add").click(function () {
                var t = $(this).prev();
                t.val(parseInt(t.val()) + 1);
                var data={}
                data.num=t.val();
                data.goods_pnum=t.attr('goods_pnum');
                data.g_id=$(this).attr('g_id');
                GetCount();
                $.ajax({
                    data:data,
                    type:"post",
                    datatype:"json",
                    url:"upd",
                    success:function(msg){
                        if(msg==1){
                            alert('库存不足')
                            location.href="/shopcart";
                        }
                    }
                });
            })
            $('.text_box').blur(function(){
                var goods_pnum=$(this).attr('goods_pnum');
                // alert(goods_pnum)
                ;
                var data={}
                data.goods_pnum=goods_pnum;
                data.num=$(this).val();
                data.g_id=$(".add").attr('g_id')
                $.ajax({
                    data:data,
                    type:"post",
                    datatype:"json",
                    url:"upd",
                    success:function(msg){
                        if(msg==1){
                            alert('库存不足')
                            history.go(0)
                        }else if(msg==2){
                            alert('数量必须大于或等于1')
                            history.go(0)
                        }
                    }
                });
            })
            $(".min").click(function () {
                var t = $(this).next();
                if(t.val()>1){
                    t.val(parseInt(t.val()) - 1);
                    var data={}
                    data.num=t.val();
                    data.goods_pnum=t.attr('goods_pnum');
                    data.g_id=$(this).attr('g_id');
                    GetCount();
                    $.ajax({
                        data:data,
                        type:"post",
                        datatype:"json",
                        url:"upd",
                        success:function(msg){

                        }
                    });
                }

            })
        })
    </script>
    <script>
        // 全选
        $(".quanxuan").click(function () {
            if($(this).hasClass('current')){
                $(this).removeClass('current');
                $(".g-Cart-list .xuan").each(function () {
                    if ($(this).hasClass("current")) {
                        $(this).removeClass("current");
                    } else {
                        $(this).addClass("current");
                    }
                });
                GetCount();
            }else{
                $(this).addClass('current');

                $(".g-Cart-list .xuan").each(function () {
                    $(this).addClass("current");
                    // $(this).next().css({ "background-color": "#3366cc", "color": "#ffffff" });
                });
                GetCount();
            }
        });
        // 单选
        $(".g-Cart-list .xuan").click(function () {
            if($(this).hasClass('current')){
                $(this).removeClass('current');
            }else{
                $(this).addClass('current');
            }
            if($('.g-Cart-list .xuan.current').length==$('#cartBody li').length){
                $('.quanxuan').addClass('current');
            }else{
                $('.quanxuan').removeClass('current');
            }
            // $("#total2").html() = GetCount($(this));
            GetCount();
            //alert(conts);
        });
        // 已选中的总额
        function GetCount() {
            var conts = 0;
            var aa = 0;
            $(".g-Cart-list .xuan").each(function () {
                if ($(this).hasClass("current")) {
                    for (var i = 0; i < $(this).length; i++) {
                        var str =parseInt($(this).parents('li').find('input.text_box').val());
                        var price  = parseInt($(this).parents('li').find('input.text_box').attr('price'));
                        conts+=parseInt(str*price );
                    }
                }
            });
            $(".total").html('<span>￥</span>'+(conts).toFixed(2));
        }
        GetCount();
        //单删
        $(".z-del").click(function(){
            var data={};
            data.id=$(this).attr('cs');
            $.ajax({
                type:"post",
                data:data,
                datatype:"json",
                url:"del",
                success:function(msg){
                    if(msg==1){
                        alert('删除成功')
                        history.go(0);
                    }else{
                        alert('删除失败')
                    }
                }
            });
        })
        //批删
        $('.del').click(function(){
            var id=[];
            $(".g-Cart-list .xuan").each(function () {
                if ($(this).hasClass("current")) {
                    for (var i = 0; i < $(this).length; i++) {
                        id.push($(this).attr('goods_id'));
                    }
                }
            });
            var data={};
            data.id=id;
            $.ajax({
                type:"post",
                data:data,
                datatype:"json",
                url:"deletes",
                success:function(msg){
                    if(msg==1){
                        alert('删除成功');
                        history.go(0);
                    }
                }
            });
        })
        //去结算
//        $('.com').click(function(){
//            var ids=[];
//            var price=$(".orange.total").text();
//            $(".xuan.current").each(function () {
//                ids.push($(this).attr('goods_id'));
//            });
//            var g_id=$(".z-del").attr('cart_id');
//            // alert(ids);
//            var data={};
//            data.id=ids;
//            data.price=price;
//            $.ajax({
//                type:"post",
//                data:data,
//                url:"pay",
//                datatype:"json",
//                success:function(res){
//                    if(res.status==1){
//                        alert(res.msg)
//                        location.href="login"
//                    }
//                    if(res.status==2){
//                        alert(res.msg)
//                        location.href="shopcart"
//                    }
//                    if(res.status==3){
//                        location.href="payment";
//                    }
//                }
//            });
//        })


    </script>
</body>
</html>
