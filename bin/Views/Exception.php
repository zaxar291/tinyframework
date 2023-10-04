<?php
/**
 * @var Throwable $model
 */
?>
<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Exception thrown</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;
            font-size: .813em;
            color: #222;
            background-color: #fff;
        }

        h1 {
            color: #44525e;
            margin: 15px 0 15px 0;
        }

        h2 {
            margin: 10px 5px 0 0;
        }

        h3 {
            color: #363636;
            margin: 5px 5px 0 0;
            font-weight: normal;
        }

        code {
            font-family: Consolas, "Courier New", courier, monospace;
            font-weight: bold;
        }

        body .titleerror {
            padding: 3px 3px 6px 3px;
            display: block;
            font-size: 1.5em;
            font-weight: 100;
        }

        body .location {
            margin: 3px 0 10px 30px;
        }

        #header {
            font-size: 18px;
            padding: 15px 0;
            border-top: 1px #ddd solid;
            border-bottom: 1px #ddd solid;
            margin-bottom: 0;
        }

        #header li {
            display: inline;
            margin: 5px;
            padding: 5px;
            color: #a0a0a0;
            cursor: pointer;
        }

        #header .selected {
            background: #44c5f2;
            color: #fff;
        }

        #stackpage ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            /*border-bottom: 1px #ddd solid;*/
        }

        #stackpage .details {
            font-size: 1.2em;
            padding: 3px;
            color: #000;
        }

        #stackpage .stackerror {
            padding: 5px;
            border-bottom: 1px #ddd solid;
        }


        #stackpage .frame {
            padding: 0;
            margin: 0 0 0 30px;
        }

        #stackpage .frame h3 {
            padding: 2px;
            margin: 0;
        }

        #stackpage .source {
            padding: 0 0 0 30px;
        }

        #stackpage .source ol li {
            font-family: Consolas, "Courier New", courier, monospace;
            white-space: pre;
            background-color: #fbfbfb;
        }

        #stackpage .frame .source .highlight {
            border-left: 3px solid red;
            margin-left: -3px;
            font-weight: bold;
        }

        #stackpage .frame .source .highlight li span {
            color: #FF0000;
        }

        #stackpage .source ol.collapsible li {
            color: #888;
        }

        #stackpage .source ol.collapsible li span {
            color: #606060;
        }

        #routingpage .subheader {
            padding: 5px;
            border-bottom: 1px #ddd solid;
        }

        .page table {
            border-collapse: separate;
            border-spacing: 0;
            margin: 0 0 20px;
        }

        .page th {
            vertical-align: bottom;
            padding: 10px 5px 5px 5px;
            font-weight: 400;
            color: #a0a0a0;
            text-align: left;
        }

        .page td {
            padding: 3px 10px;
        }

        .page th, .page td {
            border-right: 1px #ddd solid;
            border-bottom: 1px #ddd solid;
            border-left: 1px transparent solid;
            border-top: 1px transparent solid;
            box-sizing: border-box;
        }

        .page th:last-child, .page td:last-child {
            border-right: 1px transparent solid;
        }

        .page .length {
            text-align: right;
        }

        a {
            color: #1ba1e2;
            text-decoration: none;
        }

        a:hover {
            color: #13709e;
            text-decoration: underline;
        }

        .showRawException {
            cursor: pointer;
            color: #44c5f2;
            background-color: transparent;
            font-size: 1.2em;
            text-align: left;
            text-decoration: none;
            display: inline-block;
            border: 0;
            padding: 0;
        }

        .rawExceptionStackTrace {
            font-size: 1.2em;
        }

        .rawExceptionBlock {
            border-top: 1px #ddd solid;
            border-bottom: 1px #ddd solid;
        }

        .showRawExceptionContainer {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .expandCollapseButton {
            cursor: pointer;
            float: left;
            height: 16px;
            width: 16px;
            font-size: 10px;
            position: absolute;
            left: 10px;
            background-color: #eee;
            padding: 0;
            border: 0;
            margin: 0;
        }

    </style>
</head>
<body>
<h1>An unhandled exception occurred while processing the request.</h1>
<div class="titleerror"><?php echo $model; ?></div>
<p class="location"><?php echo $model->getFile(); ?>, line <?php echo $model->getLine(); ?></p>
<ul id="header">
    <li id="stack" tabindex="1" class="selected">
        Stack
    </li>
</ul>

<div id="stackpage" class="page">
    <ul>
        <li>
            <h2 class="stackerror"><?php echo $model->getMessage(); ?></h2>
            <ul>
                <li class="frame" id="frame1">
                    <h3><code title="D:\DiscordTimesData\WebApplication6\WebApplication6\Controllers\HomeController.cs"><?php echo $model->getFile(); ?></code></h3>
                </li>
                <?php foreach ($model->getTrace() as $key => $trace) : ?>
                    <li class="frame" id="frame<?php echo $key; ?>">
                        <?php echo $trace["class"] . "->" . $trace["function"]; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li>
            <br />
            <div class="rawExceptionBlock">
                <div class="showRawExceptionContainer">
                    <button class="showRawException" data-exceptionDetailId="exceptionDetail1">Show raw exception details</button>
                </div>
                <div id="exceptionDetail1" class="rawExceptionDetails">
                    <pre class="rawExceptionStackTrace"><?php echo $model->getTraceAsString(); ?></pre>
                </div>
            </div>
        </li>
    </ul>
</div>

<script>
    //<!--
    (function (window, undefined) {
        "use strict";

        function ns(selector, element) {
            return new NodeCollection(selector, element);
        }

        function NodeCollection(selector, element) {
            this.items = [];
            element = element || window.document;

            var nodeList;

            if (typeof (selector) === "string") {
                nodeList = element.querySelectorAll(selector);
                for (var i = 0, l = nodeList.length; i < l; i++) {
                    this.items.push(nodeList.item(i));
                }
            }
        }

        NodeCollection.prototype = {
            each: function (callback) {
                for (var i = 0, l = this.items.length; i < l; i++) {
                    callback(this.items[i], i);
                }
                return this;
            },

            children: function (selector) {
                var children = [];

                this.each(function (el) {
                    children = children.concat(ns(selector, el).items);
                });

                return ns(children);
            },

            hide: function () {
                this.each(function (el) {
                    el.style.display = "none";
                });

                return this;
            },

            toggle: function () {
                this.each(function (el) {
                    el.style.display = el.style.display === "none" ? "" : "none";
                });

                return this;
            },

            show: function () {
                this.each(function (el) {
                    el.style.display = "";
                });

                return this;
            },

            addClass: function (className) {
                this.each(function (el) {
                    var existingClassName = el.className,
                        classNames;
                    if (!existingClassName) {
                        el.className = className;
                    } else {
                        classNames = existingClassName.split(" ");
                        if (classNames.indexOf(className) < 0) {
                            el.className = existingClassName + " " + className;
                        }
                    }
                });

                return this;
            },

            removeClass: function (className) {
                this.each(function (el) {
                    var existingClassName = el.className,
                        classNames, index;
                    if (existingClassName === className) {
                        el.className = "";
                    } else if (existingClassName) {
                        classNames = existingClassName.split(" ");
                        index = classNames.indexOf(className);
                        if (index > 0) {
                            classNames.splice(index, 1);
                            el.className = classNames.join(" ");
                        }
                    }
                });

                return this;
            },

            attr: function (name) {
                if (this.items.length === 0) {
                    return null;
                }

                return this.items[0].getAttribute(name);
            },

            on: function (eventName, handler) {
                this.each(function (el, idx) {
                    var callback = function (e) {
                        e = e || window.event;
                        if (!e.which && e.keyCode) {
                            e.which = e.keyCode; // Normalize IE8 key events
                        }
                        handler.apply(el, [e]);
                    };

                    if (el.addEventListener) { // DOM Events
                        el.addEventListener(eventName, callback, false);
                    } else if (el.attachEvent) { // IE8 events
                        el.attachEvent("on" + eventName, callback);
                    } else {
                        el["on" + type] = callback;
                    }
                });

                return this;
            },

            click: function (handler) {
                return this.on("click", handler);
            },

            keypress: function (handler) {
                return this.on("keypress", handler);
            }
        };

        function frame(el) {
            ns(".source .collapsible", el).toggle();
        }

        function expandCollapseButton(el) {
            var frameId = el.getAttribute("data-frameId");
            frame(document.getElementById(frameId));
            if (el.innerText === "+") {
                el.innerText = "-";
            }
            else {
                el.innerText = "+";
            }
        }

        function tab(el) {
            var unselected = ns("#header .selected").removeClass("selected").attr("id");
            var selected = ns("#" + el.id).addClass("selected").attr("id");

            ns("#" + unselected + "page").hide();
            ns("#" + selected + "page").show();
        }

        ns(".rawExceptionDetails").hide();
        ns(".collapsible").hide();
        ns(".page").hide();
        ns("#stackpage").show();

        ns(".expandCollapseButton")
            .click(function () {
                expandCollapseButton(this);
            })
            .keypress(function (e) {
                if (e.which === 13) {
                    expandCollapseButton(this);
                }
            });

        ns("#header li")
            .click(function () {
                tab(this);
            })
            .keypress(function (e) {
                if (e.which === 13) {
                    tab(this);
                }
            });

        ns(".showRawException")
            .click(function () {
                var exceptionDetailId = this.getAttribute("data-exceptionDetailId");
                ns("#" + exceptionDetailId).toggle();
            });
    })(window);
    //-->
</script>
</body>
</html>
