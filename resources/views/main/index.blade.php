<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>hsfilter</title>
    <link rel="stylesheet" href="css/index.css">

</head>
<body>


    <div id="filterbar">
        <filter-bar></filter-bar>
    </div>

    <ul id="resultlist">
        <li v-for="item in items">
            @{{ item.text }}
        </li>
    </ul>
    <script src="js/app.js"></script>    
    <!-- <script src="js/axhr.js"></script> -->
</body>
</html>