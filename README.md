# loan-calculator
贷款计算器-用于比较等额本息与等额本金两种贷款方式的区别

## PHP版
### 前置依赖
- 系统已安装PHP，且PHP的bin目录在PATH路径下
- PHP版本 >= 7.0

### 使用命令
```php
php php/main.php --bj={本金} --ll={年利率} --qs={贷款期数} --cm={命令:非必填} --qse={提前还款期数:非必填}
```

### 使用举例
> 以贷款200w、年利率4.65%、贷款时间30年举例
- 计算两种贷款方式的详情，终端输出s
    - `php php/main.php --bj=2000000 --ll=0.0465 --qs=360`
- 计算两种贷款方式的详情，csv文件输出
    - `php php/main.php --bj=2000000 --ll=0.0465 --qs=360 --cm=detail2CSV`
- 计算两种还款方式已还总额相等的期数
    - `php php/main.php --bj=2000000 --ll=0.0465 --qs=360 --cm=zongErQiShu`
- 计算两种还款方式剩余本金相等的期数
    - `php php/main.php --bj=2000000 --ll=0.0465 --qs=360 --cm=benJinQiShu`
- 计算等额本息相比等额本金不亏损所需的最小投资回报年利率（需设定提前还款期数）
    - `php php/main.php --bj=2000000 --ll=0.0465 --qs=360 --cm=minTouZiLiLv --qse=120` 

## Golang版
