Zf2ModuleInstall
==================

介绍
----
为composer提供ZendFramework2.x模块的安装服务。目前只提供将模块安装到项目的module
目录下，需要手动修改application.config.php文件注册模块。

composer.json中的name必需以“-”号分隔单词，最终安装的模块目录名为去掉“-"号后每个
单词首字母大写。例如：一个叫TimeLine的模块的composer.json中name为“xxx/time-line”
安装后的目录名为TimeLine。

使用
----
在模块的composer.json文件中定义type为zf2-module

    ...
    "type": "zf2-module"
    ...

将[Zf2ModuleInstall]()添加到依赖列表中

    "require" : {
        ...
        "elvis-bi/zf2-module-install": "0.1.0",
        ...
    }

TODO
----
1. 自动将模块添加到application.config.php的modules里去。
2. 提供一个安装勾子可以进行一些初始化操作，例如初始化数据库等。
