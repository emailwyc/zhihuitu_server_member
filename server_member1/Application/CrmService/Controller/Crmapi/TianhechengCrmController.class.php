<?php
/**
 * Created by PhpStorm.
 * User: zhangkaifeng
 * Date: 2017/4/25
 * Time: 09:54
 * 注意crm的傻逼之处！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
 */

namespace CrmService\Controller\Crmapi;


use Common\Controller\WebserviceController;
use CrmService\Controller\CommonController;
use CrmService\Controller\CrminterfaceController;

class TianhechengCrmController extends CommonController implements CrminterfaceController
{
    private $webservice;
    private $client;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $data['UserId']='BHDTHCD';//header部分
        $data['Password']='BHDTHCD';
        $admininfo=$this->getMerchant($this->ukey);
        $this->webservice= new WebserviceController($admininfo['pre_table'], 'http://tempuri.org/','CrmSoapHeader', $data);
//        $this->client=$this->webservice->soapClient('http://14.23.149.194:8877/PosWebService.asmx?wsdl');
//         $this->client=$this->webservice->soapClient('http://120.236.168.34:8998/PosWebService.asmx?wsdl');
//         $this->client=$this->webservice->soapClient('http://14.23.149.194:8998/PosWebService.asmx?wsdl');
        $this->client=$this->webservice->soapClient('http://120.236.168.34:8999/PosWebService.asmx?wsdl');
    }

    /**
     * @deprecated 根据卡号获取会员信息
     * @传入参数   key_admin、sign、card
     *
     */
    public function GetUserinfoByCard()
    {
        $card = I('card');
        if ($card == ''){
            returnjson(array('code'=>1030),$this->returnstyle,$this->callback);
        }
        $admininfo = $this->getMerchant($this->ukey);
        $db = M('mem', $admininfo['pre_table']);
        $finduser = $db->where(array('cardno'=>$card))->find();
        if ($finduser == null){
            returnjson(array('code'=>102),$this->returnstyle,$this->callback);
        }
        $userinfo= $this->webservice->sopaCall('GetVipCard', $this->client, array(array('vipId'=>$finduser['vipid'])));
//        dump($userinfo);
        if ($userinfo->GetVipCardResult == true) {
            $rt['cardno']=$userinfo->vipCard->CardCode;
            $rt['usermember']=$userinfo->vipCard->VipName;
            $rt['idnumber']=$userinfo->vipCard->IdCardCode;
            $rt['status']='';
            $rt['status_description']='';
            $rt['getcarddate']='';
            $rt['expirationdate']='';//到期时间
            $rt['birthday']=$userinfo->vipCard->Birthday;
            $rt['company']='';
            $rt['phone']=$userinfo->vipCard->Mobile;
            $rt['mobile']=$userinfo->vipCard->Mobile;
            $rt['address']=$userinfo->vipCard->Address;
            $rt['level']=$userinfo->vipCard->CardTypeId;
            $rt['sex']= $userinfo->vipCard->SexType == 1 ? 0 : 1;
            $rt['vipid']=$userinfo->vipCard->CardId;
            $sel=$db->where(array('cardno'=>$rt['cardno']))->find();
            if (null == $sel){
                $sv=$db->add($rt);
            }else{
//                $sv=$db->where(array('cardno'=>$rt['cardno']))->save($rt);//暂时注释
            }
            $datas['cardno']=$rt['cardno'];
            $datas['user']=$rt['usermember'];
            $datas['cardtype']=$rt['level'];
            $datas['status']=$rt['status'];
            $datas['status_description']='';
            $datas['getcarddate']='';//创建时间
            $datas['expirationdate']='';//到期时间
            $datas['birthday']=$rt['birthday'];
            $datas['company']='';
            $datas['phone']=$rt['phone'];
            $datas['mobile']=$rt['phone'];
            $datas['address']=$rt['address'];
            $datas['sex']=$rt['sex'];
            $datas['score']=$userinfo->vipCard->ValidCent;//剩余积分
            $datas['name']=$datas['user'];
            $datas['birth']=$datas['birthday'];
            $datas['idnumber']=$rt['idnumber'];
            
            if($sel){
                $datas['hobby']=json_decode($sel['hobby'],true);
                $datas['ismarry']=$sel['marriage'];
                $datas['email']=$sel['email'];
                $datas['income']=$sel['income'];
                $datas['jobid']=$sel['career'];
            }else{
                $datas['hobby']='';
                $datas['ismarry']='';
                $datas['email']='';
                $datas['income']='';
                $datas['jobid']='';
            }
            $msg['code']=200;
            $msg['data']=$datas;
            returnjson($msg,$this->returnstyle,$this->callback);
        }else{
            returnjson(array('code'=>104, 'data'=>$userinfo->msg),$this->returnstyle,$this->callback);
        }
    }

    /**
     * @deprecated 根据手机号获取会员信息
     * @传入参数  key_admin、sign、mobile
     */
    public function GetUserinfoByMobile()
    {
        $mobile = I('mobile');
        if ($mobile == ''){
            returnjson(array('code'=>1030),$this->returnstyle,$this->callback);
        }
        $admininfo = $this->getMerchant($this->ukey);
        $db = M('mem', $admininfo['pre_table']);
        $cardinfo= $this->webservice->sopaCall('GetVipCardItem', $this->client, array(array('Mobile'=>$mobile)));
//        dump($userinfo);
        if ( isset($cardinfo->VipCardItems->VipCardItem->CardId) && isset($cardinfo->VipCardItems->VipCardItem->CardCode) ) {//傻逼，GetVipCardItemResult字段，不管查询正确与否，都是返回true
            $userinfo= $this->webservice->sopaCall('GetVipCard', $this->client, array(array('vipId'=>$cardinfo->VipCardItems->VipCardItem->CardId)));//傻逼，查询手机号只返回卡号，卡内码，会员名
            $rt['cardno']=$userinfo->vipCard->CardCode;
            $rt['usermember']=$userinfo->vipCard->VipName;
            $rt['idnumber']=$userinfo->vipCard->IdCardCode;
            $rt['status']='';
            $rt['status_description']='';
            $rt['getcarddate']='';
            $rt['expirationdate']='';//到期时间
            $rt['birthday']=$userinfo->vipCard->Birthday;
            $rt['company']='';
            $rt['phone']=$userinfo->vipCard->Mobile;
            $rt['mobile']=$userinfo->vipCard->Mobile;
            $rt['address']=$userinfo->vipCard->Address;
            $rt['level']=$userinfo->vipCard->CardTypeId;
            $rt['sex']= $userinfo->vipCard->SexType == 1 ? 0 : 1;
            $rt['vipid']=$userinfo->vipCard->CardId;
            $sel=$db->where(array('cardno'=>$rt['cardno']))->find();
            if (null == $sel){
                $sv=$db->add($rt);
            }else{
//                $sv=$db->where(array('cardno'=>$rt['cardno']))->save($rt);暂时注释
            }
            $datas['cardno']=$rt['cardno'];
            $datas['user']=$rt['usermember'];
            $datas['cardtype']=$rt['level'];
            $datas['status']=$rt['status'];
            $datas['status_description']='';
            $datas['getcarddate']='';//创建时间
            $datas['expirationdate']='';//到期时间
            $datas['birthday']=$rt['birthday'];
            $datas['company']='';
            $datas['phone']=$rt['phone'];
            $datas['mobile']=$rt['phone'];
            $datas['address']=$rt['address'];
            $datas['sex']=$rt['sex'];
            $datas['score']=$userinfo->vipCard->ValidCent;//剩余积分
            $msg['code']=200;
            $msg['data']=$datas;
            returnjson($msg,$this->returnstyle,$this->callback);
        }else{
            returnjson(array('code'=>104, 'data'=>$userinfo->msg),$this->returnstyle,$this->callback);
        }
    }

    /**
     * @deprecated  创建会员
     * @传入参数  key_admin、sign、mobile、sex、idnumber、name
     */
    public function createMember()
    {
        $params[0]['Mobile'] = I('phone') ? I('phone') : I('mobile');
        $params[0]['VipName'] = I('name');
        if (in_array('', $params[0], true)) {
            returnjson(array('code'=>1030),$this->returnstyle,$this->callback);
        }
        $params[0]['IdNumber'] = I('idnumber');
        $params[0]['Sex'] = I('sex') == 1 ? 0 : 1;
        $params[0]['BirthDay'] = I('birth');//date('Y-m-d');
        $params[0]['StoreCode'] = 'JT002';//门店，固定
        
        $result= $this->webservice->sopaCall('VipRegisterInfo', $this->client, $params);
        $admininfo = $this->getMerchant($this->ukey);
        if ($result->VipRegisterInfoResult == true){
            $rt['cardno']=$result->VipCode;
            $rt['usermember']=$params[0]['VipName'];
            $rt['idnumber']=$params[0]['IdNumber'];
            $rt['status']='';
            $rt['status_description']='';
            $rt['getcarddate']='';
            $rt['expirationdate']='';//到期时间
            $rt['birthday']=$rt['birthday']=$params[0]['BirthDay'];
            $rt['company']='';
            $rt['phone']=$params[0]['Mobile'];
            $rt['mobile']=$params[0]['Mobile'];
            $rt['address']='';
            $rt['level']=$result->VipType;
            $rt['sex']= $params[0]['Sex'];
            $rt['vipid'] = $result->VipId;
            $db = M('mem', $admininfo['pre_table']);
            $find = $db->where(array('cardno'=>$rt['cardno']))->find();
            if (null == $find){
                $sv=$db->add($rt);
            }else{
                $sv=$db->where(array('cardno'=>$rt['cardno']))->save($rt);
            }

            $userinfo= $this->webservice->sopaCall('GetVipCard', $this->client, array(array('vipId'=>$result->VipId)));

            $datas['cardno']=$rt['cardno'];
            $datas['vipid']=$result->VipId;
            $datas['user']=$rt['usermember'];
            $datas['cardtype']=$result->VipType;
            $datas['status']='';
            $datas['status_description']='';
            $datas['getcarddate']='';//创建时间
            $datas['expirationdate']='';//到期时间
            $datas['birthday']=$rt['birthday'];
            $datas['company']='';
            $datas['phone']=$rt['phone'];
            $datas['mobile']=$rt['phone'];
            $datas['address']=$userinfo->vipCard->Address;
            $datas['sex']=$rt['sex'];
            $datas['score']=$userinfo->vipCard->ValidCent;//剩余积分
            $msg['code']=200;
            $msg['data']=$datas;
            returnjson($msg,$this->returnstyle,$this->callback);
        }else{
            returnjson(array('code'=>104, 'data'=>$result->msg),$this->returnstyle,$this->callback);
        }
    }

    /**
     * @deprecated  修改会员信息
     * @传入参数  key_admin、sign、mobile、sex、idnumber、name、cardno
     */
    public function editMember()
    {
        $cardno=I('cardno');
        //获取卡内码ID.傻逼
        $admininfo = $this->getMerchant($this->ukey);
        $db = M('mem', $admininfo['pre_table']);
        $sel=$db->where(array('cardno'=>$cardno))->find();
        
        //配置传入参数
        $params['CardId']=$sel['vipid'];
        $params['Sex'] = I('sex');
        $params['Jobid']=I('jobid');
        $params['Address']=I('address');
        $params['Email']=I('email');
        $params['Married']=I('ismarry');
        $hobby=I('hobby');
        if($hobby){
            $params['Hobby']=implode( ";",$hobby);
        }
        $params['Income']=I('income');
        $params['Worknuit']=I('company');
        $params['Mobile']=I('mobile');
        $params['VipName']=I('name');
        $params['Idcard_code']=I('idnumber');
        $data[0]['vipInfo']=$params;
        writeOperationLog(array('tianhecheng_save_data',$params),'zhanghang');
        //调用修改接口
        $result= $this->webservice->sopaCall('UpdateVipInfo', $this->client, $data);
        writeOperationLog(array('tianhecheng_return_update',$result),'zhanghang');
        
        if($result->UpdateVipInfoResult == true){
            $save_data['marriage']=I('ismarry');
            $save_data['usermember']=I('name');
            $save_data['idnumber']=I('idnumber');
            $save_data['birthday']=I('birth');
            $save_data['address']=I('address');
            $save_data['hobby']=json_encode($hobby);
//             $save_data['mobile']=I('mobile');
            $save_data['email']=I('email');
            $save_data['income']=I('income');
            $save_data['career']=I('jobid');
            unset($save_data['cardno']);
            
            writeOperationLog(array('tianhecheng_arr_save',$save_data),'zhanghang');
            $db->where(array('cardno'=>$cardno))->save($save_data);
            
            $msg['code']=200;
            
        }else{
            $msg['code']=104;
            $msg['data']=$this->msg;
        }
        returnjson($msg,$this->returnstyle,$this->callback);
    }

    /**
     * @deprecated  积分扣除
     * @传入参数  key_admin、sign、cardno、scoreno、why
     */
    public function cutScore()
    {
        $msg=$this->commonerrorcode;
        $params['key_admin']=I('key_admin');
        $params['cardno']=I('cardno');
        $params['scoreno']=abs(I('scoreno'));
        $params['why']=I('why');
        //$params['scorecode']=I('scorecode');
        //$params['membername']=I('membername');
        $sign=I('sign');
        if (in_array('',$params)){//获取的参数不完整
            $msg['code']=1030;
        }else {
            $admininfo= $this->getMerchant($params['key_admin']);
            $db = M('mem', $admininfo['pre_table']);
            $find = $db->where(array('cardno'=>$params['cardno']))->find();
            $data[0]['vipId'] = $find['vipid'];
            $data[0]['updateCent'] = -($params['scoreno']);
            $this->changescore($data, $params['cardno'], $params['why'], 1);
        }
        returnjson($msg,$this->returnstyle,$this->callback);
    }

    /**
     * @deprecated  积分添加
     * @传入参数  key_admin、sign、cardno、scoreno、scorecode、why、membername
     */
    public function addintegral()
    {
        $msg=$this->commonerrorcode;
        $params['key_admin']=I('key_admin');
        $params['cardno']=I('cardno');
        $params['scoreno']=abs(I('scoreno'));
        $params['why']=I('why');
        //$params['scorecode']=I('scorecode');
        //$params['membername']=I('membername');
        $sign=I('sign');
        if (in_array('',$params)){//获取的参数不完整
            $msg['code']=1030;
        }else {
            $admininfo= $this->getMerchant($params['key_admin']);
            $db = M('mem', $admininfo['pre_table']);
            $find = $db->where(array('cardno'=>$params['cardno']))->find();
            $data[0]['vipId'] = $find['vipid'];
            $data[0]['updateCent'] = ($params['scoreno']);
            $this->changescore($data, $params['cardno'], $params['why'], 2);
        }
        returnjson($msg,$this->returnstyle,$this->callback);
    }

    /**
     * @deprecated 用户积分详细列表
     */
    public function scorelist()
    {
        $cardno=I('cardno');
        $time_Begin=I('startdate');
        $time_end=I('enddate');
        $admininfo = $this->getMerchant($this->ukey);
        $db = M('mem', $admininfo['pre_table']);
        $sel=$db->where(array('cardno'=>$cardno))->find();
        
        $params[0]['vipid']=$sel['vipid'];
        $params[0]['time_Begin']=date('Y-m-d',$time_Begin);
        $params[0]['time_End']=date('Y-m-d',$time_end+86400);
        
//         $client=$this->webservice->soapClient('http://120.236.168.34:8999/PosWebService.asmx');
        $result= $this->webservice->sopaCall('GetVipScoreItem', $this->client, $params);
        
        if($result->GetVipScoreItemResult == true){
            $result_arr=$result->VipScoreItems->VipScoreItem;
            foreach($result_arr as $k=>$v){
                $data['description']=$v->Proctype;
                $data['date']=$v->Procdate;
                $data['score']=$v->socre;
                $return_data[]=$data;
            }
            $msg['code']=200;
            $msg['data']=array(
                'cardno'=>$cardno,
                'scorelist'=>$return_data
            );
        }else{
            $msg['code']=104;
            $msg['data']=$result->msg;
        }
        returnjson($msg,$this->returnstyle,$this->callback);
    }

    /**
     * @deprecated 欧亚卖场
     * @传入参数 key_admin、sign 、skt、Jlbh、md
     */
    public function billInfo()
    {

    }


    private function changeScore(array $params, $cardno, $remark, $cutadd)
    {
        $params[0]['updateType']=1;
        $params[0]['storeCode']='JT002';
        $params[0]['posId']=1;
        $params[0]['billId']=date('His').rand(1000, 9999);
        $params[0]['Other']=0;
        $result= $this->webservice->sopaCall('UpdateVipCent', $this->client, $params);
        if ($result->UpdateVipCentResult == true) {
            $data['cardno']=$cardno;
            $data['scorenumber']=$params[0]['updateCent'];
            $data['why']=$remark;
            $data['scorecode']='';
            $data['cutadd']=$cutadd;
            $admininfo=$this->getMerchant($this->ukey);
            $db=M('score_record',$admininfo['pre_table']);
            $data['store']='';
            $add=$db->add($data);
            if ($add){
                $msg['code']=200;
            }else{
                $msg['code']=200;
                $msg['data']='数据保存错误';
            }
            returnjson($msg, $this->returnstyle, $this->callback);
        }else{
            returnjson(array('code'=>104, 'data'=>$result->msg), $this->returnstyle, $this->callback);
        }
    }

    public function GetUserinfoByOpenid(){

    }

}