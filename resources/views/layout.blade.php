<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Generator</title>
    <link rel="icon" type="image/png" href="/vendor/laravel-generator/images/logo.png">
    <!-- Styles -->
    <link href="/vendor/laravel-generator/css/element.css" rel="stylesheet" type="text/css">
    <style>
        [v-cloak] {
            display: none;
        }
        .content {
            margin:  0px auto;
        }
        .header {
            text-align: center;
        }
        .footer{
            text-align: center;
        }
        .grid-content {
            border-radius: 4px;
            min-height: 36px;
        }
        .row-bg {
            padding: 10px 0;
            background-color: #f9fafc;
        }
        .header {
            display: flex;
            flex-direction: row;       /* 图片和文字一行 */
            align-items: center;       /* 让文字垂直居中对齐图片 */
            justify-content: center;   /* 可选：让整组内容居中对齐 */
            height: 100px;              /* 可根据需求设置 header 高度 */
            gap: 10px;                 /* 图片和文字之间的间距 */
        }
        .title {
            margin: 0;                 /* 去掉 p 标签默认的 margin */
            font-size: 30px;
            color:#FF2D20;
        }
        #app input{
        }
        .el-form--label-top .el-form-item__label{
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="app" class="content" v-cloak>
    <div >
        <el-container>
            <el-header class="header" style="display: flex; align-items: center; position: relative;">
                <div style="position: absolute; left: 50%; transform: translateX(-50%); display: flex; align-items: center;">
                    <img src="/vendor/laravel-generator/images/logo.png" style="width: 70px">
                    <p class="title" style="margin-left: 10px;">Laravel Generator</p>
                </div>
                <div  id="notice" style="margin-left: auto; display: flex; align-items: center; gap: 10px;">
                    <a href="https://github.com/foryoufeng/laravel-generator" target="_blank" title="laravel-generator">
                        <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png"
                             alt="GitHub" style="width: 30px; height: 30px;">
                    </a>
                    <a href="https://generator.pp-lang.tech" target="_blank" title="document"
                       style="text-decoration: none; color: inherit;"><i class="el-icon-question" style="font-size: 24px;"></i>
                    </a>
                        <el-col >
                            <el-dropdown style="font-size: 16px;cursor: pointer" @command="handleCommand">
                              <span class="el-dropdown-link">
                                @{{ language_value }}<i class="el-icon-arrow-down el-icon--right"></i>
                              </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item  v-for="item in language_options" :command="item.value">@{{ item.label }}</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </el-col>
                </div>
            </el-header>
            <el-main>
                @yield('content')
            </el-main>
            <el-footer class="footer"> ©Laravel Generator</el-footer>
        </el-container>
    </div>
</div>
<!-- Scripts -->
<script src="/vendor/laravel-generator/js/vue.js"></script>
<script src="/vendor/laravel-generator/js/axios.js"></script>
<script src="/vendor/laravel-generator/js/element-2.4.js"></script>
<script>
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    String.prototype.trim = function (char, type) {
        if (char) {
            if (type == 'left') {
                return this.replace(new RegExp('^\\'+char+'+', 'g'), '');
            } else if (type == 'right') {
                return this.replace(new RegExp('\\'+char+'+$', 'g'), '');
            }
            return this.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '');
        }
        return this.replace(/^\s+|\s+$/g, '');
    };
    /**
     * get请求
     * @param url
     * @param params
     * @returns {Promise<any>}
     */
    Vue.prototype.doGet=function(url,params={}){
        return new Promise((resolve,reject) => {
            axios.get(url,{
                params: params
            }).then((res)=>{
                if(res.status==200){
                    resolve(res.data);
                }
                Vue.$message.error('wrong');
            }).catch(function (error) {
                reject(error);
            });
        });
    }
    /**
     * post请求
     * @param url
     * @param params
     * @returns {Promise<any>}
     */
    Vue.prototype.doPost=function(url,params={}){
        return new Promise((resolve,reject) => {
            axios.post(url,params).then((res)=>{
                if(res.status==200){
                    resolve(res.data);
                }
                Vue.$message.error('wrong');
            }).catch(function (error) {
                reject(error);
            });
        });
    }
</script>
@yield('js')
@yield('css')
</body>
</html>
