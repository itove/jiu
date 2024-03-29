### 2023-11-04
#### 后端
* 奖项**再来一瓶**现在将显示为**10元兑一瓶**
* 奖品方案为**30酒-【十堰】-【流通】**的箱码现在只含两个**再来一瓶**
* 现在不再产生**门店兑奖服务费**
* 现在不再产生**业务员兑付奖**
* 现在不再产生**分销佣金**、**推荐代理商佣金**、**推荐门店佣金**等佣金

### 2023-10-26
#### 后端
* 现在顾客中奖的微信红包将直接进入微信余额，无需再手动提现

### 2023-03-15
#### 后端
* 新的`兑奖结算`接口
* 现在业务员每一笔兑付都会产生一条`业务员兑付`记录
* 新增页面`业务员兑付`
* `兑奖结算`接口现在检查门店兑付状态及服务门店兑付状态
#### 前端
##### 发布版本 3.2.1
* 修复`兑奖结算`页面typo导致服务门店兑付状态检查失效，可以重复兑付的bug
* `兑奖结算`页面防抖
##### 发布版本 3.2.0
* 移除`我的领用`，`新增领用`等页面
* 新增业务员业务员功能`门店兑付`，`门店兑付二维码`；新增仓管功能`兑奖核销`
* **现在仓管不能再直接扫描箱码出库。现在出库功能入口为`我`的页面中`出库`**

### 2023-03-14
#### 后端
* **现在新增奖项时须选择`类型`**
* 现在`推荐代理商佣金`及`推荐门店佣金`分别计算
* 删除`领用`表
* 删除`领用`相关接口
#### 前端
##### 发布版本 3.1.7
* 修复`兑奖结算`页面点击`确定`无法跳转的bug
* 修复商家`我的集兑`不显示数据的bug
##### 发布版本 3.1.6
* 重构`搜索`页面
* 首页`合作餐厅`和`销售门店`现在跳转到正确的页面
* `微信登录`页面防抖

### 2023-03-13
#### 后端
* `用户管理`不再显示昵称
* `用户管理`现在显示用户的创建时间
* `服务员扫码奖`现在每个产品单独设置

### 2023-03-10
#### 后端
* 用户表中`代金券`字段现在为无符号整型
* 改用`getStableAccessToken`接口以解决测试环境和生产环境`access_token`冲突的问题
* 现在`产品推荐`只向总公司开放
* `我的库存`不在显示产品佣金/分润等信息
* `销售订单`现在显示箱码，并可以搜索
* 优化微信登陆接口
* 统一微信登录接口及用户接口返回数据格式
#### 前端
##### 发布版本 3.1.5
* 现在`扫描入库`及`扫描退货`页面按钮文字为`返回`，点击将返回`我的`页面
* `餐饮消费`页面防抖
##### 发布版本 3.1.4
* 修复`餐饮消费`页面顾客姓名不显示的bug
##### 发布版本 3.1.3
* 现在登录失败会显示对应的提示
* 统一微信登录接口及用户接口返回数据格式
* 修复新用户二维码中姓名为`null`的bug

### 2023-03-06
#### 后端
* 零售订单产生时会向顾客短信。如果有对应的`门店奖`，也向门店发送短信
* 短信中现在包含跳转至小程序的链接
* 奖项`集齐3瓶兑一瓶`如果`门店奖`值为0，则门店不会获得累计
* 清空了ID 263名称`二张烙馍村`机构的`可提现金额`
* 销售订单/退货订单不再统计、显示代金券
* 修复错误的排序造成瓶码列表页报错的bug
* 现在`餐饮消费`餐厅收到的是折扣后的可提现金额
#### 前端
##### 发布版本 3.1.2
* 现在`我的信息`页面提交时如果手机号重复，会显示相应的提示
##### 发布版本 3.1.1
* 现在`订单`页面`餐饮订单`显示金额统计而非代金券统计
* 现在`餐饮订单详情`页显示`折扣`及`实际到帐`
* `商家管理员绑定`现在按名称搜索并筛选

### 2023-03-05
#### 后端
* 清空了3月2日后产生的订单、零售、库存、兑奖、集兑及领用数据
* 现在只有`限定餐厅`的关联瓶码才产生`服务员扫码奖`
* 兑奖表增加产品字段
#### 前端
##### 发布版本 3.0.19
* `商家钱包`页面中的`转入个人微信`更改为`转入我的钱包`
##### 发布版本 3.0.18
* 修复`我的集兑`页面显示过滤错误的bug
* `我的奖品`页面`再来一瓶`现在显示产品名称
* 抽奖页面样式调整
* 现在抽奖页面奖励金额及数量显示正确的数值
##### 发布版本 3.0.17
* `申请提现`页面防抖以避免出现重复提交
##### 发布版本 3.0.16
* 修复`我的集兑`页面不显示数据的bug
##### 发布版本 3.0.15
* `门店活动` `活动公告`等页面不再显示内容标题及日期
* `我的信息`页面不再显示`昵称`
* 修复部分手机兼容性问题导致字符串不匹配，无法跳转扫描页的bug
* 修复业务员功能不显示的bug

### 2023-03-04
#### 后端
* 新增`扫描退货`接口
* 现在可以通过`扫描退货`接口退货
    * 如果箱码当前所属机构不属于本商家则不能退货
    * 箱中已有单瓶售出则不能退货
* 退货不再流转代金券
#### 前端
##### 发布版本 3.0.13
* 现在可以通过商家功能`我要退货`退货
    * 如果箱码当前所属机构不属于本商家则不能退货
    * 箱中已有单瓶售出则不能退货
* 修复`我的代金券`不显示的bug
* 修复部分页面点击确定不能跳转的bug

### 2023-03-03
#### 后端
* 现在通过`商家管理员`功能`添加店员`时，如果商家类型为`餐厅`，被添加店员将被赋予`服务员`角色
* 现在不再裁减所上传**产品**图片的高度
* 生成新的用户分销海报
* 重构`集兑`接口
* 现在`集齐3瓶兑一瓶`每个产品单独计算
* 现在奖项`代金券`的`门店奖`为可提现金额
* 现在当箱内所有瓶码都售出时，该箱码对应产品的门店库存减1
* `箱码管理` `瓶码管理`页面不再显示密文
* 现在销售/退货/零售不再流转代金券
* 新增角色`餐厅管理员`
* `内容管理`中新增标签`门店活动`，包含此标签的最新一篇内容将作为小程序首页`门店活动`的内容

#### 前端
##### 发布版本 3.0.11
* 新的`产品详情`页面样式
* 现在点击`集3瓶兑一瓶`将显示用户当前累积的集兑列表及数量，每个产品单独计算
* `分销中心`页面不再显示`我的佣金`、`可提金额`
* 中奖页面现在显示正确的数量及金额
* `申请提现`页面键盘现在可以输入小数点
* 修复`申请提现`页面`可提金额`为0的bug
* 现在只对角色`餐厅业务员`开放`服务员登记`
* 不再显示`提现中`
* 重构文章展示页面
* 修复`转入个人微信`后不能跳转的bug

### 2023-02-28
#### 后端
##### 发布版本 3.0.5
* 实现箱码及瓶码一物一码
    * 通过`扫描二维码跳转微信小程序`，由中转页面判断并分发至具体操作页面
    * 二维码内容示例`https://hbljk.cn/wxqr?t=1&s=A0000003.5&e=NONyXcSXM9OKxg`，参数`t`为二维码类型，参数`s`为箱码/瓶码，参数`e`为加密后的箱码/瓶码信息
    * 编码规则: 
        * 箱码8位，前4位为base36，为了美观，最高位从`A`开始；后4位为十进制以增加可读性。范围百亿级，`26 * 36**3 * 10**4 - 1 = 12,130,559,999`。示例`ABCD1234`
        * 瓶码10位，由对应箱码和瓶号组成，示例`ABCD1234.5`
    * 加密:
        * 箱码/瓶码二维码内容均携带加密信息，业务请求会先验证解密是否成功并与对应箱码/瓶码相符
        * 采用`aes-128-gcm`加密算法，密钥统一，`iv`每个箱码/瓶码随机生成
    * 箱码瓶码生成
        1. 在后台`箱码管理`中点击`批量新增`，填入生成`数量`及`瓶每箱`并提交
        2. 系统自动生成指定数量的箱码及关联瓶码
        3. 箱码初始所属机构为总公司
    * 二维码下载
        1. 在后台`箱码管理`中点击`下载二维码`，填入生成`起始箱码`，`结束箱码`或`数量`二选一填写
        2. 系统自动生成压缩包并下载
    * 下载二维码内容(字符串)
        1. 在后台`箱码管理`中点击`导出字符串`，填入生成`起始箱码`，`结束箱码`或`数量`二选一填写
        2. 系统自动生成xlsx表格并下载
* 抽奖功能:
    * 逻辑: 
        1. 在`奖项设置`中新增奖项
        1. 新增`奖项方案`，将多个奖项打包成一个方案
        1. 总公司为`销售订单`指定`奖项方案`，订单审核通过后，订单中所有箱码即绑定该方案，箱中瓶码即随机分配该方案中一个奖项
    * 抽奖:
        * 顾客微信扫描瓶码产生零售订单并获得奖项
        * 顾客身份不能二次扫码
        * 服务员身份二次扫码可获得`服务员扫码奖`
        * **如果服务员先于顾客扫码，将视服务员为顾客，奖项由服务员获取**
        * 售出门店获得与顾客相同的奖项
        * 代金券及红包类奖项立即计入顾客/门店对应账户，无需兑奖
        * 奖项`再来一瓶`需到任一门店兑奖
        * 兑奖门店将获得`门店兑奖服务费`
        * 可在后台`系统设置`中设置`服务员扫码奖`及`门店兑奖服务费`金额
* 统一登录接口
    * 合并原顾客表及商家用户表，统一使用用户表
    * 根据机构类型及角色为用户分配对应权限
    * 所有表中的顾客现在引用用户表
    * 角色:
        * 增加角色`用户`，`商家管理员`，`仓管`，`业务员`，`服务员`等身份
        * 所有用户初始将获得`用户`，并归属于一个虚拟机构`顾客`
        * 所有新增用户仍有初始用户名，为用户的微信openid
        * 当用户机构变为商家时，将获得对应的商家身份
        * 可在后台用户管理中为指定用户添加`仓管`，`业务员`，`服务员`等身份
        * 门店推荐顾客时，门店`商家管理员`用个人身份推荐即可
        * 商家提现金额可转至`商家管理员`个人微信
    * 现在用户名及手机号均可用于后台登录，手机号优先，即如果用户A手机号为`13233334444`，用户B用户名同为`13233334444`，则用`13233334444`登录将验证用户A
* 订单
    * 总公司至代理商
        1. 由仓库在小程序端扫码生成订单
        1. 后台为订单指定`奖项方案`并审核订单
        1. 订单包含箱码所属机构变为对应代理商
    * 代理商至门店(含异业)
        * 门店在小程序端自行扫码入库
        * 若箱码所属机构非本商家上游，则商家不能入库
        * **若箱码绑定的`奖品方案` `限定餐厅`，则只有餐厅类型商家可入库**
* 零售
    * 现在零售以瓶为单位
    * 顾客微信扫描瓶码产生零售订单
    * 若瓶码对应的箱码所属机构非终端门店，则顾客不能扫码
    * **现在顾客获得的代金券/门店扣除的代金券为产品设定代金券除以箱瓶数**
* **现在所有佣金/分润都在零售订单身成时分发**
* 接入微信支付
* 业务员领用
* 上传图片默认裁减比例4:3
* 删除`新增零售`，`零售退货`，`修改密码`，`重置密码`等接口
* 增加`领用`，`兑奖`，`添加店员`，`仓管生成订单`，`绑定商家管理员`，`服务员登记`，`扫描瓶码`，`扫描箱码`等接口
* 重构`新增商家`等接口
* 从用户表中删除打款信息等字段
* 用户机构或角色发生变化，小程序端会强制退出
* 提现不再需要审核
* 提现金额将立即转入用户微信余额
* 删除`顾客管理`页面，现在在`用户管理`中统一管理
* 新增`兑奖记录`，`领用记录`，`箱码管理`，`瓶码管理`，`奖品方案`，`奖项设置`等页面
* 产品增加`瓶每箱`等字段
* 现在允许负库存

#### 前端
##### 发布版本 3.0.9
* 统一登录接口
    * 统一使用微信登录
    * 根据机构类型及角色为用户展示对应功能及界面
* 用户机构或角色发生变化，用户会被强制退出以应用新角色
* 升级用户二维码，现在一码多用:
    * 直接微信扫码，如果扫描方身份为餐厅，则将进入餐饮消费界面
    * 通过业务员功能`商家注册`扫描，将进入`商家注册`页面，被扫描用户将成为注册商家的`商家管理员`
    * 通过业务员功能`商家管理员绑定`扫描，将进入`商家管理员绑定`页面，被扫描用户将成为指定商家的`商家管理员`
    * 通过业务员功能`服务员登记`扫描，将进入`服务员登记`页面，被扫描用户将被赋予`服务员身份`
    * 通过商家功能`添加店员`扫描，将进入`添加店员`页面，被扫描用户所属机构将变为该商家
* 仓管出库
    1. 微信扫码箱码，如果当前用户具有`仓管身份`，跳转至`仓管出库`页面
    1. 所扫箱码显示在页面
    1. 选择`产品`
    1. 选择`代理商`
    1. 点击`生成订单`，预览信息，确认后生成订单
    1. 可反复扫描箱码，之前扫描的箱码及所选`产品`和`代理商`会缓存在本机
    1. 可点击箱码删除指定箱码
    1. 可点击`清空数据`清除缓存产品、代理商及所有箱码
    1. 也可点击`继续扫描`直接扫描下一个箱码
* 兑奖
    * 顾客的中奖信息将出现在`我的奖品`页面
    * 门店的中奖信息将出现在商家分组的`我的奖品`页面
    * 点击中奖信息可展示兑奖二维码
    * **门店向顾客兑付`再来一瓶`后，须扫描顾客兑奖二维码确认兑奖，否则顾客兑奖信息一直处于未兑奖状态**
    * 门店兑奖自己的`再来一瓶`时，向业务员展示对应兑奖二维码
    * 业务员向兑奖服务门店补充时，扫描`顾客兑奖`二维码
    * 业务员向门店兑付时，须先创建对应的领用单
* 商家钱包增加`转入个人微信`功能，可将商家可提现金额转入`商家管理员`个人微信
* 商家功能`更新门店坐标`，`我的库存`，`商家钱包`，`添加店员`，`机构信息`，`我的奖品`，`顾客兑奖`等现在只对`商家管理员`开放
* 新增`扫码`，`仓管出库`，`我的奖品`，`我的兑奖`，`我的领用`，`新增领用`，`服务员登记`，`商家管理员绑定`，`添加店员`，`兑奖`等页面
* 删除`商家登录`，`新增订单`，`新增零售`，`新增零售退货`，`零售退货详情`，`修改密码`，`重置密码`等页面
* 订单页面不再显示`零售退货`
* `合作报备`页面新增`餐厅`类型
* `门店`列表页面不再显示tab
* `订单`页面不再显示订单/退货/零售新增按钮
* `我的`页面功能分组，向所有用户显示用户功能，向商家用户显示商家功能，向业务员显示业务员功能
* `机构信息`页面不再显示收款信息
* 现在版本更新后可控制是否强制用户登录重新登录
* 新的首页样式

### 2023-02-04
#### 后端
##### 发布版本 2.0.0
* 增加`分润明细`
* 增加`佣金明细`
* 现在新增机构有默认图片
* 对接微信支付，用户可以提现到微信零钱
* 为用户生成分销海报
* 内容管理增加`用户协议`标签，选择该标签的文章将在用户登陆时的用户协议内容
* 用户通过海报分享的团队成员消费后，分享人可获得佣金
* 产品及库存分离
* 产品增加`分销佣金`、`介绍人佣金`、`代理商(异业)分润`、`区域代理商(异业)分润`、`门店(异业)分润`、`产品介绍`、`零售价`、`单瓶价格`等字段
* 新增`代理商(异业)`、`区域代理商(异业)`、`门店(异业)`等角色
* 用户可推荐代理商、门店、代理商(异业)、区域代理商(异业)、门店(异业)
* 餐厅提现现在由总部打款，代理商只负责审核
* `销售订单`等页面增加导出功能
* `销售订单`、`售后退货`、`商家提现`等页面增加金额合计
* 机构用户现在可以通过手机号找回密码
* 现在每笔佣金及分润都会生成记录
* 门店可以设置是否显示
* 新增页面`推荐管理`，总部及门店可发布自己的推荐信息
* 新增`合作报备`功能，提交人将成为推荐机构的推荐人
* 可以从`合作报备`页面快捷`新增机构`
* 现在上传图片会自动裁减并压缩
* 优化图片加载速度
* 行业增加权重字段
* 商家现在可以自己注册代理商/门店
* 新的分销逻辑：
    * 代理商的推荐人佣金在代理商进货时触发
    * 门店的推荐人佣金在门店顾客购买触发
    * 用户的推荐人佣金在用户购买时触发
* 新增异业分润逻辑：
    * 顾客购买时触发代理商(异业)分润
    * 顾客购买时触发区域代理商(异业)分润
    * 顾客购买时触发门店(异业)分润
* 分润/佣金7天后转入至`可提现金额`

#### 前端
##### 发布版本 2.0.0
* 首页样式调整
* 门店页面增加城市及行业筛选
* 门店页面列表行业筛选选项按权重排序
* 现在打开`我的二维码`页面会自动增加屏幕亮度以方便门店扫码；离开页面时会自动恢复
* 现在`我的二维码`页面交易完成后会振动提示
* 现在`我的二维码`页面交易完成后会跳转到`交易完成`页面
* 用户可以在`我的信息`页面编辑头像、昵称、电话
* 增加`用户协议`页面
* 用户首次登录填写个人信息时需勾选`用户协议`
* 用户可下载并分享海报，其他用户扫码登录后将加入分享人团队
* 首页增加产品推荐，点击进入产品详情页
* 产品详情页重构，详情显示后台`产品推荐`内容，如果内容为空，则显示`产品管理`中的`产品介绍`
* 门店详情页可显示门店推荐信息
* 新增页面`合作报备`，用户可提交报备信息
* 首页及门店页面增加门店搜索功能
* 门店页面列表现在按距离升序排序
* 新增页面`门店注册`、`分销中心`、`分润明细`等页面
* 机构信息页现在可以上传图片

### 2022-11-25
#### 后端
* 修复前端发起提现申请不计算餐厅折扣的bug

### 2022-11-23
#### 后端
* 禁用接口/api/orgs客户端分页，现在分页由后端控制，前端将显示所有数据

### 2022-11-06
#### 前端
* 门店列表现在默认加载180个机构

### 2022-10-28
#### 后端
* `我的信息`更名为`我的机构`
* 现在编辑`我的机构`可以填写`收款人`、`开户行`、`开户行地址`、`收款账号`等信息
* 现在`机构管理`编辑机构时可以查看其`收款人`、`开户行`、`开户行地址`、`收款账号`等信息，但无法编辑

### 2022-10-17
#### 后端
* `代金券明细`可以搜索机构和顾客了
* `售后退货`的`退货方`过滤现在只列出自己的下级机构
* `下级提现`的`申请方`过滤现在只列出自己的下级机构
* 现在点击`编辑推荐`，`我的信息`，`编辑产品`，`编辑提现申请`等页面的图片可以正确打开lightbox了
#### 前端
* 修复文章正文图片不显示的问题
* 门店详情页高亮显示电话和地址
* 发布版本1.2.1

### 2022-10-16
#### 后端
* 现在`销售订单`，`进货订单`，`售后退货`，`我的退货`，`下级提现`等页面可以搜索对方机构名称或商品名称了
* `我的提现`，`修改密码`，`我的信息`等页面不再显示搜索框

### 2022-10-14
#### 后端
* 现在推荐文章正文中可以插入图片了

### 2022-10-10
#### 前端
* 我的提现和我的代金券页面顶部card样式
* 推荐文章内容可以正常显示H5图片了
* 现在更新坐标前会弹出确认提示
* 现在只有餐厅新增申请提现时会显示折扣提示
* 现在游客身份和顾客身份点击门店列表跳转至门店详情页
* 发布版本1.2.0
#### 后端
* 现在不能再编辑顾客的代金券数值
* 现在不能再编辑顾客的微信openid
* 现在不能在后台新增顾客
* `用户管理`中不再显示当前登录的用户

### 2022-10-09
#### 前端
* 现在点击`门店详情`页的电话可以拨打电话
* 现在点击`门店详情`页的地址也能打开地图了
* 修复错误坐标导致地图无法加载的问题
* `我的信息`页面**机构名称**不再能修改
* `我的代金券`页面不再显示**可提金额**和**提现中**
* 发布版本1.1.9
#### 后端
* 现在提现通过不再产生双方的代金券明细记录
* 现在餐饮消费不再产生餐厅的代金券明细记录
* 修复错误坐标导致前端地图无法加载的问题
* `机构管理`现在为总公司账号显示所有类型的机构
* `机构管理`添加类型过滤器
* 系统标题更改为`剑南老窖营销平台`
* `我的信息`页面**机构名称**不再能修改
* `推荐管理`增加**企业简介**标签，含此标签的第一篇文章将显示在前端`企业简介`页面
* 修复权限导致打款图片无法上传的问题
* 现在上传图片后文件名称自动转换成唯一标识，以避免文件名重复或包含特殊字符等问题
* 现在上传图片大小不能超过1M

### 2022-10-08
#### 前端
* 现在`新增销售`、`新增退货`、`新增零售`如果库存不足会提示
* 现在`零售退货`、`餐饮消费`如果顾客代金券不足会提示
* 提现页面现在显示`可提额度`和`提现中`
* 详情页的日期能正常显示了
* 登录界面样式
* 视频页面现在同时显示企业简介
* 首页推荐不再显示标题
* 首页推荐现在正序排列
* 发布版本1.1.8
#### 后端
* 现在可以在推荐管理中编辑`企业简介`
* 现在`新增销售`、`新增退货`、`新增零售`如果库存不足会提示
* 清空除推荐、总公司、总公司用户外所有数据
* 总公司账号密码`admin:111`

### 2022-10-07
#### 前端
* 提现详情页现在显示打款图片
* 发布版本1.1.7
#### 后端
* 现在审核提现申请时可以上传打款图片了
* 除机构、用户、总公司产品外的所有数据清空
* 配置并启用服务器`hbljk.cn`
* 域名SSL证书及自动延期

### 2022-10-06
#### 前端
* 修复ios页面空白的问题
#### 后端
* 修复`新建提现申请`不显示的问题

### 2022-10-05
#### 前端
* 我的代金券页面现在显示`提现中`的金额
#### 后端
* 代金券明细页面现在显示`提现中`的金额
* 现在提交提现申请后金额会从`可提现金额`中扣除，加入`提现中`金额
* 现在提现申请审核，如果通过，金额从申请人`提现中`扣除，加入审核人`可提现金额`中；拒绝时，金额从申请人`提现中`扣除，返回至`可提现金额`中
* 现在新增`销售订单`和`售后退货`只能有一种商品
* 现在不能再编辑`销售订单`，`进货订单`，`售后退货`和`我的退货`了
* 现在`销售订单`，`进货订单`，`售后退货`和`我的退货`页面显示商品名称和数量
* 现在`销售订单`，`进货订单`，`售后退货`和`我的退货`页面不再显示状态

### 2022-10-04
#### 前端
* 门店/餐厅图片能正确显示了
* 产品图片能正确显示了
* 餐厅消费现在只显示顾客自己的订单
* 新增零售/新增零售退货/新增餐饮消费页面顾客姓名字体黑色加粗
* 用户可以修改本机构信息了
* 用户可以修改密码了
#### 后端
* 用户名可以数字开头了，最长30个字符
* 后台能正确显示上传并显示`产品`，`机构`和`推荐`的图片了
* 用户可以在后台编辑自己机构的信息了
* 用户可以在后台修改自己的密码了

### 2022-10-02
* 总公司现在可以编辑库存了

### 2022-09-26
* 代理商现在可以管理用户了
* 总公司只能管理总公司用户和代理商用户，代理商只能管理本机构用户和下级门店餐厅用户
* 机构添加`可提金额`字段
* 餐厅通过**餐饮消费**回收的金额加入餐厅`可提金额`，代理商审核**餐厅提现**回收的金额加入代理商`可提金额`
* 提现的金额从申请方`可提金额`中扣除
* 代金券明细现在显示`可提金额`

### 2022-09-21
* 代金券明细页面现在显示我的代金券余额，且仅对代理商/门店/餐厅显示
* 提现增加`实际到帐`字段，仅在餐厅的提现申请中显示
* 现在餐厅提现时会提示折扣率及实际到帐金额
* `提现申请`现在细分为`我的提现`和`下级提现`
* `订单`现在细分为`销售订单`和`进货订单`
* `退货`现在细分为`售后退货`和`我的退货`
* 去掉订单的审核步骤，创建后相应的库存及代金券将立即变化
* 去掉退货的审核步骤，创建后相应的库存及代金券将立即变化
* `酒零售`现在仅显示自己的交易
* `零售退货`现在仅显示自己的交易
* `餐饮消费`现在仅显示自己的交易
* 完善权限


### 2022-09-20
* 折扣现在与机构绑定，且只在**代理商**新建/查看/编辑下级机构时会显示，默认值95%
* 代理商现在可以创建/查看/编辑下级餐厅/门店了
* 总公司现在只能创建/查看/编辑**代理商**
* 餐厅现在可以查看自己的产品了
* 餐厅现在可以零售酒水了
* 代金券明细类型现在更简明
* 代理商现在可以为餐厅创建订单和退货了
* 门店/餐厅现在可以创建零售退货了
* `文章`更名为推荐，和`用户`一起整合进`系统管理`
* 订单/零售/退货/零售退货/代金券/提现申请/餐饮消费 等页面添加日期过滤
* 餐厅消费不再需要填写餐厅订单号和消费金额
