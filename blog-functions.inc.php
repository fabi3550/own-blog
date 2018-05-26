<?php
include('blogpost.php');

/*
    cmpdate(post1, post2)
        -> returns a sort integer
        -> delivers sorting critera for usort, is a dependency for usort
*/

function cmpdate($post1, $post2) {

  $r = NULL;

  $adate = call_user_func(array($post1, "getReleaseDate"));
  $bdate = call_user_func(array($post2, "getReleaseDate"));

  if ($adate == $bdate) {
       $r = 0;
  }

  $r = ($adate < $bdate) ? 1 : -1;
  return $r;
}


/*
    readJSONFiles(filedir)
        -> returns an array of BlogPosts with blogposts
        -> input parameter $filedir points to local
           ressource folder
*/

function readJSONFiles($filedir) {

  $handle = opendir($filedir);
  $blogposts = array();
  $countfiles = 0;

  while ($filename = readdir($handle)) {

    if (strlen($filename) > 2) {
      $filecontent = file_get_contents($filedir.'/'.$filename);
      $json_a = json_decode($filecontent);

      $blogpost = new BlogPost(
        $countfiles,
        $json_a->{'title'},
        $json_a->{'author'},
        $json_a->{'email'},
        $json_a->{'release-date'},
        $json_a->{'content'}
      );

      $blogposts[$countfiles] = $blogpost;
      $countfiles++;
    }
  }

  return $blogposts;
}

/*
    printJSONBlogPost(blogpost)
        -> returns a formatted string representing a blog post

*/

function printJSONBlogPost($blogpost) {

  $html = '<div id="blogpost">';
  $html = $html.'<h3><a href="blog.php?articleid='.$blogpost->getOwnId().'">'.$blogpost->getTitle().'</a></h3>';
  $html = $html.'<i>Ver&ouml;ffentlicht von '.$blogpost->getAuthor().' am '.$blogpost->getReleaseDate().'</i>';
  $html = $html.'<p>'.$blogpost->getContent().'</p><br>';
  $html = $html.'</div>';
  return $html;

}

?>
