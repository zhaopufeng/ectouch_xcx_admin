<?php
// 本类由系统自动生成，仅供测试用途

defined('IN_ECTOUCH') or die('Deny Access');

class OrderController extends CommonController{
	
	public function __construct() {
        parent::__construct();
    }
	//***************************
	//  用户获取订单信息接口
	//***************************
	public function index(){
		$uid = intval($_REQUEST['uid']);
		if (!$uid) {
			echo json_encode(array('status'=>0,'err'=>'登录状态异常'));
			exit();
		}

		//分页
		$pages=intval($_REQUEST['page']);
		if (!$pages) {
			$pages=0;
		}

		/*$orders=M("order");
		$orderp=M("order_product");
		$shangchang = M('shangchang');*/

		//按条件查询
		$uid = intval($uid);
		$order_type = trim($_REQUEST['order_type']);
		if ($order_type) {
			switch ($order_type) {
				case 'pay':
					$where = 'and pay_status = ' . PS_UNPAYED . ' and order_status not in(' . OS_CANCELED . ','. OS_INVALID .','. OS_RETURNED .')';
					break;
				case 'deliver':
					$where = 'and shipping_status = 0';
					break;
				case 'receive':
					$where = 'and shipping_status = 1';
					break;
				/*case 'evaluate':
					$condition['status'] = 40;
					break;	*/
				case 'finish':
					$where = " AND {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
               " AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
               " AND {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " ";
					break;		
				default:
					$where = 'and pay_status = ' . PS_UNPAYED . ' and order_status not in(' . OS_CANCELED . ','. OS_INVALID .','. OS_RETURNED .')';
					break;
			}
		}
		
        //$count = $orders->where($condition)->count();
        $eachpage=7;

		//$order_status = array('0'=>'已取消','10'=>'待付款','20'=>'待发货','30'=>'待收货','40'=>'待评价','50'=>'交易完成','51'=>'交易关闭');
		$sql = "SELECT order_id, order_sn, shipping_id, order_status, shipping_status, pay_status, add_time, " .
                "(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount) AS total_fee " .
                " FROM " . $this->model->pre .
                "order_info WHERE user_id = '$uid' $where ORDER BY add_time DESC LIMIT $pages , 7";
		$order = $this->model->query($sql);	
        $arr = array();
		foreach ($order as $key => $value) {
            $value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];
            $os = L('os');$ps = L('ps');$ss = L('ss');
			$value['order_status'] = $os[$value['order_status']] . ',' . $ps[$value['pay_status']] . ',' . $ss[$value['shipping_status']];
			
			$arr[] = array('order_id' => $value['order_id'],
                'order_sn' => $value['order_sn'],
                'img' => get_image_path(0, model('Order')->get_order_thumb($value['order_id'])),
                'order_time' => local_date(C('time_format'), $value['add_time']),
                'order_status' => $value['order_status'],
                'shipping_id' => $value['shipping_id'],
                'total_fee' => price_format($value['total_fee'], false),
                'goods_count' => model('Users')->get_order_goods_count($value['order_id']),
            );    
        }
		/*foreach ($order as $n=>$v){
			$order[$n]['desc'] = $order_status[$v['status']];
			$prolist = $orderp->where('order_id='.intval($v['id']))->find();
			$order[$n]['photo_x'] = __DATAURL__.$prolist['photo_x'];
			$order[$n]['pid'] = $prolist['pid'];
			$order[$n]['name'] = $prolist['name'];
			$order[$n]['price_yh'] = $prolist['price'];
			$order[$n]['pro_count'] = $orderp->where('order_id='.intval($v['id']))->getField('COUNT(id)');
		}*/

        echo json_encode(array('status'=>1,'ord'=>$arr,'eachpage'=>$eachpage));
        exit();

	}

	//***************************
	//  用户获取订单信息接口
	//***************************
	/*public function get_more(){
		$uid = intval($_REQUEST['uid']);
		if (!$uid) {
			echo json_encode(array('status'=>0,'err'=>'登录状态异常'));
			exit();
		}

		//分页
		$pages=intval($_REQUEST['page']);
		if (!$pages) {
			$pages=2;
		}
		$limit = $pages*7-7;

		$orders=M("order");
		$orderp=M("order_product");
		$shangchang = M('shangchang');

		//按条件查询
		$condition = array();
		$condition['del'] = 0;
		$condition['back'] = '0';
		$condition['uid'] = intval($uid);
		$condition['status'] = 10;
		$order_type = trim($_REQUEST['order_type']);
		if ($order_type) {
			switch ($order_type) {
				case 'pay':
					$condition['status'] = 10;
					break;
				case 'deliver':
					$condition['status'] = 20;
					break;
				case 'receive':
					$condition['status'] = 30;
					break;
				case 'evaluate':
					$condition['status'] = 40;
					break;	
				case 'finish':
					$condition['status'] = array('IN',array(40,50));
					break;		
				default:
					$condition['status'] = 10;
					break;
			}
		}

		//获取总页数
        $count = $orders->where($condition)->count();
        $eachpage=7;

		$order_status = array('0'=>'已取消','10'=>'待付款','20'=>'待发货','30'=>'待收货','40'=>'待评价','50'=>'交易完成','51'=>'交易关闭');

        $order = $orders->where($condition)->order('id desc')->field('id,order_sn,pay_sn,status,price,type,product_num')->limit($limit.',7')->select();
		foreach ($order as $n=>$v){
			$order[$n]['desc'] = $order_status[$v['status']];
			$prolist = $orderp->where('order_id='.intval($v['id']))->find();
			$order[$n]['photo_x'] = __DATAURL__.$prolist['photo_x'];
			$order[$n]['pid'] = $prolist['pid'];
			$order[$n]['name'] = $prolist['name'];
			$order[$n]['price_yh'] = $prolist['price'];
			$order[$n]['pro_count'] = $orderp->where('order_id='.intval($v['id']))->getField('COUNT(id)');
		}

        echo json_encode(array('status'=>1,'ord'=>$order));
        exit();

	}*/

	//***************************
	//  用户退款退货接口
	//***************************
	/*public function order_refund(){
		$uid = intval($_REQUEST['uid']);
		if (!$uid) {
			echo json_encode(array('status'=>0,'err'=>'登录状态异常'));
			exit();
		}

		//分页
		$pages=intval($_REQUEST['page']);
		if (!$pages) {
			$pages=0;
		}

		$orders=M("order");
		$orderp=M("order_product");
		$shangchang = M('shangchang');

		$condition = array();
		$condition['back']=array('gt','0');
		//获取总页数
        $count = $orders->where($condition)->count();
        $the_page = ceil($count/6);

		$refund_status = array('1'=>'退款申请中','2'=>'已退款','3'=>'处理中','4'=>'已拒绝');

        $order = $orders->where($condition)->order('back_addtime desc')->field('id,price,order_sn,product_num,back,back_addtime')->limit($pages.',6')->select();
		foreach ($order as $n=>$v) {
			$order[$n]['desc'] = $refund_status[$v['back']];
			$prolist = $orderp->where('order_id='.intval($v['id']))->find();
			$order[$n]['photo_x'] = __DATAURL__.$prolist['photo_x'];
			$order[$n]['pid'] = $prolist['pid'];
			$order[$n]['name'] = $prolist['name'];
			$order[$n]['price_yh'] = $prolist['price'];
			$order[$n]['back_addtime'] = date("Y-m-d H:i",$v['back_addtime']);
			$order[$n]['pro_count'] = $orderp->where('order_id='.intval($v['id']))->getField('COUNT(id)');
		}

        echo json_encode(array('status'=>1,'ord'=>$order));
        exit();
	}*/

	//***************************
	//  用户退款退货接口
	//***************************
	/*public function get_refund_more(){
		$uid = intval($_REQUEST['uid']);
		if (!$uid) {
			echo json_encode(array('status'=>0,'err'=>'登录状态异常'));
			exit();
		}

		//分页
		$pages=intval($_REQUEST['page']);
		if (!$pages) {
			$pages=2;
		}
		$limit = $pages*6-6;

		$orders=M("order");
		$orderp=M("order_product");
		$shangchang = M('shangchang');

		$condition = array();
		$condition['back']=array('gt','0');
		//获取总页数
        $count = $orders->where($condition)->count();
        $the_page = ceil($count/6);

		$refund_status = array('1'=>'退款申请中','2'=>'已退款','3'=>'处理中','4'=>'已拒绝');

        $order = $orders->where($condition)->order('back_addtime desc')->field('id,price,order_sn,product_num,back,back_addtime')->limit($limit.',6')->select();
		foreach ($order as $n=>$v) {
			$order[$n]['desc'] = $refund_status[$v['back']];
			$prolist = $orderp->where('order_id='.intval($v['id']))->find();
			$order[$n]['photo_x'] = __DATAURL__.$prolist['photo_x'];
			$order[$n]['pid'] = $prolist['pid'];
			$order[$n]['name'] = $prolist['name'];
			$order[$n]['price_yh'] = $prolist['price'];
			$order[$n]['back_addtime'] = date("Y-m-d H:i",$v['back_addtime']);
			$order[$n]['pro_count'] = $orderp->where('order_id='.intval($v['id']))->getField('COUNT(id)');
		}

        echo json_encode(array('status'=>1,'ord'=>$order));
        exit();
	}*/


	//***************************
	//  用户订单编辑接口
	//***************************
	/*public function orders_edit(){
		
	    $orders=M("order");
	    $order_id=intval($_REQUEST['id']);
	    $type=$_REQUEST['type'];

	    $check_id = $orders->where('id='.intval($order_id).' AND del=0')->getField('id');
	    if (!$check_id || !$type) {
	    	echo json_encode(array('status'=>0,'err'=>'订单信息错误.'.__LINE__));
	    	exit();
	    }

	    $data = array();
	    if ($type==='cancel') {
	    	$data['status'] = 0;
	    }elseif ($type==='receive') {
	    	$data['status'] = 40;
	    }elseif ($type==='refund') {
	    	$data['back'] = 1;
	    	$data['back_remark'] = $_REQUEST['back_remark'];
	    }

	    if ($data) {
	    	$result = $orders->where('id='.intval($order_id))->save($data);
	    	if($result !== false){
				echo json_encode(array('status'=>1));
	    		exit();
			}else{
				echo json_encode(array('status'=>0,'err'=>'操作失败.'.__LINE__));
	    	exit();
			}
	    }else{
	    	echo json_encode(array('status'=>0,'err'=>'订单信息错误.'.__LINE__));
	    	exit();
	    }
	}*/

	//***************************
	//  用户订单详情接口
	//***************************
	/*public function order_details(){

		$order_id = intval($_REQUEST['order_id']);
		//订单详情
		$orders=M("order");
		$product_dp=M("product_dp");
		$orderp=M("order_product");
		$id=intval($_REQUEST['id']);
		$qz=C('DB_PREFIX');	//前缀

		$order_info = $orders->where('id='.intval($order_id.' AND del=0'))->field('id,order_sn,shop_id,status,addtime,price,type,post,tel,receiver,address_xq,remark')->find();
		if (!$order_info) {
			echo json_encode(array('status'=>0,'err'=>'订单信息错误.'));
			exit();
		}

		//订单状态
		$order_status=array('0'=>'已取消','10'=>'待付款','20'=>'待发货','30'=>'待收货','40'=>'已收货','50'=>'交易完成');
		//支付类型
		$pay_type = array('cash'=>'现金支付','alipay'=>'支付宝','weixin'=>'微信支付');

		$order_info['shop_name'] = M('shangchang')->where('id='.intval($order_info['shop_id']))->getField('name');
		$order_info['order_status'] = $order_status[$order_info['status']];
		$order_info['pay_type'] = $pay_type[$order_info['type']];
		$order_info['addtime'] = date('Y-m-d H:i:s',$order_info['addtime']);
		$order_info['yunfei'] = 0;
		if ($order_info['post']) {
			$order_info['yunfei'] = M('post')->where('id='.intval($order_info['post']))->getField('price');
		}

		//获取产品
		$pro = $orderp->where('order_id='.intval($order_info['id']))->select();
		foreach ($pro as $k => $v) {
			$pro[$k]['photo_x'] = __DATAURL__.$v['photo_x'];
		}
		
         
        echo json_encode(array('status'=>1,'pro'=>$pro,'ord'=>$order_info));
        exit();
	}*/

    //***************************
	//  用户订单评论接口
	//***************************
    /*public function order_comment(){
    	$id=explode(',', $_GET['id']);

    	$orderid = (int)$_GET['orderid'];
    	$order_product = M('order_product');
    	$order   = M('order');
    	$orderr  = $order->where('id='.$orderid)->select();
    	foreach ($id as $key => $value) {
    		$result[$key]  = $order_product->where('`order`='.$orderid.' and pid='.$value)->select();
    	}
        $this->assign('result',$result);
        $this->assign('orderr',$orderr);
    	$this->display();
    }

    //***************************
	//  用户订单评论接口
	//***************************
    public function addMessage(){
        $product_dp      = M('product_dp');
        $order_product   = M("order_product");
        $order       = M("order");
        //获取商品的ID
        $id              = $_POST['pid'];
        $id              = explode(",", $id);
        $data['orderid'] = $_POST['orderid'];
        $data['type']    = 1;
        $status['mstatus']=1;
        $data['uid']     = $_SESSION['ID'];
        $data['addtime'] = time();
        foreach ($id as $key => $value) {
        	$data['pid']     = $value;
            $data['concent'] = $_POST['content'.$data['pid']];
            $data['num']     = $_POST['pingfen'.$data['pid']];
            $result   = $product_dp->add($data);
        }
        /*$meresult = $product_dp->where('uid='.$_SESSION['ID'].' and orderid='.$_POST['orderid'].' and pid='.$_POST['id'])->select();
        if($meresult){
        	echo 2;
        	exit();
        }*/
        
        /*if($result){
            $order->where('id='.$_POST['orderid'])->save($status);
            $this->success('评价成功',U('User/orders',array('key'=>$_POST['key'])));
        }else{
        	$this->error('评价失败');
        }

    }*/

}