<?php
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
?>
