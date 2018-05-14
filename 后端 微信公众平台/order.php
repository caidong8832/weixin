<?php
$openid = $_GET["openid"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>预订房间</title>
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/order.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
  </head>

  <body id="wrap" style="">
    <style>
      .deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}
    </style>
    <div class="banner">
      <div id="wrapper">
        <div id="scroller" style="float:none">
          <ul id="thelist"><img src="img/jiudian.jpg" alt="预订房间" style="width:100%">
          </ul>
        </div>
      </div>
      <div class="clr"></div>
    </div>
    <div class="cardexplain">
      <ul class="round">
        <li>
          <h2>预约房间</h2>
          <div class="text">
            杭州市XX酒店竭诚为您服务<br/>
          联系电话：0571-888888</div>
        </li>
      </ul>
      <form method="post" action="submit.php" id="form" onsubmit="return tgSubmit()">
        <ul class="round">
          <li class="title mb"><span class="none">请填写以下信息</span></li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>姓名</th>
                  <td><input type="text" class="px" placeholder="请输入姓名" id="name" name="name" value="">
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>性别</th>
                  <td><select style="line-height:35px;" id="sex" name="sex" class="dropdown-select"><option value="" selected="">请选择性别</option><option value="男">男</option><option value="女">女</option></select>
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>年龄</th>
                  <td><input type="text" class="px" placeholder="请输入年龄" id="age" name="age" value="">
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>手机</th>
                  <td><input type="text" class="px" placeholder="请输入手机" id="mobile" name="mobile" value="">
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>预约日期</th>
                  <td>
                    <select style="line-height:35px;" id="bookdate" name="bookdate" class="dropdown-select">
                      <option value="" selected="">请选择预约日期</option>
                      <?php
                      for ($i = 1; $i <= 6; $i++) {
                        $offset = strtotime("+".($i-1)." day");
                        $bDate = date("m月d日",$offset);
                        $optionString .= '<option value="'.$bDate.'">'.$bDate.'</option>';
                      }
                      echo $optionString;
                      ?>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
          <li class="nob">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
              <tbody>
                <tr>
                  <th>预约房型</th>
                  <td><select style="line-height:35px;" id="bookexpert" name="bookexpert" class="dropdown-select"><option value="" selected="">请选择预约房型</option><option value="标准间">标准间</option><option value="大床房">大床房</option><option value="钟点房">钟点房</option></select>
                  </td>
                </tr>
              </tbody>
            </table>
          </li>
        </ul>

        <div class="footReturn" style="text-align:center">
          <input type="hidden" name="openid" value="<?php echo $openid;?>">
          <input type="submit" style="margin:0 auto 20px auto;width:90%" class="submit" value="提交信息">
        </div>
      </form>
      <script>
        function showTip(tipTxt) {
          var div = document.createElement('div');
          div.innerHTML = '<div class="deploy_ctype_tip"><p>' + tipTxt + '</p></div>';
          var tipNode = div.firstChild;
          $("#wrap").after(tipNode);
          setTimeout(function () {
            $(tipNode).remove();
          }, 1500);
        }
        function tgSubmit(){
          var name=$("#name").val();
          if($.trim(name) == ""){
            showTip('请输入姓名')
            return false;
          }
          var sex=$("#sex").val();
          var age=$("#age").val();
          var patrn = /^[0-9]{1,2}$/;
          if (!patrn.exec($.trim(age))) {
            showTip('请输入年龄')
            return false;
          }
          var mobile=$("#mobile").val();
          if($.trim(mobile) == ""){
            showTip('请正确填写手机号码')
            return false;
          }
          var patrn = /^13[0-9]{9}$|^15[0-9]{9}$|^18[0-9]{9}$/;
          if (!patrn.exec($.trim(mobile))) {
            showTip('请正确填写手机号码')
            return false;
          }
          var bookdate=$("#bookdate").val();
          if($.trim(bookdate) == ""){
            showTip('请输入预约日期')
            return false;
          }
          var bookexpert=$("#bookexpert").val();
          if($.trim(bookexpert) == ""){
            showTip('请输入预约房型')
            return false;
          }
          return true;
        }
      </script>
    </div>

  </body>
</html>