# This is a sample Python script.
import os
from time import sleep

from selenium import webdriver
import requests


class Juejinxiaoce:
    driver = None
    cookies = None

    def __init__(self):
        self.driver = self.catch_brower()
        self.cookies = self.get_cookies()

    def catch_brower(self):
        options = webdriver.ChromeOptions()
        caps = {
            'browserName': 'chrome',
            'version': '',
            'platform': 'ANY',
            'goog:loggingPrefs': {'performance': 'ALL'},  # 记录性能日志
            'goog:chromeOptions': {'extensions': [], 'args': ['--headless']}  # 无界面模式
        }
        # 避免执行完毕自动关闭浏览器
        # options.add_experimental_option('detach',True)
        options.add_experimental_option("debuggerAddress", "127.0.0.1:9222")
        options.add_argument('--start-maximized')
        # options.add_argument('--disable-gpu')  # 谷歌文档提到需要加上这个属性来规避bug
        driver = webdriver.Chrome(options=options, desired_capabilities=caps)
        driver.get('https://juejin.cn/my-course')
        driver.implicitly_wait(10)
        print(driver)
        return driver

    def get_cookies(self):
        """
        获取浏览器cookie
        :return: cookie 登录掘金后的cookie
        """
        cookies = self.driver.get_cookies()
        ck = {}
        for cookie in cookies:
            ck[cookie["name"]] = cookie["value"]
        return ck

    def get_books(self):
        """
        获取已购买小册列表
        :return: books 小册列表
        """
        url = "https://api.juejin.cn/booklet_api/v1/booklet/bookletshelflist?aid=2608&uuid=7204114281988998691&spider=0"
        r = requests.post(url, data={}, cookies=self.cookies)
        books = []
        res = r.json()
        data = res["data"]
        for item in data:
            books.append({
                "booklet_id": item["booklet_id"],
                "title": item["base_info"]["title"],
                "update_section_count": item["section_updated_count"]
            })

        return books

    def get_sections(self, booklet_id):
        """
        获取小册的章节列表
        :param booklet_id: 小册id
        :return: sections 章节列表
        """
        url = 'https://api.juejin.cn/booklet_api/v1/booklet/get?aid=2608&uuid=7204114281988998691&spider=0'
        payload = {
            "booklet_id": booklet_id
        }
        r = requests.post(url, data=payload, cookies=self.cookies)
        data = r.json()
        sections = []
        i = 0
        for section in data["data"]["sections"]:
            i += 1
            sections.append({"section_id": section["section_id"], "title": str(i) + "." + section["title"]})
        return sections

    def get_section_data(self, section_id):
        """
        获取章节内容
        :param section_id: 章节id
        :return: sesion
        """
        print("start post:" + str(section_id))
        url = 'https://api.juejin.cn/booklet_api/v1/section/get?aid=2608&uuid=7204114281988998691&spider=0'
        payload = {
            "section_id": section_id
        }
        r = requests.post(url, data=payload, cookies=self.cookies)
        data = r.json()
        return data["data"]["section"]

    def write_file(self, path, content):
        """
        写入到文件
        :param path: 文件保存路径
        :param content: 文件保存内容
        """
        with open(path, 'w') as file:
            file.write(content)

    def save_section(self, section, base_path):
        """
        保存章节内容
        :param section: 章节内容
        :param base_path: 保存根目录
        """
        html_path = base_path + "html/"
        markdown_path = base_path + "markdown/"
        pdf_path = base_path + "pdf/"
        res = self.get_section_data(section["section_id"])
        html = self.htmlbc(res["content"])
        markdown = res["markdown_show"]
        section["title"] = section["title"].replace("/", "_")
        self.write_file(html_path + section["title"] + ".html", html)
        self.write_file(markdown_path + section["title"] + ".md", markdown)
        sleep(1)

    def htmlbc(self, content):
        """
        为html内容增加css类以及class
        :param content:
        :return:
        """
        res = """<link href="https://cdn.bootcss.com/github-markdown-css/2.10.0/github-markdown.min.css" rel="stylesheet" />\n<div class="markdown-body" style="width:699px;">\n""" + content + "\n</div>"
        return res

    def down_book(self, book):
        """
        下载小册的内容
        :param book: 小册
        """
        bash_path = "./down/" + book["title"] + "/"
        html_path = bash_path + "html/"
        markdown_path = bash_path + "markdown/"
        pdf_path = bash_path + "pdf/"
        os.makedirs(html_path, 0o777, True)
        os.makedirs(markdown_path, 0o777, True)
        os.makedirs(pdf_path, 0o777, True)
        sections = self.get_sections(book["booklet_id"])
        for section in sections:
            try:
                self.save_section(section, bash_path)
            except:
                print("====================")
                print("保存失败了")
                print(section)
                print("====================")

    def start(self):
        """
        开始下载所有已购买小册的内容
        """
        books = self.get_books()
        print(books)
        for book in books:
            self.down_book(book)


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    print("准备初始化")
    cla = Juejinxiaoce()
    print("捕捉浏览器成功", cla.cookies)
    cla.start()
    print("结束")
# See PyCharm help at https://www.jetbrains.com/help/pycharm/
