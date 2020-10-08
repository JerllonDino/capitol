<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>Capitol | Database Restoration</title>
    <style>
        body {
            font-family: Helvetica, Arial, Sans-Serif;
            margin: 0em;
        }

        #content {
            margin-left: auto;
            margin-right: auto;
            padding-top: 2em;
            position: relative;
            width: 90%;
            z-index: 3;
        }

        #cycler .active {
            z-index: 1;
        }

        #cycler .fade {
            opacity: 0;
            transition: opacity 2s;
        }

        #cycler .next {
            z-index: 0;
        }

        #cycler img {
            min-height: 100%;
            min-width: 64em;
            position: fixed;
            width: 100%;
            z-index: -9999;
        }

        #search-group {
            background-color: #ffffff;
            border: 1px solid #d0d0d0;
            border-radius: 2px;
            display: flex;
            opacity: 0.8;
            width: 100%;
            margin-top: 25%;
            
        }
        
        #search-group p {
            width: 100%;
        }

        .search-group-right {
            float: right;
        }

        #search-group * {
            background-color: #ffffff;
            border: 0px;
            font-size: 20px;
            height: 1.8em;
            text-align: center;
        }

        #search-input {
            width: 100%;
        }

        @media screen and (max-width: 1024px) {
            #cycler img {
                top: -125%;
                right: -125%;
                bottom: -125%;
                left: -125%;
                margin: auto;
            }
        }
    </style>
</head>
<body>
    <div id="cycler"></div>
    <div id="content">
        <form action="https://duckduckgo.com/" method="get">
            <div id="search-group">
                <p>
                Restoration of Database is ongoing.
                Please refresh the page later.
                </p>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        /* LIST YOUR IMAGES HERE */
        var backgrounds = [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            ];
        
        function cycle_images() {
            backgrounds[1].classList.add('next');
            backgrounds[0].classList.add('fade');
            
            setTimeout(function() {
                backgrounds[1].classList.remove('next');
                backgrounds[0].classList.remove('fade', 'active');
                backgrounds.push(backgrounds.shift());
                backgrounds[0].classList.add('active');
            }, 2000);
            /* 2 seconds before execution, same as transition time in CSS */
        }
        
        (function() {
            while (backgrounds.length > 0) {
                var index = Math.floor(Math.random() * backgrounds.length);
                var background = document.getElementsByClassName('background');
                var img = document.createElement('img');
                
                img.src = '{{ url('/') }}/asset/backgrounds/' + backgrounds[index];
                img.className = (background.length == 0) ? 'background active' : 'background';
                document.getElementById('cycler').appendChild(img);
                backgrounds.splice(index, 1);
            }
            
            if (background.length > 1) {
                backgrounds = Array.from(background);
                setInterval('cycle_images()', 7000);
                /* 7 seconds display period */
            }
        })();
    </script>
</body>
</html>