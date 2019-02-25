-- 后台登录


wx.config({
            debug: false,
            appId: "{$signPackage.appId}",
            timestamp: '{$signPackage.timestamp}',
            nonceStr: '{$signPackage.nonceStr}',
            signature: '{$signPackage.signature}',
            jsApiList: [
              // 所有要调用的 API 都要加到这个列表中
              'getLocation',
            ]
        });
		wx.ready(function () {
            // 在这里调用 API
            wx.getLocation({
				type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
				success: function (res) {
					var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
					var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
					var result = polygon(arr[0],arr[1])
	        		if (!result) {
	        			$('#pay').attr('disabled',disabled)
	        			$('.show').show()
	        			alert('超过了目前可配送范围')
	        			return;
	        		}
				}
			});
        });
create table admin(
	id int primary key auto_increment not null,
	username char(30) not null,
	password char(30) not null
);
-- 用户表
insert into user(opendid) values('软小死')
create table user(
	id int not null primary key auto_increment,
	userid char(90) not null comment"用户ID",
	opendid char(240) not null comment"微信openid",
	num int not null comment"购买次数",
	type int not null comment"0标星，1已标星"
)
-- 商品标签
create table tip(
	id int not null primary key auto_increment,
	name char(240) not null,
	list int not null comment"标签顺序，由小到大",
	type int not null comment"1显示，0禁用"
)
-- 商品拥有的标签表
create table goodstip(
	id int not null primary key auto_increment,
	gid int not null comment"商品id",
	tid int not null comment"标签id"
)
-- 商品
create table goods(
	id int not null primary key auto_increment,
	name char(240) not null,
	money double(8,2) not null,
	newmoney double(8,2) not null,
	sale int not null comment"销量",
	img char(240) not null,
	sendmoney double(6,2) not null comment"运费,0免运费",
	number int not null comment"剩余数量",
	type int not null comment"0不限购，其余是限购数量"
)
insert into goods(ordernum,) values('馒头',5,30,2)
-- 订单
insert into orders(ordernum,aid,ordertime,sendtime,money) values(A2017112233335029111,2,151953415,151059341,60)
create table orders(
	id int not null primary key auto_increment,
	ordernum char(80) not null comment"订单号",
	aid int not null comment"地址id",
	did int not null comment"优惠券id，0就是不使用",
	money double(10,2) not null comment"总计",
	dismoney double(10,2) not null comment"",
	num int not null comment"份数",
	sendtime char(240) not null,
	ordertime bigint not null comment"下单时间",
	sendmoney double(5,2) not null comment"运费,0免运费",
	extra char(240) not null comment"备注",
	pingjia char(240) not null comment"评价",
	status int not null comment"0未支付,1制作中，2配送中，3已送达，4已评价"
)
alter table orders add print int comment"1代表需要打印"
-- 订单商品
insert into ordergoods(name,money,num,oid) values("红牛",7,1,4)
create table ordergoods(
	id int not null primary key auto_increment,
	oid int not null comment"订单id",
	gid int not null comment"商品id",
	num int not null comment"份数",
	money double(10,2) not null comment"总金额"
)
-- 地址
insert into address(name,tel,address) values('软小死',13189983330,meizhou)
create table address(
	id int not null primary key auto_increment,
	name char(240) not null,
	tel int not null,
	address char(240) not null,
	doornumber char(120) not null
)
-- 优惠券
create table coupon(
	id int not null primary key auto_increment,
	name char(120) not null,
	content int not null,
	timetype int not null comment"有效期类型",
	open bigint not null comment"有效期开始",
	close bigint not null comment"有效期结束",
	type int not null comment"0首单半价，1咖啡人，2满减满价，3满减满件",
	limi int not null comment"0无限制，限制领取次数",
    number int not null,
	link char(240) not null,
	extra char(240) not null,
	status int not null comment"0可使用,1已过期"
)
-- 可使用优惠券商品的类型
create table cougood(
	id int not null primary key auto_increment,
	cid int not null comment"优惠券id",
	tid int not null comment"商品类型id"
)
-- 用户拥有优惠券
create table mancoupon(
	id int not null primary key auto_increment,
	name char(120) not null,
	time bigint not null,
	type int not null,
	cid int not null,
	extra char(240) not null,
	status int not null comment"0可使用,1锁定，2已过期，3已使用"
)
