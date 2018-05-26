<html>
    <head>
        <title>2 + 2 = 5</title>
        <style>
            a {
                color: #ffffff;
                text-decoration: none;
            }

            #page {
                font-family: monospace;
                position: absolute;
                width: 600;
                left: 50%;
                margin-left: -300;
                background-color: #999999;
            }

            #blogpost {
                margin-top: 1.2em;
                margin-bottom: 0.5em;
                margin-left: 0.5em;
                margin-right: 0.5em;
            }

            #menuitem {
                text-align: center;
                width: 25%;
            }

            h3 {
                font-size: 18;
                margin-bottom: 0.3em;
            }
        </style>
    </head>
    <body>
        <div id="page">
            <h1 align="center">your blog title here</h1>
            <table align="center" width="100%">
                <tr>
                    <td id="menuitem"><a href="blog.php">Home</a></td>
                    <td id="menuitem"><a href="blog.php?page=ueber">&Uuml;ber</a></td>
                    <td id="menuitem"><a href="blog.php?page=impressum">Impressum</a></td>
                    <td id="menuitem"><a href="blog.php?page=datenschutz">Datenschutz</a></td>
                </tr>
            </table>

            <!-- Content ab hier-->
            <?php

              include('blog-functions.inc.php');

                /*
                    DEFINITIONS:
                        ->  filedir: ressource directory on the server
                        ->  max_posts_per_page: nothing more to say...
                        ->  posts: array with content + metadata
                        ->  max_pages: number of pages which can be displayed
                        ->  html: fancy arrows at the bottom of the main page
                */


                $filedir = '/your/json/src/files/here';
                $max_posts_per_page = 5;
                $posts = readJSONFiles($filedir);

                $max_pages = ceil(count($posts) / $max_posts_per_page) - 1;
                $html = '';

                //set current page to 0 per default
                $page = 0;

                //visitor has direct link to blogpost
                if (isset($_GET['articleid'])) {
                    $blogpostid = $_GET['articleid'];

                    if (($blogpostid >= 0) && ($blogpostid < count($posts))) {
                        echo printJSONBlogPost($posts[$blogpostid]);
                        echo '<a href="blog.php"><-zur&uuml;ck</a>';
                    }
                }

                //visitor has page id or nothing
                else {

                    usort($posts, "cmpdate");

                    /* TODO String/ Int Problematik */
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }

                    if (preg_match('/[0-9]+/', $page)) {
                        $page = (int) $page;

                        //shows blogposts from array
                        for ($i = 0; $i < $max_posts_per_page; $i++) {
                            if (array_key_exists($page * $max_posts_per_page + $i, $posts)) {
                                echo printJSONBlogPost($posts[$page * $max_posts_per_page + $i]);
                            }
                        }

                        //navigation arrows at the bottom, dependent from current page
                        $html = $html.'<table align="center"><tr>';

                        if ($page > 0) {
                            $html = $html.'<td><a href="blog.php?page='.--$page.'"> << </a></td>';
                        }

                        if ($page < $max_pages) {
                            $html = $html.'<td><a href="blog.php?page='.++$page.'"> >> </a></td>';
                        }

                        $html = $html.'</tr></table>';
                        echo $html;
                    }

                    else {

                        if (($page == 'ueber') || ($page == 'impressum') || ($page == 'datenschutz')) {
                            include($page.'.html');
                        }

                        else {
                            echo 'ungueltige Anfrage';
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>
