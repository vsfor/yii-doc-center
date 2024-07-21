<?php
echo 'GET:<br>';
var_dump($_GET);
echo '<br>POST:<br>';
var_dump($_POST);
echo '<br><hr>open wechat login test<br>';
	

$baseUrl = 'https://open.weixin.qq.com/connect/qrconnect?';
$params = [
	'appid' => 'wxf45f89b87d87eaa1',
	'redirect_uri' => 'http://ydc.jeen.wang/static/logint/topen.php',
	'response_type' => 'code',
	'scope' => 'snsapi_login',
	'state' => 'some diy msg',
];

$url = $baseUrl . http_build_query($params) . '#wechat_redirect';

if (!isset($_GET['code'])) :
?>
<style>
	.test-link-btn {
		display:block;
		width:120px;
		height:20px;
		padding:5px 10px;
		margin:20px;
		line-height:20px;
		text-align:center;
		border:1px solid #777;
		border-radius:5px;
		text-decoration:none;
		color:#38bb04;
		font-size:16px;
	}
</style>
<a href="<?php echo $url; ?>" class="test-link-btn">去授权登录</a>	
<?php else: ?>
<BR><BR>5分钟内使用code 获取微信授权用户信息
<?php endif; ?>

