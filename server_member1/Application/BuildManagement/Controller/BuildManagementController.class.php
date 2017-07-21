<?php
/**
 * 建筑物管理C端
 */
namespace BuildManagement\Controller;

use Common\Controller\CommonController;
use Common\Controller\VerifySignController;

class BuildManagementController extends CommonController {


    private $merchant;
    public function _initialize()
    {
        parent::__initialize(); // TODO: Change the autogenerated stub
        $this->merchant = $this->getMerchant($this->ukey);
    }

    /**
     * 商场（建筑物）简介
     * build 建筑物id
     * key_admin 商户key
     */
    public function buildProfile()
    {
        $params['build'] = I('build');
        $params['key_admin'] = I('key_admin');
        if (in_array('', $params)) {
            $this->assign(array('errorcode'=>1))->display('buildmanagement:error');//参数不全
        }else{
            $admininfo = $this->getMerchant($this->ukey);
            if (empty($admininfo['id'])){
                $this->assign(array('errorcode'=>2))->display('buildmanagement:error');//admin表id不存在，虽然这个判断似乎可以省略
            }
            $db = M('buildid', 'total_');
            $sel = $db->where(array('adminid'=>$admininfo['id'], 'buildid'=>$params['build']))->find();
            if ($sel && !empty($sel['url'])) {
                $url = trim($sel['url']);
                if (strpos($url, 'http://') === 0){
                    header('Location:'. $url);
                }else{
                    $this->assign(array('errorcode'=>4))->display('buildmanagement:error');//URL格式错误
                }
            }else{
                $this->assign(array('errorcode'=>3))->display('buildmanagement:error');//找不到数据或URL字段为空
            }
        }
    }


    /**
     * 获取建筑物信息
     */
    public function getLongitudeLatitude()
    {
        $params['buildid'] = I('buildid');
        $params['key_admin'] = I('key_admin');
        if (in_array('', $params)) {
            returnjson(array('code'=>1030),$this->returnstyle,$this->callback);
        }
        $db = M('total_buildid');
        $result = $db->where(array('adminid' => $this->merchant['id'], 'buildid'=>$params['buildid'], 'is_del' => 2))->find();
        if ($result){
            returnjson(array('code'=>200, 'data'=>$result),$this->returnstyle,$this->callback);
        }else{
            returnjson(array('code'=>102),$this->returnstyle,$this->callback);
        }
    }


    /**
     * 用商户的商铺号，搜索商铺
     */
    public function getpoi()
    {
        $params = I();
        $params['buildid'] = I('buildid');
        $params['key_admin'] = I('key_admin');
        $params['poinumber'] = I('poinumber');
        $sign = $params['sign'] = I('sign');
        if (in_array('', $params)) {
            returnjson(array('code'=>1030),$this->returnstyle,$this->callback);
        }
        unset($params['sign']);
        $checksign = $this->checkParams($params, $params['key_admin'], $sign);
        if ($checksign !== true){
            returnjson(array('code'=>$checksign),$this->returnstyle,$this->callback);
        }
        $admininfo = $this->getMerchant($this->ukey);
        $db = M( $admininfo['pre_table'].'map_poi_'.$params['buildid'] , '', 'DB_CONFIG2');
        $sel = $db->where(array('poi_number'=>$params['poinumber']))->order('sorting asc')->select();
        if ( $sel ) {
            returnjson(array('code'=>200, 'data'=>$sel),$this->returnstyle,$this->callback);
        }else{
            returnjson(array('code'=>102),$this->returnstyle,$this->callback);
        }
    }
}

?>