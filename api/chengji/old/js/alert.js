

var strHtml;
function ShowMessage(m,color){
        messageBox.style.filter="alpha(opacity=100)";
        messageBox.style.visibility="visible";

        message.style.visibility="visible";
        //录入内容
        strHtml="<div style='background:"+color+"; padding:3px 10px; height:45px; text-align:center;height:30px;line-height:30px;color:#FFFFFF; font-size:30px;'>"+m+"</div>";
        message.innerHTML=strHtml; 
        setTimeout("Close()",3500);//调用关闭的时间
}
var i=100;
function Close(){
    if(i<=0){
        message.style.visibility="hidden";
        strHtml="";
        
        //还原属性和参数
        i=100;
        messageBox.style.filter="alpha(opacity=100)";
        messageBox.style.visibility="visible";
        clearTimeout();
        return;
    }
    else{
        i--;
        messageBox.style.filter="alpha(opacity="+i+")";//刷新可见度，可见度越来越低
        setTimeout("Close()",10);//递归
    }
    return;
}

