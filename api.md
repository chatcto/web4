# API列表
## 图灵机器人
- 官网地址 : http://www.tuling123.com/member/robot/index.jhtml
- API地址 : http://www.tuling123.com/openapi/api
- APIkey : 5ea93606aceb4328a4e97285979f6056
- 请求方式 : POST
- 返回类型 : JSON
- 参数列表 :
    - key : [必选][STRING] APIkey
    - info : [必选][STRING] 提问的问题
    - userid : [必选][STRING] 开发者给自己的用户分配的唯一标志
    - loc : [可选][STRING] 地理信息
## 今日热门新闻查询
- 官网地址 : https://market.aliyun.com/products/57126001/cmapi011178.html?spm=5176.730005.0.0.MTcPJK#sku=yuncode517800004
- API地址 : http://jisunews.market.alicloudapi.com/news/get
- 请求方式 : GET
- 返回类型 : JSON
- 参数列表 : 
    - channel : [必选][STRING] 频道
    - num : [可选][INT] 数量,默认10,最大40
    - start : [可选][INT] 起始位置,默认0
## 菜谱大全
- 官网地址 : https://market.aliyun.com/products/57126001/cmapi012028.html?spm=5176.730005.0.0.D4aYHK#sku=yuncode602800005
- API地址 : http://jisusrecipe.market.alicloudapi.com/recipe/search
- 请求方式 : GET
- 返回类型 : JSON
- 参数列表 : 
    - keyword : [必选][STRING] 关键词
    - num : [必选][STRING] 获取数量
## 智能问答
- 官网地址 : https://market.aliyun.com/products/57124001/cmapi013943.html#sku=yuncode794300000
- API地址 : http://jisuznwd.market.alicloudapi.com/iqa/query
- 请求方式 : GET
- 返回类型 : JSON
- 参数列表 : 
    - question : [必选][STRING] 提问的问题
## 常见疾病
- 官网地址 : https://market.aliyun.com/products/57126001/cmapi011522.html?spm=5176.730005.0.0.FQlQtY#sku=yuncode552200000
- API地址 : http://ali-disease.showapi.com/disease-type-list
- 请求方式 : GET
- 返回类型 : JSON
## 天气预报
- 官网地址 : https://market.aliyun.com/products/57096001/cmapi010812.html?spm=5176.730005.0.0.6xzZir#sku=yuncode481200005
- API地址 : http://ali-weather.showapi.com/ip-to-weather
- 请求方式 : GET
- 返回类型 : JSON
- 参数列表 : 
    - ip [必选][STRING] 用户ip
    - need3HourForcast [可选][STRING] 是否需要每小时数据的累积数组,实时数组最大长度为48,每天0点长度初始化为0
    - needAlarm	[可选][STRING] 是否需要天气预警,1为需要,0为不需要
    - needHourData	[可选][STRING] 是否需要每小时数据的累积数组,实时数组最大长度为48,每天0点长度初始化为0
    - needIndex	[可选][STRING] 是否需要返回指数数据,比如穿衣指数、紫外线指数等,1为返回,0为不返回
    - needMoreDay	[可选][STRING] 是否需要返回7天数据中的后4天,1为返回,0为不返回
