<?php

class GoodsModel extends CommonModel{
    protected $pk   = 'goods_id';
    protected $tableName =  'goods';
	
	protected $_validate = array(
        array(),
        array(),
        array()
    );
	
	public function getError(){
        return $this->error;
    }

    public function _format($data){
        $data['save'] =  round(($data['price'] - $data['mall_price'])/100,2);
        $data['price'] = round($data['price']/100,2);
		//多属性开始
		$data['mobile_fan'] = round($data['mobile_fan']/100,2);
		//多属性结束
        $data['mall_price'] = round($data['mall_price']/100,2); 
        $data['settlement_price'] = round($data['settlement_price']/100,2); 
        $data['commission'] = round($data['commission']/100,2); 
        $data['discount'] = round($data['mall_price'] * 10 / $data['price'],1);
        return $data;
    }
	
	//商城海报开始
	public function goodsCode($goods_id,$uid = 0){
      
	    $detail = M('goods')->where(array('goods_id'=>$goods_id))->find();
	    $shop = M('shop')->where(array('shop_id'=>$detail['shop_id']))->find();
		$users = M('users')->where(array('user'=>$uid))->find();
		
		$token = 'goods_'.$goods_id;
		$url = $this->_CONFIG['sote']['host'].'/wap/mall/detail/goods_id/'.$goods_id.'/fuid/'.$uid;//分销连接
		
		$file = ToQrCode($token,$url,8,'goods');
		

		$title[0] = mb_substr($detail['title'],0,28,'utf-8');
        $title[1] = mb_substr($detail['title'],28,48,'utf-8');
        $title = '' . $title[0] . '\r\n'.$title[1];
		
		
		$price = '售价：¥'.round($detail['mall_price']/100,2).'元， 原价：¥'.round($detail['price']/100,2).'元';
				
				
		$shopname_text = $users['nickname'] ? $users['nickname'] : $shop['shop_name'];
		$shopname_text = $shopname_text.'为您推荐';
		
		$portrait_thumb = $users['face'] ? $users['face'] : $shop['photo'];
		
		$codedata = array( 
			 'portrait' => array( 'thumb' => config_weixin_img($portrait_thumb), 'left' => 20,'top' => 720,'width' => 80,'height' => 80), 
			 'shopname' => array( 'text' => tu_msubstr($shopname_text,0,26, false), 'left' => 120,'top' => 720, 'size' => 18, 'width' => 560, 'height' => 30, 'color' => '#333' ), 
			 'thumb' => array( 'thumb' => config_weixin_img($detail['photo']), 'left' => 0,'top'=> 0, 'width' => 600, 'height' => 600 ), 
			 'qrcode' => array( 'thumb' => config_weixin_img($file), 'left' => 450,'top' => 720, 'width' => 100, 'height' => 100 ), 
			 'title' => array( 'text' => $title, 'left' => 20, 'top' => 620, 'size' => 16, 'width' => 650, 'height' => 30, 'color' => '#000'), 
			 'price' => array( 'text' => $price, 'left' => 20, 'top' => 680, 'size' => 20, 'color' => '#f20' ), 
			 'desc' => array( 'text' => '长按二维码识别', 'left' => 450, 'top' => 820, 'size' => 12, 'color' => '#666') 
		 );
		 
		$parameter = array( 'goods_id' => $goods_id, 'qrcode' => config_weixin_img($file), 'codedata' => $codedata,'mid' =>$uid,'codeshare'=>1);
		$goodscode = D('Goods')->createcode($parameter);
		return $goodscode;
    }
	
	
	//生成商品分销海报
	public function createcode($parameter){
	
		$name = date('Y/m/d/',time());
		$path = BASE_PATH.'/attachs/'.'poster/'.$name;
		$paths = '/attachs/'.'poster/'.$name;
		
        $goods_id = $parameter["goods_id"];//商品ID
        $qrcode = $parameter["qrcode"];
        $data = $parameter["codedata"];
        $mid = $parameter["mid"];
        $codeshare = $parameter["codeshare"];
		
		//创建保存目录  
		if(!is_dir($path)){
			//文件夹不存在，则新建  
			mkdir(iconv("UTF-8", "GBK", $path),0777,true);  
		} 
		
        $md5 = md5(json_encode(array("goods_id" => $goods_id, "title" => $data["title"]["text"],"price" =>$data["price"]["text"],"codeshare" => $parameter["codeshare"], "codedata" => $data, "mid" => $mid)));
        $file = $md5 . ".jpg";
		
		//p($parameter);die;
		
        if(!is_file($path . $file)){
            set_time_limit(0);
            @ini_set("memory_limit", "256M");
			
			
			$target = imagecreatetruecolor(600,850);
			$color = imagecolorAllocate($target, 255, 255, 255);
			imagefill($target, 0, 0, $color);
			imagecopy($target, $target, 0, 0, 0, 0, 600, 850);
			$target = $this->mergeText($target, $data["shopname"], $data["shopname"]["text"]);
			$thumb = preg_replace("/\\/0\$/i", "/96", $data["portrait"]["thumb"]);
			$target = $this->mergeImage($target, $data["portrait"], $thumb);
			$thumb = preg_replace("/\\/0\$/i", "/96", $data["thumb"]["thumb"]);
			$target = $this->mergeImage($target, $data["thumb"], $thumb);
			$qrcode = preg_replace("/\\/0\$/i", "/96", $data["qrcode"]["thumb"]);
			$target = $this->mergeImage($target, $data["qrcode"], $qrcode);
			$target = $this->mergeText($target, $data["title"], $data["title"]["text"]);
			$target = $this->mergeText($target, $data["price"], $data["price"]["text"]);
			$target = $this->mergeText($target, $data["desc"], $data["desc"]["text"]);
			imagepng($target, $path . $file);
			imagedestroy($target);

        }
        $img = $paths. $file;
        return $img;//返回
    }
	
	
	
    //添加图片
    public function createImage($imgurl){
        $config = D('Setting')->fetchAll();
        $count = str_replace($config["site"]["host"], BASE_PATH. "/", $imgurl);
        $imgurl = file_get_contents($count);
        return imagecreatefromstring($imgurl);
    }


    //合并图片
    public function mergeImage($target, $data, $imgurl){
        $img = $this->createImage($imgurl);
        $w = imagesx($img);
        $h = imagesy($img);
        imagecopyresized($target, $img, $data["left"], $data["top"], 0, 0, $data["width"], $data["height"], $w, $h);
        imagedestroy($img);
        return $target;
    }



    //合并文字
    public function mergeText($target, $data, $text, $center = false) {
        $font = "/Public/font/msyhl.ttc";
        $colors = $this->hex2rgb($data["color"]);
        $color = imagecolorallocate($target, $colors["red"], $colors["green"], $colors["blue"]);
        if($center){
            $fontBox = imagettfbbox($data["size"], 0, $font, $data["text"]);
            imagettftext($target, $data["size"], 0, ceil(($data["width"] - $fontBox[2]) / 2), $data["top"] + $data["size"], $color, $font, $text);
        }else{
            imagettftext($target, $data["size"], 0, $data["left"], $data["top"] + $data["size"], $color, $font, $text);
        }
        return $target;
    }


    //合并颜色
    public function hex2rgb($colour){
        if($colour[0] == "#"){
            $colour = substr($colour, 1);
        }
        if(strlen($colour) == 6){
            list($r, $g, $b) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        }
        else{
            if(strlen($colour) == 3){
                list($r, $g, $b) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
            }else{
                return false;
            }
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array( "red" => $r, "green" => $g, "blue" => $b );
    }   
	
	
	
	
	
	
	
	//获取首页轮播数据
	public function getScroll(){
		$config = D('Setting')->fetchAll();
		$limit = isset($config['goods']['limit']) ? (int)$config['goods']['limit'] : 6;
		$order = isset($config['goods']['order']) ? (int)$config['goods']['order'] : 1;
		switch ($order) {
            case '1':
                $orderby = array('create_time' =>'desc','order_id' =>'desc');
                break;
            case '2':
                $orderby = array('total_price' =>'desc');
                break;
            case '3':
                $orderby = array('order_id' =>'desc');
                break;
        }
		$list = D('Order')->order($orderby)->limit(0,$limit)->select();
		foreach($list as $k => $v){
            if($user = D('Users')->where(array('user_id'=>$v['user_id']))->find()){
                $list[$k]['user'] = $user;
            }
        }
        return $list;
    }
	
	
	//后台限购
	public function goodsLimitNum($goods_id,$uid,$num){
		
		$detail = M('goods')->find($goods_id);
		
		$goods_limit_num = (int)$detail['limit_num'];
		$order_goods = M('order_goods')->where(array('user_id'=>$uid,'goods_id'=>$goods_id))->select();
		foreach($order_goods as $k=> $v){
			$order = M('order')->where(array('order_id'=>$v['order_id']))->find();
			if($order['status'] >0){
				$limit_num += $v['num'];
			}
        }
		$limit_num = (int)$limit_num;
		
		if($goods_limit_num && $limit_num >= $goods_limit_num){
			$this->error = '当前商品限购【'.$goods_limit_num.'】件，您已经购买【'.$limit_num.'】件';
			return false;
		}
		
		$cha = $goods_limit_num - ($limit_num + $num);
		$limit = $goods_limit_num - $limit_num;
	
		if($goods_limit_num && $cha < 0){
			$this->error = '当前商品限购【'.$goods_limit_num.'】件，您最多再能购买【'.$limit.'】件';
			return false;
		}
		return true;
	}
	
		
		
	
	public function top_time($goods_id,$type){
		$config = D('Setting')->fetchAll();
		$goods = $this->find($goods_id);
		$shop = D('Shop')->find($goods['shop_id']);
		if(!$shop){
			$this->error = '没找到商家';
			return false;
		}
		$Users = D('Users')->find($shop['user_id']);
		if(!$Users){
			$this->error = '会员状态不正常';
			return false;
		}
		$money = $type * $config['goods']['top'] * 100;
		if($Users['money'] < $money) {
			$this->error = '您的会员账户余额不足，请先充值后操作';
			return false;
		}
		if(D('Users')->addMoney($Users['user_id'], -$money, '置顶商品ID【'.$goods_id.'】' . $type . '天')) {
			if($this->save(array('goods_id'=>$goods_id,'top_time'=>NOW_TIME + ($type*3600)))) {
				return true;
			}else{
				$this->error = '操作失败';
				return false;
			}
		}else{
			$this->error = '扣费失败';
			return false;
		}
   }
	
	//计算用户下单返回多少积分传2个参数，商品id商品类型
    public function get_forecast_integral_restore($id,$type){
        $config = D('Setting')->fetchAll();
		if($config['integral']['is_restore'] == 1){
			if($type == 'goods'){
				$Goods = D('Goods')->find($id);
				if($config['integral']['is_goods_restore'] == 1){
					if($config['integral']['restore_type'] == 1){
						$integral = $Goods['mall_price'];
					}elseif($config['integral']['restore_type'] == 2){
						$integral = $Goods['settlement_price'];
					}elseif($config['integral']['restore_type'] == 3){
						$integral = $Goods['mall_price']- $Goods['settlement_price'];
					}else{
						$integral = 0;
					}
				}else{
					return false;
				}
			}
			
			if($integral > 0){
				if($config['integral']['restore_points'] > 100){
					if($config['integral']['restore_points']){
						$integral = $integral - (($integral * $config['integral']['restore_points'])/100);
						return int($integral/100);
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		
    }
	
	//这里暂时没有判断多属性的问题，后期再判断
	public function check_add_use_integral($use_integral,$mall_price){
        $config = D('Setting')->fetchAll();
        $integral = $config['integral']['buy'];
		if($integral == 0){
			if($use_integral % 100 != 0) {
				$this->error = '积分必须为100的倍数';
				return false;
			}
			if($use_integral >= $mall_price){
				$this->error = '积分兑换数量必须小于'.$mall_price.','.'并是100的倍数';
				return false;
			}
		}elseif($integral == 10){
			if($use_integral % 10 != 0){
				$this->error = '积分必须为10的倍数';
			}
			if($use_integral*10 >= $mall_price){
				$this->error = '积分兑换数量必须小于'.($mall_price/10).','.'并是10的倍数';
				return false;
			}
		}elseif($integral == 100){
			if($use_integral % 1 != 0){
				$this->error = '积分必须为1的倍数';
				return false;
			}
			if($use_integral*100 >= $mall_price) {
				$this->error = '积分兑换数量必须小于'.($mall_price/100).','.'并是1的倍数';
				return false;
			}	
		}else{
			$this->error = '后台设置的消费抵扣积分比例不合法';
			return false;
		}
		return true;
    }

}