####头脑游戏 接口说明：

```
1. 登录接口
路径：/user/login
方法：post
参数：wxcode
返回：
    {
        code:200,
        data:{
            token:"xxxxxxxx",
            uid:'xxx'
        }
    }
```


```
2. 更新接口
路径：/user/update
方法：post
header: token
参数：
    avatar
    nickname
    gender
    city
    province
    country
返回：
    {
        code:200,
    }
```


```
3. 获取用户信息
路径：/user/info
方法：get
header: token
返回：
    {
        code:200,
        data:{
            user:{nick_name:xxx,gender:xxx,avatar_url:xxx,country:xxx,province:xxx,city:xxx,update_time:xxx}
            continues:12,
            wins:23
        }
    }
```

#####socket 通信接口说明

```
1. socket 通信说明：
    服务端每一段时间心跳， 默认2秒，返回数据{"type":"ping"}

```


```
2. lgoin 进入比赛
{type:login,token: "xxx",s:0 or xxx}
token: xxx为http 接口登录接口返回的token
s: 0/xxxx 分享出去的参数，http接口中的uid

双方推送消息：
    {
        "type": "login",
        "code": 200,
        "info": "",
        "data": {
            "1": {  // 参赛者的uid, 游戏本人或对手，如果第二个玩家进来，此处将对双方推荐两人信息。
                "ip": "192.168.31.164",
                "uid": 1,
                "avatar": "http://img.tandamao.com/avatar/efc1bac4d621f50f9e40c66e4f330c90.jpg",
                "nickname": "??朵朵朵朵朵…",
                "isfanzhu": false,  //是否是房主，true是房主， false 非房主
                "create_time": "2018-02-13 00:44:03"
            },
            "2": {  // 参赛者的uid, 游戏本人或对手，如果第二个玩家进来，此处将对双方推荐两人信息。
                "ip": "192.168.31.164",
                "uid": 2,
                "avatar": "http://img.tandamao.com/avatar/efc1bac4d621f50f9e40c66e4f330c90.jpg",
                "nickname": "??朵朵朵朵朵…",
                "isfanzhu": false,
                "create_time": "2018-02-13 00:44:03"
            }
        }
    }
说明, 返回 type=login，各状态描述：
    code 200 加入正常
    
    code 210 用户未登录，请先登录
    
    code 220 已经开战，请退出 
    如 {"type":"login","code":220,"info":"房主正在对战中","data":""}
```

```
3. 放弃比赛
{type:giveup}
// 执行与disconnect 相同逻辑，退出比赛
对手推送消息：
    {
        "type": "exit",
        "code": 200,
        "info": "用户退出",
        "data": {
            "uid": 2,
            "result": null
            }
    }

若游戏进行中，result 为当前游戏结局
```

```
4. 房主点击开始
{type:begin}
返回题目：
    {
        "code": 200,
        "type": "question",
        "info": "题目内容",
        "data": {
            "id": 5,        //题目唯一标识
            "title": "中国有多少个省", //题目内容
            "category": "地理类", // 题目类型
            "answers": "['32','44','55','11']", //选项组
            "correct_answer": 1, // 正确答案下标            
            "number": 2 // 题目序号
        }
    }
```

```
5.答题
{type:answered, choose:1}
返回
    {
        "type": "answer",
        "code": 200,
        "info": "回答结果",
        "data": {
            "num": 1,
            "result": {
                "1": { //用户uid
                    "corrects": "0", //回答正确个数
                    "scores": "0", // 得分
                    "win": 0 // 输赢标识， 1 赢了，-1 输了，0平局
                },
                "3": {//用户uid
                    "corrects": "0",
                    "scores": "0",
                    "win": 0
                }
            },
            "detail": {
                "1": {
                    "scores": 0,
                    "option": -1,
                    "status": false
                },
                "3": {
                    "scores": 0,
                    "option": -1,
                    "status": false
                }
            },
            "correct": 1
        }
    }

后答题完后，将此消息再次同步给先答题者。
option 为用户选择， -1 表示超时未答题，由系统直接判定为0分
当num等于5时，直接输出结果。 参考result

