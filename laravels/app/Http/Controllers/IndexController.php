<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Extend\Send\Send;
class IndexController extends BaseController
{
    protected static $arrCate;
    //注册
    public function register(){
        return view('register');
    }
//注册执行
    public function reg(Request $request){
        $data=$request->all();
        $code=$data['code'];
        $tel=$data['tel'];
        unset($data['_token']);
        $pwd=md5($data['pwd']);
        $conpwd=md5($data['pwds']);
        $data=[];
        $data['pwd']=$pwd;
        $data['tel']=$tel;
        $use=DB::table('code')->where(['tel'=>$tel,'code'=>$code,'status'=>1])->get()->count();
        if(!$use){
            return $arr = ['msg'=>0,'font'=>'请输入获取验证码的手机号！'];
        }
        $user=DB::table('code')->where(['tel'=>$tel,'code'=>$code,'status'=>1])->first();
        if(time()>$user->timeout){
            return $arr = ['msg'=>0,'font'=>'您的验证码过期！'];
        }else if($pwd!=$conpwd){
            $data=array(
                'status'=>0,
                'msg'=>"密码不一致"
            );
            return $data;
        }else{
            $user=DB::table('user')->where('tel',$tel)->first();
            if(!empty($user)){
                $arr=array(
                    'status'=>0,
                    'msg'=>"已存在"
                );
                return $arr;
            }
            $info=DB::table('user')->insert($data);
            if($info){
                DB::table('code')->where('id',$user['u_id'])->update(['status'=>0]);
                return array(
                    'status'=>1,
                    'msg'=>"注册成功"
                );
            }else{
                return array(
                    'status'=>0,
                    'msg'=>"用户已注册"
                );
            }
        }
    }
//发送验证码
        public function code(Request $request){
            $arr=$request->all();
            $num = rand(1000,9999);
            $tel=$arr['tel'];
            $obj=new Send();
            $code=$obj->show($tel,$num);
            if($code==100){
                $data=[
                    'code'=>$num,
                    'tel'=>$tel,
                    'status'=>1,
                    'timeout'=>time()+60
                ];
                $res=DB::table('code')->insert($data);
                $arr=array(
                    'code'=>1,
                    'msg'=>'短信发送成功'
                );
                echo   json_encode($arr);
            }else{
                $arr=array(
                    'code'=>0,
                    'msg'=>'短信发送失败'
                );
                echo  json_encode($arr);
            }
        }

    //登录
    public function login(){
        return view('login');
    }
    public function loginadd(Request $request){
        $data=$request->input();
        $tel=$data['tel'];
        $pwd=$data['pwd'];
        $code=$data['code'];
        $verifycode=$request->session()->get('verifycode');
        $where=[
            'tel'=>$tel,
        ];
        $arr=DB::table('user')->where($where)->first();
        if($code!=$verifycode){
            $arr=[
                'status'=>0,
                'msg'=>"验证码或密码错误"
            ];
            echo json_encode($arr);
        }else if($arr){
            $id=$arr->u_id;
            $tel=$arr->tel;
            session(['u_id'=>$id,'tel'=>$tel]);
            $arr=[
                'status'=>1,
                'msg'=>"登录成功"
            ];
            echo json_encode($arr);
        }
    }
    //潮购首页
   public function index(){
       $data=DB::table('goods')->where(['goods_show'=>1])->paginate(2);
       $info=DB::table('goods')->where(['is_tell'=>2])->get();
       return view('index',['data'=>$data,'info'=>$info]);
   }
   //所有商品
    public function allshops(){
       $info=DB::table('goods')->get();
//       print_r($info);die;
       $data=DB::table('category')->where(['pid'=>0])->get();
        return view('allshops',['data'=>$data,'info'=>$info]);
    }

    public function test(Request $request){
        $id=$request->input('id');
        $data=DB::table('category')->select("cate_id")->where("pid",0)->get();
        $cate_id=$id;
        $this->get($id);
        $arr=self::$arrCate;
        $arr=DB::table('goods')->whereIn('cate_id',$arr)->get();
        return view('all',['arr'=>$arr]);
    }
    private function get($id){
        $arrIds=DB::table('category')->select('cate_id')->where("pid",$id)->get();
        if(count($arrIds)!=0){
            foreach($arrIds as $k=>$v){
                $cateId=$v->cate_id;
                $Ids=$this->get($cateId);
                self::$arrCate[]=$Ids;
            }
        }
        if(count($arrIds)==0){
            return $id;
        }
    }
    //商品详情
    public function shopcontent(Request $request){
        $id=$_GET['goods_id'];
        $where=[
            'goods_id'=>$id
        ];
        $data=DB::table('goods')->where($where)->get();
        return view('shopcontent',['data'=>$data]);
    }
    //添加购物车
    public function alladd(Request $request){
        $id=$request->all('id');
        $where=[
            'goods_id'=>$id
        ];
        $data=DB::table('goods')->where($where)->get();
        if($data){
            return 1;
        }else{
            return 2;
        }
    }
    /*
     * @content Request 接id
     * */
    public function cont(Request $request){
        $arr=$request->all('id');
        $id=$arr['id'];
        $where=array(
            'goods_id'=>$id,
        );
        $data=DB::table('goods')->where($where)->first();
        if($data){
            $uid=$request->session()->get('u_id');
            if(session($uid)){
                echo json_encode([
                    'msg'=>1,
                    'font'=>'您还没有登陆'
                ]);
            }else{
                $goods_show=$data->goods_show;
                if($goods_show!=1){
                    echo json_encode([
                        'msg'=>1,
                        'font'=>'没有此商品'
                    ]);
                }else{
                    $where=array(
                        'goods_id'=>$id,
                        'user_id'=>$uid	,
                        'del'=>0
                    );
                    $cart=DB::table("cart")->where($where)->first();
                    if($cart){
                        $number=$cart->num;
                        $num=$number+1;
                        $goods_pnum=$data->goods_pnum;
                        if($goods_pnum<$num){
                            echo json_encode([
                                'msg'=>1,
                                'font'=>'库存不足'
                            ]);
                        }else{
                            $up=DB::table('cart')->where($where)->update(['num'=>$num]);
                            echo json_encode([
                                'msg'=>0,
                                'font'=>'添加成功'
                            ]);
                        }
                    }else{
                        $data=[];
                        $data['goods_id']=$id;
                        $data['user_id']=$uid;
                        $data['time']=time();
                        $data['num']=1;
                        $data['is_show']=1;
                        DB::table('cart')->insert($data);
                       return json_encode([
                            'msg'=>0,
                            'font'=>'添加成功'
                        ]);
                    }
                }
            }
        }else{
            echo json_encode([
                'msg'=>1,
                'font'=>'没有该商品'
            ]);
        }
    }
    /*
     * @content
     *
     * */
    //购物车
    public function shopcart(Request $request){
        $data=$request->input('goods_id');
        $cart=DB::table('cart')->join('goods',"goods.goods_id","=","cart.goods_id")->where(['del'=>0])->get();
        $where=array(
            'is_tell'=>1,
        );
        $info=DB::table('goods')->where($where)->paginate(4);
        return view('shopcart',['cart'=>$cart,'info'=>$info]);
    }
    //购物车单删
    public function del(Request $request){
        $id=$request->input('id');
        $where=array(
            'g_id'=>$id,
        );
        $up=DB::table('cart')->where($where)->update(['del'=>1]);
        if($up){
            return 1;
        }else{
            return 0;
        }
    }
//购物车数量
    public function upd(Request $request){
        $id=$request->input('g_id');
        $goods_pnum=$request->input('goods_pnum');
        $num=$request->input('num');
        // echo $num;die;
        $where=array(
            'g_id'=>$id,
        );
        if($num>$goods_pnum){
            $up=DB::table('cart')->where($where)->update(['num'=>$goods_pnum]);
            return 1;
        }else if($num<1){
            $up=DB::table('cart')->where($where)->update(['num'=>1]);
            return 2;
        }else{
            // DB::enableQueryLog();
            $up=DB::table('cart')->where($where)->update(['num'=>$num]);
            // dd(DB::getQueryLog());
            return 3;
        }
    }
//购物车批删
    public function deletes(Request $request){
        $id=$request->input('id');
        foreach($id as $v){
            $data=DB::table('cart')->where(['g_id'=>$v])->update(['del'=>1]);
        }
        if($data){
            return 1;
        }else{
            return 2;
        }
    }
//购物车提交
//    public function pay(Request $request){
//        $id=$request->session()->get('id');
//        $g_id=$request->input('id');
//        $price1=$request->input('price');
//        // print_r($id);die;
//        $price=ltrim($price1,'￥');
//
//        if($g_id==''){
//            return $arr=array(
//                'status'=>2,
//                'msg'=>'请选择商品'
//            );
//        }else{
//            $data=Cart::join('goods','goods.goods_id','=','cart.goods_id')->whereIn('cart.g_id',$g_id)->get()->toArray();
//        }
//        $name=[];
//        $num=[];
//        foreach($data as $k=>$v){
//            if($v['goods_show']==0){
//                $name[]=$v['goods_name'];
//            }
//            if($v['num']>$v['goods_pnum']){
//                $num[]=$v['goods_name'];
//            }
//        }
//        $name=implode(',',$name);
//        $num=implode(',',$num);
//        if($name!=''){
//            return $arr=array(
//                'status'=>1,
//                'msg'=>$name.'已下架'
//            );
//        }
//        if($num!=''){
//            return $arr=array(
//                'status'=>1,
//                'msg'=>$num.'库存不足'
//            );
//        }
//        $time=date('YmdHis',time()).rand(11111,99999);
//        $ins=[];
//        $ins['user_id']=$id;
//        $ins['order_amount']=$price;
//        $ins['order_sn']=$time;
//        $ins['order_pay_type']=1;
//        $ins['pay_status']=1;
//        $ins['status']=0;
//        $ins['pay_way']=1;
//        $insert=DB::table('shop_order')->insertGetId($ins);
//        session(['order_id'=>$insert]);
//        DB::table('cart')->whereIn('g_id',$g_id)->update(['del'=>1]);
//        $infos=DB::table('shop_order')->where($ins)->select('order_id')->get();
//        // print_r($infos);die;
//        foreach($data as $k=>$v){
//            $arr=[
//                'user_id'=>$id,
//                'order_id'=>$infos[0]->order_id,
//                'order_sn'=>$time,
//                'status'=>0,
//                'goods_name'=>$v['goods_name'],
//                'goods_price'=>$v['goods_price'],
//                'goods_img'=>$v['goods_img'],
//                'buy_numder'=>$v['num'],
//                'goods_id'=>$v['goods_id']
//            ];
//            $res=DB::table('shop_order_detail')->insert($arr);
//        }
//        if($res){
//            return $arr=[
//                'status'=>3,
//            ];
//        }
//    }



    //我的潮购
    public function userpage(Request $request){
        return view('userpage');
    }
    //晒单
    public function share(){
        return view('share');
    }
    //填写晒单内容
    public function willshare(){
        return view('willshare');
    }
}
