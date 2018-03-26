<?php
/**
 * 小程序配置
 */
namespace Commands\Controller;

use Common\Controller\CommonController;

class AppletController extends CommonController
{
    public $config;
    
    public function _initialize()
    {
        parent::__initialize(); // TODO: Change the autogenerated stub
        //活动id生成接口http://10.10.11.47:8080/rtmap-luck-web/api/generate/activity?platformType=MiniApps
        $this->config = array(
            'dev'  => array(//测试环境
                290 => array('name' => '皇庭小程序', 'image' => 'https://img.rtmap.com/img_little_program.7f51612.jpg', 'acticityid' => 'A03bpLoBm', 'acticityname' => '皇庭小程序')
            ),
            'prod' => array(//正式环境
                12540 => array('name' => '皇庭小程序', 'image' => 'https://img.rtmap.com/img_little_program.7f51612.jpg', 'acticityid' => 'A037p7j9D', 'acticityname' => '皇庭小程序')
            ),
        );
    }
    
    /**
     * 皇庭小程序 对接 MA4.0 -- 注券问题处理
     * http://localhost/member/index.php/Commands/Applet/getAppletConf
     */
    public function getAppletConf(){
        $marketId = I('marketId');
        
        if(empty($marketId))
        {
            $msg['status']=1051;
            echo returnjson($msg,$this->returnstyle,$this->callback);exit;
        }
        else
        {
            $res = array();
            if(C('DOMAIN') == 'http://mem.rtmap.com' || C('DOMAIN') == 'https://mem.rtmap.com')//正式环境
            {
                $res = $this->config['prod'][$marketId];
            }
            else
            {
                $res = $this->config['dev'][$marketId];
            }
            
            $msg = array('status'=>200,'data'=> $res);
        }
        
        returnjson($msg,$this->returnstyle,$this->callback);exit();
    }
}

?>
