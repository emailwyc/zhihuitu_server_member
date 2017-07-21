<?php
/**
 * wechat模块状态码为4000——4999
 * #1-50 状态码为临时状态码，不要定义，
 * 支付模块状态码为5000——5999！！！！！！！！！！！
 * 营销平台券、奖品状态码：1500——1999
 */
return array(
    'ERROR_CODES'=>array(
        '200'=>'success',
        '100'=>'Incomplete_parameters',
        '101'=>'interface_error',
        '102'=>'cnfrd',
        '103'=>'notvip',
        '104'=>'error',
        '105'=>'noratio',
        '106'=>'finish',
        '107'=>'finish',
        '108'=>'ishaveorder',
        '109'=>'nohaveorder',
        '110'=>'adminenable',
        '1000'=>'systemerror',
        '111'=>'requesttypeerror',
        '112'=>'insertdataerror',
		'113'=>'Shopping_information_is_not_configured',
        '1101'=>'nocar',
        '1102'=>'nocar',
        '1103'=>'waspay',
        '1001'=>'keyadminisnull',
        '1002'=>'signfaild',
        '1003'=>'nopermissions',
		'1008'=>'alreadyexists',
        '1010'=>'connectmysqlerror',
        '1011'=>'querysqlerror',
        '1013'=>'timeerror',
        '1014'=>'mobileisused',
        '1015'=>'membernickalreadyregister',
        '1016'=>'cannotgetcardnumber',
        '1020'=>'connectftperror',
        '1021'=>'ftpusererror',
        '1022'=>'scoreisling',
        '1023'=>'scoreinvalid',
        '1030'=>'Incomplete_parameters',
        '1031'=>'invalidcheckcode',
        '1040'=>'filetypeerror',
        '1041'=>'filesize',
        '1042'=>'filenameerror',
        '1043'=>'filehouzhui',
        '1044'=>'filesavefaild',
        '2000'=>'nouser',
        '2001'=>'userisalready',
		'2003'=>'thephonenumberwasregistered',
		'2004'=>'noconfigregisterform',
		'2005'=>'noconfigupdateform',
        '3000'=>'othererror',
        '1012'=>'wsalreadyvip',
        '307'=>'noprize',
        '4000'=>'isnotjson',
        '4001'=>'wechat_Incomplete_parameters',
        '4002'=>'adminerror',
        '4003'=>'wechaterror',
        '317'=>'novoucher',
        '319'=>'scorenoenouth',
        '309'=>'activitydisable',
        '304'=>'returnscorefaild',
		'500'=>'passworderror',
		'501'=>'twopasswordatypism',
		'502'=>'overtime',
        '1100'=>'thestringlengthisnotenouth',
        '5000'=>'payerror',
        '1045'=>'wassigned',
        '1046'=>'nosign',
        '1047'=>'buildidnotadminid',
        '1048'=>'pleasewait',
        '1051'=>'parametersfaild',
		'1049'=>'bodycodeerror',
		'1050'=>'phonenumberisnotcorrect',
		'1057'=>'scoredeficiency',
        '1052'=>'mimeerror',
        '1053'=>'typeinputerror',
        '1054'=>'nouploadfile',
		'1055'=>'cardnonoconsistent',
        '1009'=>'Datatoomuch',
        '1017'=>'feifacaozuo',
        '1018'=>'userfrozen',
        '1019'=>'ordernumbenotempty',
        '1024'=>'alreadyget',
        '1025'=>'noreceive',
        '1026'=>'duetime',
        '1027'=>'timelow',
        '1028'=>'receivefaild',
        '1032'=>'selfreceive',
        '1056'=>'timeout',
        '4007'=>'havetfollow',
        '4006'=>'followed',
        '1081'=>'typeerror',
        '4008'=>'alreadyrelation',
        '4009'=>'alreadyordernum',
        '1083'=>'onlyone',
//         '1082'=>'donoterror',   这个错误码不要用！！！！！！！！！！
        '1004'=>'param_error',
        '1033'=>'UnderPrizes',
        '1035'=>'resultisnull',
        '1034'=>'nothingtodo',
        '4010'=>'ProviderAccessTokenerror',
        '4011'=>'agentid',
        '1111'=>'blackuser',
        '1095'=>'youhavethecard',
        '1096'=>'thismicrosignalhascard',
        '1097'=>'mobileisnotcard',
        '5001'=>'amountsmall',
        '1500'=>'verifyfail',
        '1501'=>'paytypeerror',
        '1502'=>'menkan',
        '1503'=>'levelerror',
    ),
);
