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
                width: 33%;
            }

            h3 {
                font-size: 18;
                margin-bottom: 0.3em;
            }
        </style>
    </head>
    <body>
        <div id="page">
            <h1 align="center">2 + 2 = 5 | Blog von fabi3550</h1>
            <table align="center" width="100%">
                <tr>
                    <td id="menuitem"><a href="blog.php">Home</a></td>
                    <td id="menuitem"><a href="blog.php?page=ueber">&Uuml;ber</a></td>
                    <td id="menuitem"><a href="blog.php?page=impressum">Impressum</a></td>
                </tr>
            </table>

            <!-- Content ab hier-->
            <?php
                /*
                    DEFINITIONS:
                        ->  filedir: ressource directory on the server
                        ->  max_posts_per_page: nothing more to say...
                */

                $max_posts_per_page = 5;

                /*
                    getSubStrOf(buzzword1, buzzword2, content)
                        ->  buzzword1 and buuzword2 are Tags within the content
                        ->  returns a string with text within the buzzwords
                */
                function getSubstrOf($buzzword1, $buzzword2, $content) {
                    $x = strlen($buzzword1);
                    return substr($content,
                            strpos($content, $buzzword1) + $x,
                            strpos($content, $buzzword2) - (strpos($content, $buzzword1) + $x)
                    );
                }

                /*
                    readFiles(filedir)
                        ->  returns an array of arrays with blogposts
                        ->  has just one parameter filedir, which is
                            the ressource directory on the server
                */
                function readFiles($filedir) {

                    $handle = opendir($filedir);
                    $blogposts = array();
                    $countfiles = 0;

                    while ($filename = readdir($handle)) {

                        if (strlen($filename) > 2) {
                            $filecontent = file_get_contents($filedir.'/'.$filename);

                            $blogpost = array (
                                'ownid'    => $countfiles,
                                'filename' => $filename,
                                'title'    => getSubstrOf('[Title]', '[/Title]', $filecontent),
                                'author'   => getSubstrOf('[Author]', '[/Author]', $filecontent),
                                'date'     => date("d.m.Y", strtotime(getSubstrOf('[Date]', '[/Date]', $filecontent))),
                                'content'  => getSubstrOf('[Content]', '[/Content]', $filecontent)
                            );

                            $blogposts[$countfiles] = $blogpost;
                            $countfiles++;
                        }
                    }

                    return $blogposts;
                }

                /*
                    printBlogPost(blogpost)
                        -> returns a formatted string representing a blog post
                */
                function printBlogPost($blogpost) {
                    $html = '<div id="blogpost">';
                    $html = $html.'<h3><a href="blog.php?articleid='.$blogpost['ownid'].'">'.$blogpost['title'].'</a></h3>';
                    $html = $html.'<i>Ver&ouml;ffentlicht von '.$blogpost['author'].' am '.$blogpost['date'].'</i>';
                    $html = $html.'<p>'.$blogpost['content'].'</p><br>';
                    $html = $html.'</div>';
                    return $html;
                }

                //fill array with file data
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
                        echo '<a href="blog.php"><-zur&uuml;ck</a>';
                    }
                }

                //visitor has page id or nothing
                else {

                    rsort($posts);

                    /* TODO String/ Int Problematik */
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
                            $html = $html.'<td><a href="blog.php?page='.++$page.'"> >> </a></td>';
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
