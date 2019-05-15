# juejinxiaoce  

用php写的掘金小册子的下载脚本


## 使用准备

下载之后，应当需要执行一下`composer update`

因为使用了`composer`，且自定义了命名空间，可参考`composer.json`文件


## 使用说明  

在`index.php`文件中，修改配置信息，并填入要下载的小册子ID。  

然后执行`php index.php`即可

目录读写权限自己注意，下载的文件会保存在与`index.php`文件同级的`output`目录下

默认同时生成html格式和markdown格式



## 版本记录

v1.0 第一次提交
v1.1 排版排了一下，加了一个env
	`.env`文件里面如果值包括了`=` 那么这个方法会抛出异常， 必须加上双引号.
