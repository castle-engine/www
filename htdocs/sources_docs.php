<?php
  require_once 'vrmlengine_functions.php';

  common_header("Documentation generated by pasdoc", LANG_EN, NULL, NULL);
?>

<p style="font-size: large; font-weight: bold">This is documentation of my
<?php echo a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine'); ?>
 as generated by
<a href="http://pasdoc.sourceforge.net/">pasdoc</a>.

<p>Read documentation in various formats:
<ul>
  <li><a href="apidoc/html/index.html">Online HTML documentation</a>
  <li><?php echo current_www_a_href_size(
    'PDF documentation',
    'apidoc/latex/docs.pdf'); ?>
  <li><?php echo current_www_a_href_size(
    'LaTeX version of documentation (was used to make PDF output)',
    'apidoc/latex/docs.tex'); ?>
<!--
  <li><a href="apidoc/latex/docs.html">Online HTML documentation
    made by htlatex from LaTeX source</a>. Warning: this is one <em>huge</em>
    HTML file
    (< ? php echo readable_file_size('apidoc/latex/docs.html'); ? >) !
    It's also admittedly much worse than HTML docs produced directly by pasdoc.
    Just treat this as a mere demo of htlatex...
-->
</ul>

<!--
  /* This style is copied from Media Wiki style for table of contents
     (/wiki/stylesheets/commonPrint.css) */
-->
<p><div style="border:1px solid #aaaaaa; background-color:#f9f9f9; padding:5px">
Sort of disclaimer: please note that for now
<ul>
  <li>some parts of the docs are still in Polish
  <li>generated documentation for some units doesn't look good
    <!-- (sometimes
    I wrote one comment documenting couple of things at once,
    sometimes I wrote a list but in the past there was no markup
    in pasdoc to handle this etc.)
  <li>there are many comments in sources that were not converted
    by pasdoc because pasdoc couldn't associate them with any item
    -->
</ul>

I'm working on all these issues, by correcting my code
and by working on <a href="http://pasdoc.sourceforge.net/">pasdoc</a> itself.
Feel free to browse this documentation and
send any comments to <?php echo MAILING_LIST_LINK; ?>.
I'm interested in all comments,
both as the author of this code and as a developer of
<a href="http://pasdoc.sourceforge.net/">pasdoc</a>.
</div>

<?php
  if (!IS_GEN_LOCAL) {
    $counter = php_counter("sources_docs", TRUE);
  };

  common_footer();
?>