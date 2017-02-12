<html>
    <head>
        <title>2 + 2 = 5</title>
        <link href="blog.css" rel="stylesheet">
    </head>
    <body>
        <div id="page">
            <h1 align="center">Your blogtitle</h1>
            <table align="center" width="100%">
                <tr>
                    <td id="menuitem"><a href="blog.php">Home</a></td>
                    <td id="menuitem"><a href="blog.php?page=ueber">&Uuml;ber</a></td>
                    <td id="menuitem"><a href="blog.php?page=impressum">Impressum</a></td>
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

                $filedir = 'your-ressource-folder';
                $max_posts_per_page = 5;
                $posts = array();
                $posts = readFiles($filedir);
                $max_pages = ceil(count($posts) / $max_posts_per_page) - 1;
                $html = '';

                //set current page to 0 per default
                $page = 0;

                //visitor has direct link to blogpost
                if (isset($_GET['articleid'])) {
                    $blogpostid = $_GET['articleid'];

                    if (($blogpostid >= 0) && ($blogpostid < count($posts))) {
                        echo printBlogPost($posts[$blogpostid]);
                        echo '<a href="blog.php?page='.getPageOf($blogpostid, $max_posts_per_page, $max_pages).'"><-zur&uuml;ck</a>';
                    }
                }

                //visitor has page id or nothing
                else {

                    rsort($posts);

                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }

                    if (preg_match('/[0-9]+/', $page)) {
                        $page = (int) $page;

                        //shows blogposts from array
                        for ($i = 0; $i < $max_posts_per_page; $i++) {
                            if (array_key_exists($page * $max_posts_per_page + $i, $posts)) {
                                echo printBlogPost($posts[$page * $max_posts_per_page + $i]);
                            }
                        }

                        //navigation arrows at the bottom, dependent from current page
                        $html = $html.'<table align="center"><tr>';

                        if ($page > 0) {
                            $html = $html.'<td><a href="blog.php?page='.--$page.'"> << </a></td>';
                        }

                        if ($page < $max_pages) {
                            $html = $html.'<td><a href="blog.php?page='.$page++.'"> >> </a></td>';
                        }

                        $html = $html.'</tr></table>';
                        echo $html;
                    }

                    else {

                        if (($page == 'ueber') || ($page == 'impressum')) {
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
