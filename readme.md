# 掘金小册抓去到本地

## 使用方法

安装好`chromedriver`后,启动一个浏览器
```shell
/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --remote-debugging-port=9222 --kiosk-printing --user-data-dir="/Users/daviddong/Downloads/package/seleium-kios"
```
然后再打开的浏览器中登录你的掘金账号
然后运行脚本开始爬去小册内容
```shell
python main.py
```


## 安装Selenium  

```shell
pip install selenium
```
安装浏览器driver
到(驱动网站)[http://chromedriver.storage.googleapis.com/index.html]上下载对应驱动，前三位版本号要一致,最好是前四位都一致
我用的是Mac Intel芯片,当前浏览器版本 版本 107.0.5304.110（正式版本） (x86_64)
所以下载(这个链接)[http://chromedriver.storage.googleapis.com/107.0.5304.62/chromedriver_mac64.zip]
解压后得到`chromedriver`文件
执行命令
```shell
mv chromedriver /usr/local/bin
xattr -d com.apple.quarantine chromedriver
```


## 如何让Selenium接管已经打开的浏览器  

有的网站因为账号登录等原因,需要先人为登录后,再让Selenium接管已经登录的网页,进行后续自动化操作
这里需要先把Google Chrome加入到环境变量, 然后终端执行命令打开浏览器进行登录操作,或者直接用完整路径
```
# windows
C:Program Files (x86)GoogleChromeApplication
# mac
/Applications/Google Chrome.app/Contents/MacOS
```
```shell
# windows
chrome.exe --remote-debugging-port=9222 --user-data-dir="D:packageselenium"
# mac
/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --remote-debugging-port=9222 --kiosk-printing --user-data-dir="/Users/daviddong/Downloads/package/seleium-kios"

#--kiosk-printing 用于跳过打印预览
```
然后在代码中用端口号关联
```python
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
chrome_options = Options()
chrome_options.add_experimental_option("debuggerAddress", "127.0.0.1:9222")
driver = webdriver.Chrome(options=chrome_options)
print(driver.title)
```


## 参数说明  

```python
from selenium.webdriver.chrome.options import Options
options = Options()
# 程序启动时接管已存在的浏览器窗口 和下面的不可共存
options.add_experimental_option("debuggerAddress", "127.0.0.1:9222")
# 程序结束后不自动关闭浏览器窗口 和上面的不可共存
options.add_experimental_option('detach',True)

# 其他配置
options.add_argument('--disable-infobars') # 禁止策略化

options.add_argument('--no-sandbox') # 解决DevToolsActivePort文件不存在的报错

options.add_argument('window-size=1920x3000') # 指定浏览器分辨率

options.add_argument('--disable-gpu') # 谷歌文档提到需要加上这个属性来规避bug

options.add_argument('--incognito') # 隐身模式（无痕模式）

options.add_argument('--disable-javascript') # 禁用javascript

options.add_argument('--start-maximized') # 最大化运行（全屏窗口）,不设置，取元素会报错

options.add_argument('--disable-infobars') # 禁用浏览器正在被自动化程序控制的提示

options.add_argument('--hide-scrollbars') # 隐藏滚动条, 应对一些特殊页面

options.add_argument('blink-settings=imagesEnabled=false') # 不加载图片, 提升速度

options.add_argument('--headless') # 浏览器不提供可视化页面. linux下如果系统不支持可视化不加这条会启动失败

options.binary_location = r"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" # 手动指定使用的浏览器位置
```
