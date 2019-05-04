<?php require 'header.php'; ?>

<el-collapse-transition>
    <div style="
              width: 75%;
              margin: 10vh auto;
          " v-show="loaded">
        <el-row :gutter="20">


            <el-col :span="6">
                <div class="side-blog-author">
                    <el-card shadow="hover">
                        <div class="side-author-banner"></div>
                        <div class="side-author-avatar"><img src="https://static.ouorz.com/tonyhe.jpg">
                            <div class="side-author-info">
                                <h2>TonyHe</h2>
                                <p>Just A Poor Lifesinger</p>
                                <em>已发布 {{ site_info.posts_count }} 篇内容</em>
                            </div>
                        </div>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-w">
                            <i class="czs-weixin"></i>
                            Helipeng_tony
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-e">
                            <i class="czs-message-l"></i>
                            he@holptech.com
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-q">
                            <i class="czs-qq"></i>
                            36624065
                        </p>
                    </el-card>
                </div>
            </el-col>



            <el-col :span="12">
                <div>
                    <el-card shadow="hover" v-for="(post,index) in posts" v-if="index <= display_count" class="stream-card">
                        <img :src="post.info.Img" v-if="!!post.info.Img">
                        <p class="stream-info">
                            <em>{{ post.info.Author ? post.info.Author : 'Whoever'}}</em>
                            <em>{{ post.info.Date ? post.info.Date : 'Whenever'}}</em>
                            <em>{{ post.info.Cate ? post.info.Cate : 'Wherever' }}</em>
                            <em style="color: rgb(136, 142, 148);background: rgb(231, 236, 240);" v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
                        </p>
                        <a :href="'posts.php?view=' + post.filename">
                            <h1 v-html="post.info.Title"></h1>
                            <div v-html="md.render(post.content.replace(/\n*/g,'') + '...')" class="stream-content"></div>
                        </a>
                        <a :href="'posts.php?view=' + post.filename" class="stream-view">浏览全文</a>
                    </el-card>
                    <el-card shadow="hover" class="stream-card" v-loading="loading" v-show="loading">
                    </el-card>
                </div>
            </el-col>


            <?php require 'sidebar.php'; ?>



        </el-row>

        <?php require 'footer.php'; ?>
    </div>
    <el-collapse-transition>
<script>
    var md = window.markdownit({
        html: true,
        xhtmlOut: false,
        breaks: true,
        linkify: true
    });

$(document).ready(function(){
    $('#view').css('opacity','1');

    new Vue({
        el: '#view',
        data() {
            return {
                loading: 1,
                loaded:0,
                posts: [],
                nav_items: [],
                site_info: {
                    'posts_count': 0,
                    'cates_count': 0,
                    'tags_count' : 0
                },
                display_count: 3,
                tags: [],
                cates: []
            }
        },
        mounted() {
            axios.get('get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('get_posts.php?pos=1')
                        .then(e => {
                            this.posts = e.data.posts;
                            this.site_info.posts_count = e.data.counts.posts_count;
                            axios.get('get_info.php?key=tags')
                                .then(e => {
                                    this.tags = e.data;
                                    this.site_info.tags_count = e.data.counts.key_count;
                                    axios.get('get_info.php?key=cate')
                                        .then(e => {
                                            this.cates = e.data;
                                            this.site_info.cates_count = e.data.counts.key_count;
                                            this.loaded = 1;
                                        })
                                })
                        })
                })
        },
        methods : {
            new_page : function(){ //加载下一页文章列表
                this.display_count += 6;
                if(this.display_count >= this.site_info.posts_count){
                    this.loading = 0;
                }
            },
        }
    })

});
</script>
</body>

</html>