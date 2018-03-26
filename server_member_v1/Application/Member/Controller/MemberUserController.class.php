<?php
/**
 * Created by PhpStorm.
 * User: zhangkaifeng
 * Date: 2017/5/17
 * Time: 21:03
 */

namespace Member\Controller;


use Common\Controller\CommonController;

class MemberUserController extends CommonController
{
    public function _initialize()
    {
        parent::__initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 验证用户是否是会员接口
     */
    protected function checkopenid() {
        if (!$this->ukey or !$this->user_openid) {
            $data = array('code' => '1030', 'msg' => 'miss mobile params');
            returnjson($data, $this->returnstyle, $this->callback);
        }

        // 查询商户配置
        $mer_re = $this->getMerchant($this->ukey);
        if (!$mer_re) {
            $data = array('code' => '1001', 'msg' => 'invalid ukey!');
            returnjson($data, $this->returnstyle, $this->callback);
        }

        $uinfo = $this->getUserCardByOpenId($mer_re['pre_table'], $this->user_openid);

        if (is_array($uinfo)) {
            $data = array('code' => '200', 'msg' => 'success');
        } else if ($uinfo == '2000') {
            $data = array('code' => '2000', 'msg' => 'u are not our member!');
        }
        returnjson($data, $this->returnstyle, $this->callback);
    }

    /**
     * 验证用户是否登录
     * @param $prefix 查询表前缀
     * @param $openid 会员openid
     * @return string
     */
    protected function getUserCardByOpenId($prefix, $openid) {

        // 读取缓存
        //$uinfo = $this->redis->get('member:' . $openid);
        //if (!$uinfo) {
        $re = $this->checkUserExists($prefix, $openid);
        if (!$re) {
            return '2000';
        }
        /*else {
            if ($re['cookie'] != cookie($prefix . 'ck')) {
                return '2000';
            }
        }*/
        //$this->redis->set('member:' . $openid, json_encode($re));
        //} else {
        //$re = json_decode($uinfo, true);
        //}

        return $re;
    }


    /**
     * 通过openid来判断是否数据是否存在(用来避免同一个微信号绑定多个手机号)
     * @param $prefix
     * @param $openid
     * @return mixed
     */
    protected function checkUserExists($prefix, $openid) {
        $user = M('mem', $prefix);
        $re = $user->where(array('openid' => $openid))->find();
        return $re;
    }


    
    public function membersavebirth(){
       set_time_limit(0);
        $mer_re = $this->getMerchant($this->ukey);
        
        $db=M('mem',$mer_re['pre_table']);
        $start_time=I('start_time');
        $end_time=I('end_time');
        $map=array('getcarddate'=>array('between',$start_time.','.$end_time));
        $map['is_del']=array('NEQ',2);
        $map['_logic']='and';
        $arr=$db->field('id,cardno,idnumber,star,birthday')->where($map)->select();
        if($arr){
            $i=1;
            foreach($arr as $k=>$v){
                
                if($v['birthday'] != ''){
                    $birth[$i]=getConstellation(date('m',$v['birthday']),date('d',$v['birthday']));
//                     $birth[$i]=getIDCardInfo($v['idnumber']);
                    if($birth[$i] != ''){
                        $db->where(array('id'=>$v['id']))->save(array('star'=>$birth[$i]));
                        echo $db->_sql();echo "<br/>";
                    }
                }
                $i++;
            }
        }
        
        
    }
    
    
}