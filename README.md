一个一级标题 H1
====================
二级标题H2
---------------------
现在是时候让所有优秀的男人来
他们的国家的援助。这只是一个
普通段落。
那只敏捷的棕色狐狸跳过了懒惰
狗的背上。
## H2在引用
### H3

>start blockquote 这是一个引用。
>
>这是第二段的引用。
>
>end blockquote

Markdown 使用星号和底线来标记需要强调的区段。
*、**、_ 和 __ 都必须 成对使用；
*em*
**strong **
__strong__

列表 无序列表项的开始是：符号 空格；

无序列表项的开始是：符号 空格；

有序列表项的开始是：数字 . 空格；

空格至少为一个，多个空格将被解析为一个；

如果仅需要在行前显示数字和 .：

### 无序列表 ol + - *
* *Candy
+ +Gum
- -Booze


### 有序 隔壁
1. Cate
2. Cate2
2. Cate2

### A连接
这是一个 [连接www.9juren.com](http://www.9jruen.com/ "这里是标题").

### A连接 参数
[Google][1] [QQ][2] or [9jreun.com][9juren].

[1]: http://google.com.hk/ "Google"
[2]: http://qq.com/ "QQ"
[9juren]: http://www.9juren.com/ "具人同行"

### 图片

![alt LOGO][src:logo]
[src:logo]: http://i.9juren.com/template/jrtx/images/logo.png "LOGO"



五、代码区块
------------

1.可以使用缩进来插入代码块：

	<html> // Tab开头
		<title>Markdown</title>
    </html>  // 四个空格开头

代码块前需要有至少一个空行，且每行代码前需要有至少一个 Tab 或四个空格；

2.也可以通过 \`\`，插入行内代码（` 是 Tab 键上边、数字 1 键左侧的那个按键）：

例如 `<title>Markdown</title>`

3.代码块中的文本（包括 Markdown 语法）都会以原格式显示，而特殊字符会被转换为 HTML 实体。

六、分隔线
=======

1.可以在一行中使用三个或更多的 \*、\- 或 \_ 产生分隔线：

***
------
___

2.多个 \* 之间可以有空格（空白符），但不能有其他字符：

*	* *
- - -


//这里显示一些代码，在正文显示中会自动识别语言，进行代码染色，这是一段C#代码
public class Blog
{
     public int Id { get; set; }
     public string Subject { get; set; }
}


/**
 * nth element in the fibonacci series.
 * @param n >= 0
 * @return the nth element, >= 0.
 */
function fib(n) {
    var a = 1, b = 1;
    var tmp;
    while (--n >= 0) {
        tmp = a;
        a += b;
       b = tmp;
    }
    return a;
}

document.write(fib(10));