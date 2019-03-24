<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>地址管理</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="css/comm.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/address.css">
    <link rel="stylesheet" href="css/sm.css">
</head>
<body>
  <meta name="csrf-token" content="{{ csrf_token() }}">
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">地址管理</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="{{url('writeaddr')}}" class="m-index-icon">添加</a>
</div>
<div class="addr-wrapp">
    <div class="addr-list">
    @foreach($arr as $v)
         <ul>
            <li class="clearfix">
                <span class="fl">{{$v->address_name}}</span>
                <span class="fr">{{$v->tel}}</span>
            </li>
            <li>
                <p>{{$v->country}}</p>
            </li>
            <li class="a-set">
                 @if($v->is_default==2)
                    <s class='z-set add' addressId="{{$v->address_id}}" style='margin-top: 6px;'></s>
                    <span >默认地址</span>
                    @else
                    <s class='z-defalt add' addressId="{{$v->address_id}}" style='margin-top: 6px;'></s>
                    <span class='defalt'  >设为默认</span>
                    @endif
                <div class="fr">
                    <a href="updatelist?id={{$v->address_id}}"><span class="edit">编辑</span></a>
                    <span addressId="{{$v->address_id}}" class="remove">删除</span>
                </div>
            </li>
        </ul>  
        @endforeach
    </div>
</div>
<script src="js/zepto.js" charset="utf-8"></script>
<script src="js/sm.js"></script>
<script src="js/sm-extend.js"></script>
<script src="js/jquery-1.11.2.min.js"></script>

<!-- 单选 -->
<script>
     // 删除地址
    $(document).on('click','span.remove', function () {
         $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        var data={}
        data.id=$(this).attr('addressId');
        $.ajax({
            type:"post",
            data:data,
            url:"delete",
            success:function(msg){
                if(msg==1){
                    alert('删除成功')
                    location.href="address"
                }else{
                    alert('删除失败')
                }
        }          
    });
})
      
</script>
<script src="js/jquery-1.8.3.min.js"></script>
<script>
    var $$=jQuery.noConflict();
    // $$(document).ready(function(){
    //         // jquery相关代码
    //         $$('.addr-list .a-set s').toggle(
    //         function(){
    //             if($$(this).hasClass('z-set')){
    //                 $$(this).removeClass('z-defalt').removeClass('z-set');
    //             }else{
    //                 $$(this).removeClass('z-defalt').addClass('z-set');
    //                 $$(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
    //             }   
    //         },
    //         function(){
    //             if($$(this).hasClass('z-defalt')){
    //                 $$(this).removeClass('z-defalt').addClass('z-set');
    //                 $$(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
    //             }
                
    //         }
    //     )

    // });
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.add').click(function(){
        var id=$(this).attr('addressId')
        $.ajax({
            type:"post",
            data:{id:id},
            url:"{{url('upaddress')}}",
            dataType:"json",
            success:function(msg){
                if(msg==1){
                    alert('设置默认成功');
                    location.href='address'
                }
            }
        })
    })
</script>



</body>
</html>
