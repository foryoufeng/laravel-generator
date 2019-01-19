<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('generator.name') }}</title>
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
        .header{
            color:#3A88FD;
            padding: 20px;
            font-size: 30px;
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

            <el-header class="header">
                <i class="el-icon-rank"></i>{{ config('generator.name','Laravel-generator') }}
            </el-header>
            <el-main>
                @yield('content')
            </el-main>
            <el-footer class="footer"> ©{{ config('generator.name','Laravel-generator') }}</el-footer>
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
</script>
@yield('js')
@yield('css')
</body>
</html>
