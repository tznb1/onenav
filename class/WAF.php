<?php //错误码定义,20开头XSS拦截,21开头SQL拦截,如有误杀自行删除相关检测代码!

foreach($_POST as $key =>$value){
    //拦截XSS
    if($XSS == 1 ){
        //自定义代码接口不过滤
        if( ($method ==='edit_root' && $key ==='footer') || ($method ==='edit_homepage' && ($key ==='head'||$key ==='footer' ))  ){
            continue;
        }
        if(preg_match('/<(iframe|script|body|img|layer|div|meta|style|base|object|input)/i',$value)){
            $code = 2001;
        }elseif(preg_match('/(onmouseover|onerror|onload)\=/i',$value)){
            $code = 2002;
        }
    }
    //拦截SQL注入
    if($SQL == 1 ){
        if(preg_match("/\s+(or|xor|and)\s+(=|<|>|'|".'")/i',$value)){
            $code = 2101;
        }elseif(preg_match("/select.+(from|limit)/i",$value)){
            $code = 2102;
        }elseif(preg_match("/(?:(union(.*?)select))/i",$value)){
            $code = 2103;
        }elseif(preg_match("/sleep\((\s*)(\d*)(\s*)\)/i",$value)){
            $code = 2105;
        }elseif(preg_match("/benchmark\((.*)\,(.*)\)/i",$value)){
            $code = 2106;
        }elseif(preg_match("/(?:from\W+information_schema\W)/i",$value)){
            $code = 2107;
        }elseif(preg_match("/(?:(?:current_)user|database|schema|connection_id)\s*\(/i",$value)){
            $code = 2108;
        }elseif(preg_match("/into(\s+)+(?:dump|out)file\s*/i",$value)){
            $code = 2109;
        }elseif(preg_match("/group\s+by.+\(/i",$value)){
            $code = 2110;
        }
    }
    
    if(!empty($code)){msgA(['code'=>$code,'msg'=>$code.':已拦截不合法参数！','key'=>$key,'Value'=>$value,'method'=>$method ]);}
}
