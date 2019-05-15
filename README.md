# juejinxiaoce  

用php写的掘金小册子的下载脚本


## 使用准备

下载之后，应当需要执行一下`composer update`

因为使用了`composer`，且自定义了命名空间，可参考`composer.json`文件


## 使用说明  

1. 将根目录下的`.env_example`文件重命名为`.env`  
2. 修改配置信息，填写登录后获取到的`client_id,token,uid`字段,并填入要下载的小册子ID。  
3. 然后执行`php index.php`即可

目录读写权限自己注意，下载的文件会保存在与`index.php`文件同级的`output`目录下

默认同时生成html格式和markdown格式

## 随手记一下加深记忆  

移除了`.env`之后又手贱把`.env`提交了,还是带着uid和tokend的...  

```
# 软回滚走一波
git reset HEAD ^
# 再删一次
git rm --cache .env
# 提交一下
git commit -am 'v.12'

```


## 版本记录

### v1.0 第一次提交  

### v1.1 排版排了一下，加了一个env  

`.env`文件里面如果值包括了`=` 那么这个方法会抛出异常， 必须加上双引号.

### v1.2  

把`.env`文件从git中删掉了,`git rm --cache .env`  

放了一个`.env_example`文件,需要自行将其重命名为`.env`  

然后保存的小册子章节文件名前面加上了序号