<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <title><?php echo $site['title']; ?> - <?php echo $site['subtitle']; ?></title>
    <meta content="<?php echo $site['keywords']; ?>" name="keywords">
    <meta content="<?php echo $site['description']; ?>" name="description">
    <meta content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
    <script src="/templates/tushan/vue.js"></script>
    <script src="/templates/tushan/element.js"></script>
<script defer="" src="/templates/tushan/index1651300933210.js"></script><link href="/templates/tushan/index1651300933210.css" rel="stylesheet"></head>
<body>
<div v-cloak="" id="root" @contextmenu="mouseMenu">
    <div class="main-page" ctype="[6]">
        <div :class="{moveUp:drawer}" class="search">
            <div class="inp">
                <div class="search-type" @click="souStatus = !souStatus">
                    <img :src="default_sou.icon">
                </div>
                <input v-model="search" placeholder="搜一搜？" @focus="searchPreviewRender" @focusout="closePreview" @keyup.enter="search_go">
            </div>
            <div :class="{souShow:souStatus}" class="sou">
                <div class="sou-main">
                    <img v-for="(item,index) in sou" :key="index" :alt="item.name" :src="item.icon" @click="setsou(item)">
                </div>
            </div>
            <transition name="slide-fade">
                <div v-if="searchPreview" class="search-preview">
                    <ul>
                        <li v-for="(li,index) in searchList" :key="index" @click="to(li)">
                            <span v-html="li.title"></span>
                            <span>{{ li.description }}</span>
                        </li>
                    </ul>
                </div>
            </transition>
            <transition name="showings">
                <div v-if="!drawer&&!searchPreview&&history.length!=0" class="history">
                    <transition v-for="(item,index) in history" :key="index">
                        <div :cdata="item.url" :title="item.description" class="item" ctype="[5]" @click="to(item)">
                            <div class="img"><img :src="getIcon(item)"></div>
                            <div class="span">{{ item.title }}</div>
                        </div>
                    </transition>
                </div>
            </transition>
        </div>
    </div>
    <div :class="{show:drawer}" class="drawer">
        <div class="drawer-main">
            <div v-for="(ite,index) in list" :key="index" class="x-list">
                <div :cdata="ite.id" class="title" ctype="[1,2]">
                    <i :class="ite?.font_icon"></i>
                    <span>{{ ite.name }}</span>
                </div>
                <div class="y-list">
                    <div v-for="(item,i) in ite.children" :key="i" :cdata="item.id" :title="item.description" class="dreaer-list" ctype="[3,4]" @click="to(item) ">
                        <div class="img">
                            <img :alt="item.title" :src="getIcon(item)">
                        </div>
                        <div class="name">{{ item.title }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="tabbar.length!=0" :class="{shows:drawer}" class="bottomtab">
        <div :style="{width:tabbar.length*65+'px'}" class="al">
            <div v-for="(item,index) in tabbar" :key="index">
                <img :src="item.icon">
                <!--                <span>{{ item.title.slice(0, 1) }}</span>-->
            </div>
        </div>
    </div>
    <tabar :xy="mouseRight"></tabar>
    <add_link></add_link>
    <add_menus></add_menus>
    <footer v-show="!drawer">
        <span>© 2022 Powered by <a href="https://github.com/helloxz/onenav">OneNav</a> ---> <span style="cursor: pointer" @click="login">login in</span></span>
    </footer>
</div>




</body></html>