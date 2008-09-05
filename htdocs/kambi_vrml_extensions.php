<?php
  require "vrmlengine_functions.php";
  common_header("Kambi VRML extensions", LANG_EN,
    "Description of non-standard VRML 1.0 and 2.0 features " .
    "handled by Kambi VRML game engine.");

  $node_format_fd_type_pad = 0;
  $node_format_fd_name_pad = 0;
  $node_format_fd_def_pad = 0;
  $node_format_fd_kind_pad = 0;

function node_begin($node_name)
/* inicjuje $node_format_* na wartosci domyslne zebys mogl po wywolaniu
   node_begin swobodnie zmieniac te wartosci (i to w takiej kolejnosci
   w jakiej chcesz, np. mozesz zmienic tylko $node_format_fd_name_pad
   a pozostale zostawic na domyslnych wartosciach) */
{
  global $node_format_fd_type_pad, $node_format_fd_name_pad,
    $node_format_fd_def_pad, $node_format_fd_kind_pad;

  $node_format_fd_type_pad = 10;
  $node_format_fd_name_pad = 10;
  $node_format_fd_def_pad = 10;
  $node_format_fd_kind_pad = 12;

  return "<pre><b>$node_name {</b>\n";
}
function node_end()
{
  return "<b>}</b>\n</pre>";
}

function node_dots($comment = '')
{
  if ($comment == '')
    return "  <b>...</b>\n"; else
    return "  <b>... $comment ...</b>\n";
}

function node_field($field_kind,
  $field_type, $field_name, $field_default, $field_comment = "")
{
  global $node_format_fd_type_pad, $node_format_fd_name_pad,
    $node_format_fd_def_pad, $node_format_fd_kind_pad;

  $r = sprintf("  %-" .int_to_str($node_format_fd_kind_pad). "s %-"
                      .int_to_str($node_format_fd_type_pad). "s  <b>%-"
		      .int_to_str($node_format_fd_name_pad). "s  %-"
		      .int_to_str($node_format_fd_def_pad). "s</b>",
		      $field_kind, $field_type, $field_name, $field_default);
  if ($field_comment != "") $r .= "  # $field_comment";
  $r .= "\n";
  return $r;
}

$toc = new TableOfContents(array(
  new TocItem('Introduction', 'introduction'),
  new TocItem('Extensions', 'extensions'),
  new TocItem('Extensions for all VRML versions', 'exts_vrmlall', 1),

  new TocItem('Bump mapping (<tt>normalMap</tt>, <tt>heightMap</tt>, <tt>heightMapScale</tt> fields of <tt>KambiAppearance</tt>)', 'ext_bump_mapping', 2),
  new TocItem('3D text (node <tt>Text3D</tt>)', 'ext_text3d', 2),
  new TocItem('Blending factors (node <tt>BlendMode</tt> and field <tt>KambiAppearance.blendMode</tt>)', 'ext_blending', 2),
  new TocItem('Movies for <tt>MovieTexture</tt> can be loaded from images sequence', 'ext_movie_from_image_sequence', 2),
  new TocItem('Automatic processing of inlined content (node <tt>KambiInline</tt>)', 'ext_kambi_inline', 2),
  new TocItem('Programmable shaders (X3D feature available also in VRML 97)', 'ext_shaders', 2),
  new TocItem('Other Avalon extensions: <tt>MatrixTransform</tt>, <tt>Logger</tt>, <tt>Teapot</tt>', 'ext_avalon', 2),
  new TocItem('Mixing VRML 1.0, 2.0, X3D nodes and features', 'ext_mix_vrml_1_2', 2),
  new TocItem('Volumetric fog (additional fields for <tt>Fog</tt> node)', 'ext_fog_volumetric', 2),
  new TocItem('Special objects immune to fog (<tt>fogImmune</tt> field for <tt>Material</tt> node)', 'ext_fog_immune', 2),
  new TocItem('Inline nodes allow to include 3D models in other handled formats (3DS, MD3, Wavefront OBJ, Collada) and any VRML/X3D version', 'ext_inline_for_all', 2),
  new TocItem('Specify triangulation (node <tt>KambiTriangulation</tt>)', 'ext_kambi_triangulation', 2),
  new TocItem('VRML files may be compressed by gzip', 'ext_gzip', 2),
  new TocItem('Node <tt>NavigationInfo</tt> handling details', 'ext_navigationinfo', 2),
  new TocItem('Fields <tt>direction</tt> and <tt>up</tt> and <tt>gravityUp</tt> for <tt>PerspectiveCamera</tt>, <tt>OrthographicCamera</tt> and <tt>Viewpoint</tt> nodes', 'ext_cameras_alt_orient', 2),
  new TocItem('Mirror material (field <tt>mirror</tt> for <tt>Material</tt> node)', 'ext_material_mirror', 2),
  new TocItem('Headlight properties (node <tt>KambiHeadLight</tt>)', 'ext_headlight', 2),
  new TocItem('Specify how lights cast shadows (fields <tt>kambiShadows</tt> and <tt>kambiShadowsMain</tt> for light nodes)', 'ext_shadows', 2),

  new TocItem('VRML 1.0 only extensions', 'exts_vrml1', 1),

  new TocItem('Field <tt>parts</tt> in <tt>Cone</tt> and <tt>Cylinder</tt> nodes may have value <tt>NONE</tt>', 'ext_cone_cyl_parts_none', 2),
  new TocItem('Fields <tt>attenuation</tt> and <tt>ambientIntensity</tt> for light nodes', 'ext_light_attenuation', 2),
  new TocItem('Parts of Inventor in VRML', 'ext_iv_in_vrml', 2),
  new TocItem('Multi root node', 'ext_multi_root_node', 2),
  new TocItem("Fields describing physical properties (Phong's BRDF) for <tt>Material</tt> node", 'ext_material_phong_brdf_fields', 2),
  new TocItem('Field <tt>separate</tt> for <tt>WWWInline</tt> node', 'ext_wwwinline_separate', 2),
));
$toc->echo_numbers = true;

?>

<?php echo pretty_heading("Kambi VRML extensions");  ?>

<p>Contents:
<?php echo $toc->html_toc(); ?>

<?php echo $toc->html_section(); ?>

<p>This is a VRML engine, so many programs here do something non-trivial
with VRML files.
<?php
/* Old and useless notes:

Many of our programs play with 3d models in VRML format.
Most importantly, there is
<?php echo a_href_page("view3dscene", "view3dscene"); ?> &mdash;
VRML (and some other formats) viewer, there is also
<?php echo a_href_page("rayhunter", "rayhunter"); ?> &mdash;
ray-tracer that uses VRML files,
<?php echo a_href_page("kambi_mgf2inv", "kambi_mgf2inv"); ?>
 that converts MGF to VRML with some extensions useful
for <i>path tracer</i> in <?php echo a_href_page('rayhunter', 'rayhunter'); ?>.
<?php echo a_href_page('Malfunction', 'malfunction'); ?> and
<?php echo a_href_page('lets_take_a_walk', 'lets_take_a_walk'); ?>
 also have levels, monsters etc. stored as VRML models.
Most of the work has been done with VRML 1.0,
but VRML 97 support is also done since August 2006, and
X3D will also be supported one day. */ ?>
 Not surprisingly, I needed at some point to extend what is allowed by
VRML specifications, for various reasons. This page documents these
extensions, so everyone can use them.</p>

<p>Note that some of these extensions may not be tolerated by other
VRML viewers. However:
<ul>
  <li><p>Many VRML 2.0 extensions may be preceeded by appropriate
    <tt>EXTERNPROTO</tt> statements,
    this will allow other VRML 2.0 implementations to at least gracefully
    omit them. <?php echo a_href_page("Kambi VRML test suite",
    "kambi_vrml_test_suite"); ?> uses this mechanism whenever possible,
    so that even things inside <tt>kambi_extensions/</tt> should be partially
    handled by other VRML browsers.</p>

    <p>Our extensions are identified by URN like
    "<tt>urn:vrmlengine.sourceforge.net:node:KambiTriangulation</tt>".</p>

    <p>Our extensions' external prototypes may specify a fallback URL
    <a href="http://vrmlengine.sourceforge.net/fallback_prototypes.wrl">http://vrmlengine.sourceforge.net/fallback_prototypes.wrl</a>
    for VRML 2.0. For X3D, analogous URL is
    <a href="http://vrmlengine.sourceforge.net/fallback_prototypes.x3dv">http://vrmlengine.sourceforge.net/fallback_prototypes.x3dv</a>.
    Such fallback URL will allow other VRML browsers to partially handle
    our extensions. For example, see <tt>EXTERNPROTO</tt> example
    for <a href="#ext_text3d">Text3D</a> &mdash; browsers that don't handle
    Text3D node directly should use our fallback URL and render Text3D
    like normal 2D text node.</p>

    <p>TODO: eventual goal is to make all extensions this way, so that they
    can be nicely omitted.
    Also, it would be nice to use VRML 1.0 similar feature,
    <tt>isA</tt> and <tt>fields</tt>, for the same purpose,
    but it's not implemented (and probably never will be, since VRML 1.0
    is basically dead and VRML 2.0 / X3D externproto is so much better).</p></li>

  <li><p>Some of VRML 1.0 extensions are borrowed from VRML 97 specification
    (e.g. <a href="#ext_light_attenuation">attenuation field for lights</a>),
    I just allow them also in VRML 1.0.</p></li>

  <li><p>Some other extensions like
    <a href="#ext_gzip">compressing VRML files by gzip</a>
    or <a href="#ext_multi_root_node">multiple root nodes in VRML 1.0</a>
    are often implemented in other VRML viewers.</p></li>
</ul>

<p>To understand specifications of these extensions you will
need some basic knowledge of VRML. Here you can find
<a href="http://www.web3d.org/x3d/specifications/vrml/">official
VRML 1.0 and 97 specifications</a> if you want to educate yourself.</p>

<p>VRML fields and nodes are specified on this page in the
convention somewhat similar to VRML 97 specification:
<?php echo
  node_begin("NodeName") .
  node_field('fieldKind', "FieldType", "fieldName", "default_value", "short comment") .
  node_dots() .
  node_end();
?>

<p>Example VRML models that use these extensions may be found
in <?php echo a_href_page("Kambi VRML test suite",
"kambi_vrml_test_suite"); ?> &mdash; look inside
<tt>vrml_1/kambi_extensions/</tt>, <tt>vrml_2/kambi_extensions/</tt>,
<tt>x3d/kambi_extensions/</tt>, <tt>x3d/shaders/kambi_extensions/</tt>
subdirectories.

<?php echo $toc->html_section(); ?>

<?php echo $toc->html_section(); ?>

<?php echo $toc->html_section(); ?>

    <p>Instead of <tt>Appearance</tt> node, you can use <tt>KambiApperance</tt>
    node that adds some new fields useful for bump mapping:

    <?php
      echo node_begin('KambiAppearance');
      $node_format_fd_name_pad = 15;
      echo
      node_dots('all normal Appearance fields') .
      node_field('exposedField', 'SFNode', 'normalMap' , 'NULL', 'only texture nodes (ImageTexture, MovieTexture, PixelTexture) allowed') .
      node_field('exposedField', 'SFNode', 'heightMap' , 'NULL', 'only texture nodes (ImageTexture, MovieTexture, PixelTexture) allowed') .
      node_field('exposedField', 'SFFloat', 'heightMapScale', '0.01', 'must be &gt; 0, meaningful only if heightMap specified') .
      node_end();
    ?>

    <?php
      echo '<table align="right">' .
        '<tr><td>' . medium_image_progs_demo_core("bump_demo_leaf_nobump.png", 'Leaf (without bump mapping)') .
        '<tr><td>' . medium_image_progs_demo_core("bump_demo_leaf.png", 'Leaf (with bump mapping)') .
        '<tr><td>' . medium_image_progs_demo_core("parallax_demo_lion_noparallax.png", 'Lion texture (without parallax mapping)') .
        '<tr><td>' . medium_image_progs_demo_core("parallax_demo_lion.png", 'Lion texture (with parallax mapping)') .
        '</table>';
    ?>

    <p>Texture specified as <tt>normalMap</tt> describes normal vector
    values on each texel. Normal vector values are actually encoded as colors:
    normal vector (x, y, z) should be encoded as RGB((x+1)/2, (y+1)/2, (z+1)/2).
    You can use e.g.
    <a href="http://nifelheim.dyndns.org/~cocidius/normalmap/">GIMP
    normalmap plugin</a> to generate such normal maps.
    (<i>Hint:</i> Remember to check "invert y" when generating normal maps,
    in image editing programs image Y grows down but we want Y
    (as interpreted by normals) to grow up, just like texture T coordinate.)</p>

    <p>This allows bump mapping to be used. If you set BumpMappingMaximum attribute
    (and pass light position for bump mapping), our VRML engine will
    automatically do appropriate bump mapping.</p>

    <p><tt>normalMap</tt> is enough to use normal bump mapping ("dot product"
    method, done by pure multitexturing or GLSL programs, depending on
    OpenGL capabilities). If you additionally specify some texture as
    <tt>heightMap</tt> then parallax mapping
    (<a href="http://graphics.cs.brown.edu/games/SteepParallax/index.html">steep parallax mapping with
    self-shadowing</a>, if used OpenGL will support it) will be additionally used.
    <tt>heightMapScale</tt> allows you to tweak the perceived height of bumps
    for parallax mapping.</p>

    <p>You can test it in</p>

    <ul>
      <li><p>You can turn it on and see the effects in
        <?php echo a_href_page("view3dscene", "view3dscene") ?>.
        In view3dscene, for simplicity, bump mapping light position is always
        set to current camera position. Sample models with normal maps
        and height maps are inside <?php echo a_href_page('Kambi VRML test suite',
        'kambi_vrml_test_suite'); ?>, in directory
        <tt>vrml_2/kambi_extensions/bump_mapping/</tt>.</p></li>

      <li><p>You can see this used in
        <?php echo a_href_page("The Castle", "castle") ?> "The Fountain" level.
        Authors of new levels are encouraged to use bump mapping&nbsp;!</p></li>

      <li><p>Programmers may also compile and run example program
        <tt>3dmodels.gl/examples/bump_mapping/</tt> in
        <?php echo a_href_page('engine sources', 'kambi_vrml_game_engine'); ?>, this allows
        to really play with bump mapping settings and see how to use this in
        your own programs.</p></li>
    </ul>

    <p>Note that currently bump mapping is used only when normal texture
    ("normal" texture as in "texture used for normal purposes, in <tt>texture</tt>
    field of Appearance") is also specified. And it's used only with
    IndexedFaceSet nodes for now &mdash; implementation of this is
    supposed to be extended, feature requests are welcome!</p>

<?php echo $toc->html_section(); ?>

    <p>We add new node:

    <?php
      echo node_begin('Text3D');
      echo
      node_field('exposedField', 'MFString', 'string', '[]') .
      node_field('exposedField', 'SFNode', 'fontStyle', 'NULL') .
      node_field('exposedField', 'MFFloat', 'length', '[]') .
      node_field('exposedField', 'SFFloat', 'maxExtent', '0') .
      node_field('exposedField', 'SFFloat', 'depth', '0.1', 'must be &gt;= 0') .
      node_field('exposedField', 'SFBool', 'solid', 'TRUE') .
      node_end();
    ?>

    <p>This renders the text, pretty much like <tt>Text</tt> node from
    VRML 97 (see VRML 97 specification about <tt>string</tt>, <tt>fontStyle</tt>,
    <tt>length</tt>, <tt>maxExtent</tt> fields). But the text is 3D:
    it's "pushed" by the amount <tt>depth</tt> into negative Z. The normal
    text is on Z = 0, the 3D text had front cap on Z = 0, back cap on Z = -Depth,
    and of course the extrusion (sides).</p>

    <p>Also, it's natural to apply backface culling to such text, so we have
    a <tt>solid</tt> field. When true (default), then backface culling is done.
    This may provide much speedup, unless camera is able to enter
    "inside" the text geometry (in which case solid should be set to <tt>FALSE</tt>).</p>

    <p>If <tt>depth</tt> is zero, then normal 2D text is rendered.
    However, backface culling may still be applied (if <tt>solid</tt> is true)
    &mdash; so this node also allows you to make 2D text that's supposed to be
    visible from only front side.</p>

    <p>See <?php echo a_href_page('Kambi VRML test suite',
    'kambi_vrml_test_suite'); ?>, file
    <tt>vrml_2/kambi_extensions/text_depth.wrl</tt> for example use of this.</p>

    <p>Compatibility:
    <ul>
      <li>You should specify external prototype before using this node:

        <pre>
EXTERNPROTO Text3D [
  exposedField MFString string
  exposedField SFNode fontStyle
  exposedField MFFloat length
  exposedField SFFloat maxExtent
  exposedField SFFloat depth
  exposedField SFBool solid
] [ "urn:vrmlengine.sourceforge.net:node:Text3D",
    "http://vrmlengine.sourceforge.net/fallback_prototypes.wrl#Text3D" ]
</pre>

        <p>This way other VRML browsers should be able to
        render Text3D node like normal 2D Text.</p></li>

      <li>This is somewhat compatible to <a href="http://www.parallelgraphics.com/developer/products/cortona/extensions/text3d/">Text3D
        node from Parallel Graphics</a>. At the beginning I implemented this
        externsion differently (<tt>kambiDepth</tt>, <tt>kambiSolid</tt> fields
        for <tt>AsciiText</tt> and <tt>Text</tt> nodes). But later I found
        these Parallel Graphics <tt>Text3D</tt> definition, so I decided
        to make my version compatible.</li>
    </ul>

<?php echo $toc->html_section(); ?>

  <?php
    echo '<table align="right">' .
        '<tr><td>' . medium_image_progs_demo_core("blend_mode_demo.png", 'Various blend modes with transparent teapots') .
        '</table>';
    ?>

    <p>We add new field to <tt>KambiAppearance</tt> node: <tt>blendMode</tt> (SFNode,
    NULL by default, inputOutput). It's allowed to put there <tt>BlendMode</tt>
    node to specify blending mode done for partially-transparent objects.
    BlendMode node is not X3D standard, but it's specified by Avalon:
    <a href="http://www.instantreality.org/documentation/nodetype/BlendMode/">see
    BlendNode specification</a>.

    <p>From Avalon spec, our engine supports a subset of fields: <tt>srcFactor</tt>,
    <tt>destFactor</tt>, <tt>color</tt>, <tt>colorTransparency</tt>.
    Note that some values require newer
    OpenGL versions, we will eventually fallback on browser-specific blending
    modes (you can set them explicitly in <?php echo a_href_page("view3dscene", "view3dscene") ?>).

    <p>For example:

<pre>
  appearance KambiAppearance {
    material Material {
      transparency 0.5
    }
    blendMode BlendMode {
      srcFactor "src_alpha" # actually this srcFactor is the default
      destFactor "one"
    }
  }
</pre>

    <p>Example above sets blending to an often-desired equation where the order of rendering
    doesn't matter. It's very useful for models with complex 3D partially-transparent objects,
    otherwise traditional approach (src_alpha and one_minus_src_alpha) may cause rendering
    artifacts.

<?php echo $toc->html_section(); ?>

    <table align="right" class="table_with_movie_thumbnail table_with_thumbs_and_text">
      <tr><td><?php echo medium_image_progs_demo_core("fireplace_movie_texture_demo.png", 'Fireplace demo screenshot'); ?>
      <tr><td>This movie shows how it looks animated. You can also
        <?php echo current_www_a_href_size('get AVI version with much better quality',
          'movies/fireplace_demo.avi'); ?>
        <?php if (!HTML_VALIDATION) { ?>
        <object class="youtube_thumbnail_video"><param name="movie" value="http://www.youtube.com/v/V-EJvVbi1DQ"> </param> <embed src="http://www.youtube.com/v/V-EJvVbi1DQ" type="application/x-shockwave-flash" width="200" height="167"> </embed> </object>
        <?php } ?>
      </td></tr>
    </table>

    <p>For <tt>MovieTexture</tt> nodes, you can use an URL like
    <tt>image%d.png</tt> to load movie from a sequence of images.
    This will load all successive images, substituting counter
    in place of <tt>%d</tt>, starting from 1.

    <p>You can specify a number between <tt>%</tt> and <tt>d</tt>,
    like <tt>%4d</tt>, to pad counter with zeros. For example, normal
    <tt>%d.png</tt> results in names like 1.png, 2.png, ..., 9.png, 10.png...
    But <tt>%4d.png</tt> results in names like 0001.png,
    0002.png, ..., 0009.png, 0010.png, ...

    <p>Such movie will always be considered to run at the speed of 25 frames
    per second.

    <p>A simple image filename (without <tt>%d</tt> pattern) is also accepted
    as a movie URL. This just loads a trivial movie, that consists of one
    frame and is always still...

    <p>Allowed image formats are just like everywhere in our engine &mdash;
    PNG, JPEG and many others, see <?php echo a_href_page('glViewImage docs',
    'glviewimage'); ?> for the list.

    <p>Besides the fact that loading image sequence doesn't require
    ffmpeg installed, using image sequence has also one very important
    advantage over any other movie format: <i>you can use images
    with alpha channel</i> (e.g. in PNG format), and MovieTexture
    will be rendered with
    alpha channel appropriately. This is crucial if you want to have
    a video of smoke or flame in your game, since such textures usually
    require an alpha channel.

    <p>Samples of <tt>MovieTexture</tt> usage
    are inside <?php echo a_href_page('Kambi VRML test suite',
    'kambi_vrml_test_suite'); ?>, in directory
    <tt>vrml_2/movie_texture/</tt>.

<?php echo $toc->html_section(); ?>

    <p>New <tt>KambiInline</tt> node extends standard <tt>Inline</tt>
    node, allowing you to do something like search-and-replace automatically
    on inlined content.

    <?php
      echo node_begin('KambiInline');
      $node_format_fd_name_pad = 10;
      echo
      node_dots('all normal Inline fields') .
      node_field('exposedField', 'MFString', 'replaceNames', '[]') .
      node_field('exposedField', 'MFNode', 'replaceNodes' , '[]', 'any VRML node is valid on this list') .
      node_end();
    ?>

    <p><tt>replaceNames</tt> specifies the node names in inlined content
    to search. <tt>replaceNodes</tt> are the new nodes to replace with.
    <tt>replaceNames</tt> and <tt>replaceNodes</tt> fields should have the same
    length. By default, the lists are empty and so <tt>KambiInline</tt>
    works exactly like standard <tt>Inline</tt> node.

    <p>An example when this is extremely useful: imagine you have a VRML file
    generated by exporting from some 3D authoring tool. Imagine that this tool
    is not capable of producing some VRML content, so you write a couple
    of VRML nodes by hand, and inline the generated file. For example
    this is your generated file, <tt>generated.wrl</tt>:

<pre class="vrml_code">
#VRML V2.0 utf8

Shape {
  geometry Box { size 1 2 3 }
  appearance Appearance {
    texture DEF Tex ImageTexture { url "test.png" }
  }
}
</pre>

    <p>and this is your file created by hand, <tt>final.wrl</tt>:

<pre class="vrml_code">
#VRML V2.0 utf8

# File written by hand, because your 3D authoring tool cannot generate
# NavigationInfo node.

NavigationInfo { headlight "FALSE" }
Inline { url "generated.wrl" }
</pre>

    <p>The advantage of this system is that you can get back to working
    with your 3D authoring tool, export as many times as you want
    overriding <tt>generated.wrl</tt>, and your hand-crafted content
    stays nicely in <tt>final.wrl</tt>.

    <p>The problem of the above example: what happens if you want
    to always automatically replace some part inside <tt>generated.wrl</tt>?
    For example, assume that your 3D authoring tool cannot export with
    <tt>MovieTexture</tt> node, but you would like to use it instead of
    <tt>ImageTexture</tt>. Of course, you could just change
    <tt>generated.wrl</tt> in any text editor, but this gets very tiresome
    and dangerous if you plan to later regenerate <tt>generated.wrl</tt> from
    3D authoring tool: you would have to remember to always replace
    <tt>ImageTexture</tt> to <tt>MovieTexture</tt> after exporting. Needless to say,
    it's easy to forget about such thing, and it gets very annoying when
    there are more replaces needed. Here's when <tt>KambiInline</tt>
    comes to help. Imagine that you use the same <tt>generated.wrl</tt>
    file, and as <tt>final.wrl</tt> you will use

<pre class="vrml_code">
#VRML V2.0 utf8

# File written by hand, because your 3D authoring tool cannot generate
# MovieTexture node.

KambiInline {
  url "generated.wrl"
  replaceNames "Tex"
  replaceNodes MovieTexture { url "test.avi" }
}
</pre>

    <p>Each time when loading <tt>final.wrl</tt>, our engine will
    automatically replace in the VRML graph node <tt>Tex</tt> with
    specified <tt>MovieTexture</tt>. Of course the "replacing" happens
    only in the memory, it's not written back to any file, your files
    are untouched. Effectively, the effect is like you would load a file

<pre class="vrml_code">
#VRML V2.0 utf8

Shape {
  geometry Box { size 1 2 3 }
  appearance Appearance {
    texture MovieTexture { url "test.avi" }
  }
}
</pre>

<?php echo $toc->html_section(); ?>

    <p>See <?php echo a_href_page_hashlink('X3D implementation status about programmable shaders',
      'vrml_implementation_status', 'shaders'); ?>.

    <p>Since we officially support X3D now, this is not really an extension,
    it's just normal X3D feature. You can use it in VRML 2.0 models too,
    as usual our engine allows you to mix VRML versions freely.

<?php echo $toc->html_section(); ?>

    <p>Besides <tt>BlendMode</tt>, we also read some other Avalon extensions.
    See <a href="http://instant-reality.com/">instant-reality</a>
    and in particular <a href="http://instant-reality.com/documentation/nodetype/">the
    specifications of Avalon extensions</a>.

    <p>Some subset of the Avalon extensions that we actually <i>handle</i>
    (that is, actually do what we are supposed to do, not only reading them)
    is listed below. Please note that I implemented this all looking at Avalon
    specifications, which are quite terse. (I didn't actually run
    instant-reality program (closed-source,
    not installable under current Debian testing).) Please report
    any incompatibilities.

    <ul>
      <li><p><a href="http://instant-reality.com/documentation/nodetype/MatrixTransform/"><tt>MatrixTransform</tt></a><br/>
        (supported <tt>matrix</tt> field, and the standard <tt>X3DGroupingNode</tt> fields)

        <p>This is analogous to <tt>Transform</tt> node, but specifies explicit
        4x4 matrix. Note that VRML 1.0 also had <tt>MatrixTransform</tt> node
        (we also handle it), although specified a little differently.
        Later VRML 97 and X3D removed the <tt>MatrixTransform</tt> node
        from official specification &mdash; this extension fills the gap.

        <p>Note that this node was removed from specifications for a good
        reason. It's difficult to invert arbitrary 4x4 matrix, and extract
        particular features (like scaling factor) from it. Our engine
        cannot invert matrices, and it extracts scaling factors by very naive
        method. (Although this is planned to be fixed using
        <a href="http://tog.acm.org/GraphicsGems/gemsii/unmatrix.c">unmatrix.c algorithm</a>.)
        So in some cases you may experience trouble when using this
        node. The bottom line is: <i>You are well adviced to try
        to express all transformations
        using stardard <tt>Transform</tt> node</i>.

        <p>This node may be useful
        when you really have no choice (for example, when converting from
        Collada files that have transformation written as explicit 4x4 matrix,
        it's natural to convert it to VRML <tt>MatrixTransform</tt>).

      <li><p><a href="http://instant-reality.com/documentation/nodetype/Logger/"><tt>Logger</tt></a><br/>
        (supported <tt>level</tt>, <tt>logFile</tt>,
        <tt>enabled</tt> fields and <tt>write</tt> inputOnly event)

        <?php
          echo '<table align="right">' .
            '<tr><td>' . medium_image_progs_demo_core("logger.png", 'Logger node demo') .
            '</table>';
        ?>

        <p>An extremely useful debugger when playing with VRML / X3D routes
        and events. The idea is simple: whatever is sent to <tt>write</tt>
        input event is logged on the console. <tt>write</tt> event has special type
        (Avalon calls this <tt>XFAny</tt>) that allows to receive <i>any</i>
        VRML field type.

        <p>Other properties allow to control logging better.
        When <tt>enabled</tt> is false, nothing is logged.
        <tt>level</tt> controls the amount of logged info:
        <ol>
          <li>nothing,
          <li>log sending field name, type, timestamp,
          <li>additionally log received value,
          <li>additionally log sending node name, type.
        </ol>

        <p><tt>logFile</tt>, when non-empty, specifies the filename to
        write log information to. (When <tt>logFile</tt> is empty, it's
        all simply dumped on standard output, i.e. usually console.)
        As a security measure (you really do not want to allow an author
        of X3D file to overwrite arbitrary files without asking user),
        in my implementation only the basename of the <tt>logFile</tt> matters,
        the file is always saved into current directory. Moreover, filename
        is like <tt>view3dscene_logger_XXX_%d.log</tt>, where "view3dscene"
        is the name of the program, "XXX" is the name specified in <tt>logFile</tt>,
        and "%d" is just next free number. This way logger output file
        is predictable, and should never overwrite your data.

        <p>These security measures were added by my implementation &mdash;
        Avalon spec simply says that <tt>logFile</tt> is the name of the file,
        I don't know how they handled security problems with logFile.

      <li><p><a href="http://instant-reality.com/documentation/nodetype/Teapot/"><tt>Teapot</tt></a><br/>
        (supported <tt>size</tt> and <tt>solid</tt> fields)

        <?php
          echo '<table align="right">' .
            '<tr><td>' . medium_image_progs_demo_core("teapot_demo.png", 'Teapot node demo') .
            '</table>';
        ?>

        <p>Simply renders a teapot. <tt>size</tt> field allows you to scale
        the teapot, much like the standard <tt>Box</tt> node. The default
        size (3, 3, 3) means that the longest size of teapot bounding box
        is 3.0 (all other sizes are actually slightly smaller).
        Changing size scales the teapot (assuming that size = 3 means "default size").

        <p>For the sake of VRML / X3D standards, I do not really advice
        using this node... VRML developers should spend their time better
        than to implement such nodes of little practical use :)
        But it's here for you for testing purposes.
    </ul>

<?php echo $toc->html_section(); ?>

    Because of the way how I implemented VRML 1.0, 2.0 and X3D handling,
    you have effectively the <i>sum of all VRML features</i>
    available. Which means that actually you can mix VRML 1.0 and 2.0 and X3D
    nodes to some extent. If given node name exists in two VRML/X3D versions,
    then VRML/X3D file header defines how the node behaves. Otherwise,
    node behaves according to it's VRML/X3D specification.

    <p>For example, this means that a couple of VRML 2.0/X3D nodes
    are available (and behave exactly like they should) also for VRML 1.0
    authors:
    <ul>
      <li><a href="http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/nodesRef.html#Background">Background</a>
      <li><a href="http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/nodesRef.html#Fog">Fog</a>
      <li><a href="http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/nodesRef.html#WorldInfo">WorldInfo</a>
      <li><a href="http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/nodesRef.html#NavigationInfo">NavigationInfo</a>
    </ul>

    <p>Also VRML 1.0 things are available in VRML 2.0, e.g.
    <tt>OrthographicCamera</tt> (this is one thing not available
    in VRML 2.0 specification; although for X3D
    you should rather use standard <tt>OrthoViewpoint</tt>).</p>

    <p>Also things like GLSL shaders (from X3D) are available in VRML 97.

    <p>You can also <a href="#ext_inline_for_all">freely include
    VRML 1.0 files inside VRML 2.0, or X3D, or the other way around</a>.

<?php echo $toc->html_section(); ?>

    I add to <tt>Fog</tt> node some additional fields to allow
    definition of volumetric fog:

    <?php echo node_begin("Fog");
      $node_format_fd_type_pad=8;
      $node_format_fd_name_pad=28;
      $node_format_fd_def_pad=8;
      echo
      node_dots() .
      node_field('exposedField', "SFBool", "volumetric", "FALSE") .
      node_field('exposedField', "SFVec3f", "volumetricDirection",  "0 -1 0", "any non-zero vector") .
      node_field('exposedField', "SFFloat", "volumetricVisibilityStart",  "0") .
      node_field('exposedField', "SFNode", "alternative", "# NULL or another Fog node") .
      node_end();
    ?>

    <p>Meaning: when <tt>volumetric</tt> is <tt>FALSE</tt> (the default),
    every other <tt>volumetricXxx</tt> field is ignored and Fog behaves
    like defined in VRML 97 spec. If <tt>volumetric</tt> is <tt>TRUE</tt>,
    then the volumetric fog is used.

    <p><tt>volumetricDirection</tt> is the direction of the volumetric fog,
    it must be any non-zero vector (it's length doesn't matter).
    Every vertex of the 3D scene is projected
    on <tt>volumetricDirection</tt> vector, and then the resulting
    distance (signed distance, i.e. along the direction of this vector)
    of this point is used to determine fog amount. For example:
    in the simple case when <tt>volumetricDirection</tt>
    is <tt>(0, 1, 0)</tt>, then the Y coordinate of every vertex determines
    the amount of fog. In the default case when
    <tt>volumetricDirection</tt> is <tt>(0, -1, 0)</tt>,
    then the <i>negated</i> Y coordinate of every vertex determines
    the amount of fog. I will call such calculated amount of fog the
    <i>FogAmount</i>.

    <p>Now <i>FogAmount</i> values between
    <tt>volumetricVisibilityStart</tt> and
    <tt>volumetricVisibilityStart + visibilityRange</tt>
    correspond to fog color being applied in appropriately 0 (none)
    and 1 (full) amount. <tt>fogType</tt> determines how values
    between are interpolated (in the simple <tt>LINEAR</tt> case
    they are interpolated linearly).

    <p>Note that <tt>volumetricVisibilityStart</tt> is transformed
    by the <tt>Fog</tt> node transformation scaling,
    just like <tt>visibilityRange</tt> in VRML spec.

    <p>Note that <tt>visibilityRange</tt> must stay >= 0, as required
    by VRML specification. This means that <tt>volumetricDirection</tt>
    always specifies the direction of the fog: the more into
    <tt>volumetricDirection</tt>, the more fog appears. For example,
    if your world is oriented such that the +Y is the "up", and ground
    is on Y = 0, and you want your fog to start from height Y = 20,
    you should set <tt>volumetricDirection</tt> to <tt>(0, -1, 0)</tt>
    (actually, that's the default) and set <tt>volumetricVisibilityStart</tt>
    to <tt>-20</tt> (note <tt>-20</tt> instead of <tt>20</tt>;
    flipping <tt>volumetricDirection</tt> flips also the meaning of
    <tt>volumetricVisibilityStart</tt>).

    <p>Oh, and note that in our programs for now <tt>EXPONENTIAL</tt> fog
    (both volumetric and not) is actually approximated by OpenGL
    exponential fog. Equations for OpenGL exponential fog and VRML
    exponential fog are actually different and incompatible,
    so results will be a little different than they should be.

    <p><?php echo a_href_page('VRML test suite',
    'kambi_vrml_test_suite'); ?>
    has test VRMLs for this
    (see <tt>vrml_1/kambi_extensions/fog_volumetric/</tt> and
    <tt>vrml_2/kambi_extensions/fog_volumetric/</tt> subdirectories).
    Also our games <?php echo a_href_page('malfunction', 'malfunction'); ?>
    and <?php echo a_href_page('The Castle', 'castle'); ?> use it.

    <p>One additional field not explained yet: <tt>alternative</tt>.
    This will be used if current graphic output (e.g. OpenGL implementation)
    for any reason doesn't allow volumetric fog (or at least doesn't
    allow it to be implemented efficiently). Currently, this means
    that <tt>GL_EXT_fog_coord</tt> extension is not supported.
    In such case we'll look at <tt>alternative</tt> field:</p>

    <ul>
      <li><p>If <tt>alternative</tt> is <tt>NULL</tt> (the default),
        then no fog will be rendered.</p></li>

      <li><p>Otherwise we'll try to use fog node recorded in <tt>alternative</tt>.</p>

        <p>If fog node recorded in <tt>alternative</tt> is not suitable too
        (e.g. because it also uses volumetric fog) then we'll look at it's
        <tt>alternative</tt> field in turn... So in the usual case
        a fog node placed within the <tt>alternative</tt> will not use
        volumetric fog.</p>
      </li>
    </ul>

    <p><tt>alternative</tt> will also be tried if the value specified in
    <tt>fogType</tt> field of <tt>Fog</tt> node is not recognized.</p>

<?php echo $toc->html_section(); ?>

    New field for <tt>Material</tt> node:

    <?php echo node_begin("Material");
      $node_format_fd_name_pad = 20;
      $node_format_fd_def_pad = 10;

      echo
      node_dots() .
      node_field('exposedField', "SFBool", "fogImmune", "FALSE") .
      node_end();
    ?>

    <p>When <tt>fogImmune</tt> of given object's material is <tt>TRUE</tt>,
    then the fog effect (specified by <tt>Fog</tt> node) is <i>not applied</i>
    to the object. Object is "immune" to fog.

    <p>This should be used only in a very special cases, when the scene
    looks better with the material left without fog effect.
    For example, I used this in <?php echo a_href_page('The Castle', 'castle'); ?>
    for a river surface material
    &mdash; it's a transparent material, and the whole level is covered
    with a volumetric fog. It just looks better when the river surface
    is not affected by the fog color.

<?php echo $toc->html_section(); ?>

    Inline nodes (<tt>Inline</tt> and <tt>InlineLoadControl</tt> in VRML &gt;= 2.0
    and <tt>WWWInline</tt> in VRML 1.0) allow you to include not only
    other VRML files, but also other 3DS, MD3, Wavefront OBJ, Collada models.
    Internally, all those formats are converted to VRML/X3D before
    displaying anyway. If you want to precisely know how the conversion
    to VRML/X3D goes, you can always do the explicit conversion to VRML/X3D
    by using "<i>Save as VRML</i>"
    <?php echo a_href_page("view3dscene", "view3dscene") ?> command.

    <p>Also, you can freely mix VRML versions when including.
    You're free to include VRML 1.0 file inside VRML 2.0 file, or X3D,
    or the other way around. Everything works.

    <p>This also works for jumping to scenes by clicking on an
    <tt>Anchor</tt> node &mdash; you can make an <tt>Anchor</tt> to any
    VRML version, or a 3DS, Collada etc. file.

<?php echo $toc->html_section(); ?>

    <?php
      echo '<table align="right">' .
        '<tr><td>' . medium_image_progs_demo_core("kambi_triangulation_demo.png", 'KambiTriangulation demo screenshot') .
        '</table>';
    ?>

    <p>New node:

    <?php echo node_begin("KambiTriangulation");
      $node_format_fd_type_pad=8;
      $node_format_fd_name_pad=15;
      $node_format_fd_def_pad=5;
      echo
      node_field('exposedField', "SFInt32", "quadricSlices", "-1", "{-1} + [3, infinity)") .
      node_field('exposedField', "SFInt32", "quadricStacks", "-1", "{-1} + [2, infinity)") .
      node_field('exposedField', "SFInt32", "rectDivisions", "-1", "[-1, infinity)") .
      node_end();
    ?>

    <p>This node affects rendering of subsequent <tt>Sphere</tt>,
    <tt>Cylinder</tt>, <tt>Cone</tt> and <tt>Cube</tt> nodes.
    For VRML  1.0 you can delimit the effect of this node by
    using <tt>Separator</tt>
    node, just like with other VRML "state changing" nodes.
    For VRML 2.0 every grouping node (like <tt>Group</tt>)
    always delimits this, so it only affects nodes within
    it's parent grouping node (like many other VRML 2.0 nodes,
    e.g. <tt>DirectionalLight</tt> or sensors).

    <p>When rendering sphere, cylinder, cone or cube we
    will triangulate (divide the surfaces into triangles)
    with settings specified in last <tt>KambiTriangulation</tt> node.
    <tt>quadricSlices</tt> divides like pizza slices,
    <tt>quadricStacks</tt> divides like tower stacks,
    <tt>rectDivisions</tt> divides rectangular surfaces
    of a <tt>Cube</tt>. More precise description of this triangulation
    is given at <?php echo a_href_page_hashlink(
    'description of <tt>--detail-...</tt> options in view3dscene documentation',
    'view3dscene', 'command_line_options_detail') ?>.
    Comments given there about so-called <i>over-triangulating</i>
    apply also here.

    <p>Special value -1 for each of these fields means
    that the program can use it's default value.
    In case of <?php echo a_href_page("view3dscene", "view3dscene") ?> and
    <?php echo a_href_page("rayhunter", "rayhunter") ?>
    they will use values specified by command-line options
    <tt>--detail-...</tt> (or just compiled-in
    values (see source code) if you didn't specify <tt>--detail-...</tt>
    options).

    <p>Note that this node gives only a <i>hints</i> to the renderer.
    Various algorithms and programs may realize triangulation differently,
    and then hints given by this node may be interpreted somewhat
    differently or just ignored.
    <!-- np. program mo�e ustala� jako�� triangulacji w zale�no�ci
    od odleg�o�ci obiektu od patrz�cego i wtedy zaimplementowanie
    obs�ugi tego w�z�a wi�za�oby si� z dodatkowymi komplikacjami -->
    That said, this node is useful when you're designing some
    VRML models and you want to fine-tune the compromise
    between OpenGL rendering speed and quality of some objects.
    Generally, triangulate more if the object is large or you
    want to see light effects (like light spot) looking good.
    If the object is small you can triangulate less, to get
    better rendering time.

    <p>Test VRML file:
    see <?php echo a_href_page('Kambi VRML test suite',
    'kambi_vrml_test_suite'); ?>, file
    <tt>kambi_vrml_test_suite/vrml_2/kambi_extensions/kambi_triangulation.wrl</tt>.

<?php echo $toc->html_section(); ?>

    All our programs can handle VRML files compressed with gzip.

    <p>E.g. you can call <?php echo a_href_page('view3dscene',
    'view3dscene'); ?> like
    <pre>
      view3dscene my_compressed_vrml_file.wrl.gz
    </pre>
    and you can use WWWInline nodes that refer to gzip-compressed VRML
    files, like
    <pre>
      WWWInline { name "my_compressed_vrml_file.wrl.gz" }
    </pre>

    <p>Filenames ending with <tt>.wrl.gz</tt> or <tt>.wrz</tt> are
    assumed to be always compressed by gzip.</p>

    <p>Files with normal extension <tt>.wrl</tt> but actually compressed by gzip
    are also handled OK.
    Currently, there's a small exception to this: when you give view3dscene
    VRML file on stdin, this file must be already uncompressed
    (so you may need to pipe your files through <tt>gunzip -c</tt>).
    TODO: this is intended to be fixed, although honestly it has rather low
    priority now.</p>

    <p><i>A personal feeling about this feature from the author (Kambi):</i>
    I honestly dislike the tendency to compress the files with gzip
    and then change the extension  back to normal <tt>.wrl</tt>.
    It's handled by our engine, but only because so many people do it.
    I agree that it's often sensible to compress VRML files
    by gzip (especially since before X3D, there was no binary encoding for VRML files).
    But when you do it, it's also sensible to leave the extension as <tt>.wrl.gz</tt>,
    instead of forcing it back into <tt>.wrl</tt>, hiding the fact that contents
    are compressed by gzip. Reason: while many VRML browsers detect the fact that
    file is compressed by gzip, many other programs, that look only at file
    extension, like text editors, do not recognize that it's gzip data.
    So they treat <tt>.wrl</tt> file as a stream of unknown binary data.
    Programs that analyze only file contents, like Unix <tt>file</tt>, see that it's
    a gzip data, but then they don't report that it's VRML file (since this would
    require decompressing).</p>

    <p>Also note that WWW servers, like Apache, when queried by modern WWW browser,
    can compress your VRML files on the fly. So, assuming that VRML browsers
    (that automatically fetch URLs) will be also intelligent, the compression
    is done magically over HTTP protocol, and you don't have to actually compress
    VRML files to save bandwidth.</p>

<?php echo $toc->html_section(); ?>

    Various details about how we handle NavigationInfo node in
    <?php echo a_href_page('view3dscene','view3dscene'); ?>:
    <ul>
      <li>Note that <tt>--camera-radius</tt> command-line option overrides
        whatever was specified by <tt>avatarSize[0]</tt>.

      <li><tt>avatarSize[2]</tt> (tallest object over which you can move)
        is ignored.

      <li><tt>speed</tt> is honoured as appropriate, it sets
        the speed in meters/second.

      <li><tt>type</tt> of navigation: <tt>EXAMINE</tt>, <tt>WALK</tt>
        and <tt>FLY</tt> are fully supported. They map to appropriate
        view3dscene navigation styles and settings
        (accordingly: <tt>Examine</tt> style,
        <tt>Walk</tt> style with gravity and moving versus <i>gravity</i> up vector,
        <tt>Walk</tt> style without gravity and moving versus <i>current</i> up vector).
        <!--
        Nothing maps to view3dscene
        <tt>FreeWalk</tt> and that's OK, because <tt>FreeWalk</tt>
        is actually a hack that is comfortable only if the scene
        navigation is not properly defined :)
        -->

      <li>Since our engine doesn't support any scripting, forcing camera
        to be stationary or hiding some user controls doesn't make much sense.
        That's why <tt>speed</tt> = 0.0 is ignored (i.e.
        equivalent to <tt>speed</tt> = 1.0), navigation type
        <tt>NONE</tt> is ignored, and the presense of navigation type
        <tt>ANY</tt> is not important (view3dscene always
        shows controls to change navigation settings). In the future this
        may change, if some serious scripting will be implemented.
    </ul>

    <p>When no <tt>NavigationInfo</tt> node is present in the scene,
    we try to intelligently guess related properties.
    (We try to guess "intelligently" because simply assuming that
    "no NavigationInfo node" is equivalent to "presence of
    default NavigationInfo" is <i>not good</i> for most scenes).
    <ul>
      <li><tt>avatarSize[0]</tt> and <tt>avatarSize[1]</tt>
        are guessed based on scene's bounding box sizes.

      <li><tt>headlight</tt> is set to true if and only if there are no
        lights defined in the scene.

      <li><tt>type</tt> remains as it was before loading new scene.

      <li><tt>speed</tt> is calculated to something that should "feel sensible"
        based on scene's bounding box sizes.
    </ul>

<?php echo $toc->html_section(); ?>

    Standard VRML way of specifying camera orientation
    (look direction and up vector) is to use <tt>orientation</tt> field
    that says how to rotate standard look direction vector (&lt;0,0,-1&gt;)
    and standard up vector (&lt;0,1,0&gt;). While I agree that this
    way of specifying camera orientation has some advantages
    (e.g. we don't have the problem with the uncertainty
    "<i>Is look direction vector length meaningful ?</i>")
    I think that this is very uncomfortable for humans.

    <p>Reasoning:
    <ol>
      <li>It's very difficult to write such <tt>orientation</tt> field
        by human, without some calculator. When you set up
        your camera, you're thinking about "<i>In what direction it looks ?</i>"
        and "<i>Where is my head ?</i>", i.e. you're thinking
        about look and up vectors.

      <li>Converting between <tt>orientation</tt> and look and up
        vectors is trivial for computers but quite hard for humans
        without a calculator (especially if real-world values are
        involved, that usually don't look like "nice numbers").
        Which means that when I look at source code of your VRML
        camera node and I see your <tt>orientation</tt> field
        &mdash; well, I still have no idea how your camera is oriented.
        I have to fire up some calculating program, or one
        of programs that view VRML (like view3dscene).
        This is not some terrible disadvantage, but still it matters
        for me.

      <li><tt>orientation</tt> is written with respect to standard
        look (&lt;0,0,-1&gt;) and up (&lt;0,1,0&gt;) vectors.
        So if I want to imagine camera orientation in my head &mdash;
        I have to remember these standard vectors.

      <li>4th component of orientation is in radians, that
        are not nice for humans (when specified as floating point
        constants, like in VRMLs, as opposed to multiplies of &pi;,
        like usually in mathematics). E.g. what's more obvious for you:
        "<i>1.5707963268 radians</i>" or "<i>90 degrees</i>" ? Again, these are equal
        for computer, but not readily equal for human
        (actually, "<i>1.5707963268 radians</i>" is not precisely equal to
        "<i>90 degrees</i>").
    </ol>

    <p>Also, VRML 2.0 spec says that the gravity upward vector should
    be taken as +Y vector transformed by whatever transformation is applied
    to <tt>Viewpoint</tt> node. This also causes similar problems,
    since e.g. to have gravity upward vector in +Z you have to apply
    rotation to your <tt>Viewpoint</tt> node.

    <p>So I decided to create new fields for <tt>PerspectiveCamera</tt>,
    <tt>OrthographicCamera</tt> and <tt>Viewpoint</tt>
    nodes to allow alternative way to specify
    an orientation:
    <?php echo node_begin("PerspectiveCamera / OrthographicCamera / Viewpoint");
      echo
      node_dots() .
      node_field('exposedField', "MFVec3f", "direction",  "[]") .
      node_field('exposedField', "MFVec3f", "up", "[]") .
      node_field('exposedField', "SFVec3f", "gravityUp", "0 1 0") .
      node_end();
    ?>

    <p>If at least one vector in <tt>direction</tt> field
    is specified, then this is taken as camera look vector.
    Analogous, if at least one vector in <tt>up</tt> field
    is specified, then this is taken as camera up vector.
    This means that if you specify some vectors for
    <tt>direction</tt> and <tt>up</tt> then the value of
    <tt>orientation</tt> field is ignored.
    <tt>direction</tt> and <tt>up</tt> fields should have
    either none or exactly one element.

    <p>As usual, <tt>direction</tt> and <tt>up</tt> vectors
    can't be parallel and can't be zero.
    They don't have to be orthogonal &mdash; <tt>up</tt> vector will be
    always silently corrected to be orthogonal to <tt>direction</tt>.
    Lengths of these vectors are always ignored.
    <!--
    (m.in. dlatego �e w standardowym VRMLu nie mo�na przy
    pomocy <tt>orientation</tt> ustala� d�ugo�ci tych wektor�w, ale tak�e dlatego
    �e tak jest wygodniej, zazwyczaj by�oby to raczej uci��liwe ni�
    funkcjonalne gdyby w jaki� spos�b robi� co� inaczej w zale�nosci od
    d�ugo�ci tych wektorow; tak�e dlatego �e jest w VRMLowej kamerze
    pole <tt>focalDistance</tt> slu��ce w�asnie do robienia rzeczy kt�re
    m�glby� chcie� zrobi� na podstawie dlugo�ci wektora <tt>direction</tt>).
    -->

    <p>As for gravity: VRML 2.0 spec says to take standard +Y vector
    and transform it by whatever transformation was applied to
    <tt>Viewpoint</tt> node. So we modify this to say
    <i>take <tt>gravityUp</tt> vector
    and transform it by whatever transformation was applied to
    <tt>Viewpoint</tt> node</i>. Since the default value for
    <tt>gravityUp</tt> vector is just +Y, so things work 100% conforming
    to VRML spec if you don't specify <tt>gravityUp</tt> field.

    <p>In <?php echo a_href_page("view3dscene", "view3dscene") ?>
    "<i>Print current camera node</i>" command (key shortcut Ctrl+C)
    writes camera node in both versions &mdash; one that uses
    <tt>orientation</tt> field and transformations to get gravity upward vector,
    and one that uses <tt>direction</tt> and <tt>up</tt> and <tt>gravityUp</tt>
    fields.

     <!-- funkcje VRMLFields.CamDirUp2Orient i VectorMath.RotatePointAroundAxis -->

<?php echo $toc->html_section(); ?>

    You can mark surfaces as being mirrors by using this field.

    <?php echo
      node_begin("Material") .
      node_dots() .
      node_field('exposedField', "MFFloat / SFFloat", "mirror", "0.0", "[0.0; 1.0]") .
      node_end();
    ?>

    <p>Currently this is respected only by classic ray-tracer
    in <?php echo a_href_page("view3dscene", "view3dscene"); ?>
    and <?php echo a_href_page("rayhunter", "rayhunter"); ?>.
    Well, it's also respected by path-tracer, although
    it's much better to use <a href="#ext_material_phong_brdf_fields">
    fields describing physical properties (Phong's BRDF) for <tt>Material</tt>
    node</a> when using path-tracer. In the future <tt>mirror</tt> field
    may be somehow respected with normal OpenGL rendering in
    <?php echo a_href_page("view3dscene", "view3dscene"); ?> and others.

    <dl class="vrml_ver_differences">
      <dt>For VRML 1.0</dt>
      <dd>This field is of <tt>multi-</tt> type
        (<tt>MFFloat</tt>), just like other <tt>Material</tt>
        fields in VRML 1.0; this way you can specify many material kinds for one
        shape node (like <tt>IndexedFaceSet</tt>).</dd>
      <dt>For VRML 2.0</dt>
      <dd>This field is of simple <tt>SFFloat</tt> type,
        just like other <tt>Material</tt> fields in VRML 2.0.</dd>
    </dl>

    <p>0.0 means no mirror (i.e. normal surface), 1.0 means the
    perfect mirror (i.e. only reflected color matters).
    Values between 0.0 and 1.0 mean that surface's color is partially
    taken from reflected color, partially from surface's own
    material color.

    <p>Note that this field can be (ab)used to specify completely
    unrealistic materials. That's because it's not correlated in any
    way with <tt>shininess</tt> and <tt>specularColor</tt> fields.
    In the Real World the shininess of material is obviously closely
    correlated with the ability to reflect environment
    (after all, almost all shiny materials are also mirrors,
    unless they have some weird curvature; both shininess and mirroring
    work by reflecting light rays). However, in classic ray-tracer
    these things are calculated in different places and differently
    affect the resulting look (<tt>shininess</tt> and
    <tt>specularColor</tt> calculate local effect of the lights,
    and <tt>mirror</tt> calculates how to mix with the reflected color).
    So the actual "shiny" or "matte" property of material is affected
    by <tt>shininess</tt> and <tt>specularColor</tt> fields as well as
    by <tt>mirror</tt> field.

<?php echo $toc->html_section(); ?>

    If this node is present and headlight is turned on (e.g. because
    <tt>headlight</tt> field of <tt>NavigationInfo</tt> is <tt>TRUE</tt>)
    then this configures the headlight properties.

    <p>The default values
    of this node are compatible with VRML specification, that explicitly
    states that <tt>NavigationInfo.headlight</tt> should have
    <i>intensity = 1, color = (1 1 1),
    ambientIntensity = 0.0, and direction = (0 0 -1)</i>.

    <?php
      echo node_begin('KambiHeadLight');
      $node_format_fd_name_pad = 20;
      echo
      node_field('exposedField', 'SFFloat', 'ambientIntensity', '0', '[0.0, 1.0]') .
      node_field('exposedField', 'SFVec3f', 'attenuation'     , '1 0 0', '[0, infinity)') .
      node_field('exposedField', 'SFColor', 'color'           , '1 1 1', '[0, 1]') .
      node_field('exposedField', 'SFFloat', 'intensity'       , '1', '[0, 1]') .
      node_field('exposedField', 'SFBool' , 'spot'            , 'FALSE') .
      node_field('exposedField', 'SFFloat', 'spotDropOffRate' , 0) .
      node_field('exposedField', 'SFFloat', 'spotCutOffAngle' , 0.785398) .
      node_end();
    ?>

    <p>The meaning of these field should be self-explanatory:
    <tt>ambientIntensity</tt>, <tt>attenuation</tt>, <tt>color</tt> and <tt>intensity</tt>
    are the same as for <tt>PointLight</tt> or <tt>DirectionalLight</tt>
    in VRML 2.0. If <tt>spot</tt> is <tt>TRUE</tt> then the light
    makes a spot, meaning of <tt>spotDropOffRate</tt> and
    <tt>spotCutOffAngle</tt> is the same as in VRML 1.0
    (I didn't use <tt>beamWidth</tt> from VRML 2.0 spec because it
    translates badly to OpenGL).

<?php echo $toc->html_section(); ?>

    To all VRML light nodes, we add two fields:

    <?php
      echo node_begin('XxxLight');
      $node_format_fd_name_pad = 20;
      echo
      node_dots() .
      node_field('exposedField', 'SFBool', 'kambiShadows' , 'FALSE') .
      node_field('exposedField', 'SFBool', 'kambiShadowsMain' , 'FALSE',
        'meaningfull only when kambiShadows = TRUE') .
      node_end();
    ?>

    These extensions specify the shadows behavior.
    They are parsed and handled generally in our
    <?php echo a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine'); ?>.
    But they actually <b>do something</b> only when we render with shadows &mdash;
    which means, for now, that these extensions are usefull for you
    only if you design levels for
    <?php echo a_href_page('"The Castle"', 'castle'); ?>.

    <p>The idea is that shadows are actually projected from only one light source
    (with shadow volumes, number of light sources is limited,
    since more light sources mean more rendering passes; for now, I decided
    to use only one light). The scene lights are divided into three groups:
    <ol>
      <li><p>First of all, there's one and exactly one light
        that makes shadows. Which means that shadows are made
        where this light doesn't reach. This should usually be the
        dominant, most intensive light on the scene.

        <p>This is taken as the first light node with
        <tt>kambiShadowsMain</tt> and <tt>kambiShadows</tt> = <tt>TRUE</tt>.
        Usually you will set <tt>kambiShadowsMain</tt> to <tt>TRUE</tt>
        on only one light node.</li>

      <li><p>There are other lights that don't determine <b>where</b>
        shadows are, but they are turned off where shadows are.
        This seems like a nonsense from "realistic" point of view
        &mdash; we turn off the lights,
        even though they may reach given scene point ?
        But, in practice, it's often needed to put many lights
        in this group. Otherwise, the scene could be so light,
        that shadows do not look "dark enough".

        <p>All lights with <tt>kambiShadows</tt> = <tt>TRUE</tt> are
        in this group. (As you see, the main light has to have
        <tt>kambiShadows</tt> = <tt>TRUE</tt> also, so the main light
        is always turned off where the shadow is).</li>

      <li>Other lights that light everything. These just
        work like usual VRML lights, they shine everywhere
        (actually, according to VRML light scope rules).
        Usually only the dark lights should be in this group.

        <p>These are lights with <tt>kambiShadows</tt> = <tt>FALSE</tt>
        (default).</li>
    </ol>

    <p>Usually you have to experiment a little to make the shadows look
    good. This involves determining which light should be the main light
    (<tt>kambiShadowsMain</tt> = <tt>kambiShadows</tt> = <tt>TRUE</tt>),
    and which lights should be just turned off inside the shadow
    (only <tt>kambiShadows</tt> = <tt>TRUE</tt>).
    This system tries to be flexible, to allow you to make
    shadows look good &mdash; which usually means "dark, but
    not absolutely unrealistically black".

    <p>In <?php echo a_href_page('"The Castle"', 'castle'); ?>
    you can experiment with this using <i>Edit lights</i> inside
    debug menu.

    <p>If no "main" light is found
    (<tt>kambiShadowsMain</tt> = <tt>kambiShadows</tt> = <tt>TRUE</tt>)
    then shadows are turned off on this level.

    <p><i>Trick:</i> note that you can set the main light
    to have <tt>on</tt> = <tt>FALSE</tt>. This is the way to make "fake light"
    &mdash; this light will determine the shadows position (it will
    be treated as light source when calculating shadow placement),
    but will actually not make the scene lighter (be sure to set
    for some other lights <tt>kambiShadows</tt> = <tt>TRUE</tt> then).
    This is a useful trick when there is no comfortable main light on the scene,
    so you want to add it, but you don't want to make the scene
    actually brighter.

<?php echo $toc->html_section(); ?>

<?php echo $toc->html_section(); ?>

    This way every possible value is allowed for <tt>parts</tt>
    field. This is comfortable for operating on these nodes,
    especially from programs &mdash; there is no special "forbidden" value.

<?php echo $toc->html_section(); ?>

    Lights that have a position, i.e. <tt>PointLight</tt> and <tt>SpotLight</tt>
    nodes, have the field <tt>attenuation</tt>. The meaning of this
    field is <a href="http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/nodesRef.html#PointLight">
    exactly the same as in VRML 97</a>.
    I allow this for VRML 1.0 because this is really useful,
    and because the default value of this field (1,0,0)
    assures that standard VRML 1.0 files are interpreted correctly.

    <p>Moreover, all lights have <tt>ambientIntensity</tt> field,
    also defined exactly like in VRML 97. However, when reading VRML 1.0
    files, we treat default value of <tt>ambientIntensity</tt>
    as -1 (while VRML 97 specification gives 0). And when rendering,
    we treat lights with <tt>ambientIntensity &lt; 0</tt> specially:
    we treat them like <tt>ambientIntensity</tt> = <tt>intensity</tt>.
    This way:
    <ol>
      <li>in VRML 1.0 when you specified <tt>ambientIntensity</tt>
        value, or in VRML 97: <tt>ambientIntensity</tt> is treated
        following VRML 97 specification. So rendered
        light ambient color is <tt>color</tt> * <tt>ambientIntensity</tt>.
      <li>in VRML 1.0 when you didn't specify <tt>ambientIntensity</tt>:
        calculations are compatible with standard VRML 1.0 behavior
        (although it was not really stated clearly in VRML 1.0 spec...).
        So rendered light ambient color is
        <tt>color</tt> * <tt>intensity</tt>.
    </ol>

<?php echo $toc->html_section(); ?>

    Some Inventor-specific things are allowed:
    <ul>
      <li><tt>ShapeHints</tt> node has <tt>hints</tt> field of type
        SFBitMask, allowed values are combinations of <tt>NONE</tt>,
        <tt>SOLID</tt>, <tt>ORDERED</tt> and <tt>CONVEX</tt>.
        This is allowed only if the file started with Inventor 1.0 signature
        (<tt>#Inventor V1.0 ascii</tt>).
      <li><tt>IndexedTriangleMesh</tt>, <tt>RotationXYZ</tt> nodes
        are allowed and understood
      <li>Some other fields from Inventor are allowed, but are actually ignored
    </ul>

    <p>These things allow me to handle many Inventor 1.0 files.
    They also allow me to handle many broken VRML 1.0
    files that sometimes falsely claim that they are VRML 1.0 while in
    fact they use some Inventor-specific features.

    <p>For completely unrecognized nodes, our engine can always omit them
    (even without any VRML &gt;= 2.0 (protos) or VRML 1.0 ("fields", "isA")
    extensibility features), so most Inventor files can be at least
    partially handled and displayed.

<?php echo $toc->html_section(); ?>

    VRML 1.0 file may have any number of root nodes
    (VRML 1.0 spec requires that there is exactly one root node).
    I implemented this because
    <ol>
      <li>There are many invalid VRML 1.0 files on the Internet
        that use this extension (partially because it's
        normal VRML 97 feature, and many VRML viewers allow this)
      <li>This is allowed in VRML 97.
      <li>This was very easy to implement :)
    </ol>

<?php echo $toc->html_section(); ?>

    In <?php echo a_href_page("rayhunter's","rayhunter") ?>
    <i>path-tracer</i> I implemented Phong's BRDF.
    To flexibly operate on material's properties understood
    by Phong's BRDF you can use the following <tt>Material</tt> node's
    fields:

    <?php echo node_begin("Material");
      $node_format_fd_name_pad = 20;
      $node_format_fd_def_pad = 10;

      echo
      node_dots() .
      node_field('exposedField', "MFColor", "reflSpecular", "[]", "specular reflectance") .
      node_field('exposedField', "MFColor", "reflDiffuse", "[]", "diffuse reflectance") .
      node_field('exposedField', "MFColor", "transSpecular", "[]", "specular transmittance") .
      node_field('exposedField', "MFColor", "transDiffuse", "[]", "diffuse transmittance") .
      node_field('exposedField', "MFFloat", "reflSpecularExp", "1000000", "specular reflectance exponent") .
      node_field('exposedField', "MFFloat", "transSpecularExp", "1000000", "specular transmittance exponent") .
      node_end();
    ?>

    <p>Short informal description how these properties work
    (for precise description see Phong's BRDF equations or source
    code of my programs):
    <dl>
      <dt>reflectance</dt>
      <dd>tells how the light rays reflect from the surface.</dd>

      <dt>transmittance</dt>
      <dd>tells how the light rays transmit into the surface
        (e.g. inside the water or thick glass).</dd>

      <dt>diffuse</dt>
      <dd>describe the property independent of light rays
        incoming direction.</dd>

      <dt>specular</dt>
      <dd>describe the property with respect to the
        light rays incoming direction (actually, it's the angle
        between incoming direction and the vector of
        perfectly reflected/transmitted ray that matters).</dd>

      <dt>specular exponent</dt>
      <dd>describe the exponent
        for cosinus function used in equation, they say how much
        the specular color should be focused around
        perfectly reflected/transmitted ray.</dd>
    </dl>

    <p>All these fields have <tt>multi-</tt> type (like other
    fields of <tt>Material</tt> node) to allow you to specify
    many material kinds at once.

    <p>Two <tt>*SpecularExp</tt> fields have default values
    equal to 1 000 000 = 1 million = practically infinity
    (bear in mind that they are exponents for cosinus).
    Other four fields have very special default values.
    Formally, they are equal to zero-length arrays.
    If they are left as being zero-length arrays,
    they will be calculated as follows :

    <ul>
      <li><b>reflSpecular<sub>i</sub></b> := vector &lt;mirror<sub>i</sub>, mirror<sub>i</sub>, mirror<sub>i</sub>&gt;
      <li><b>reflDiffuse<sub>i</sub></b> := diffuseColor<sub>i</sub>
      <li><b>transSpecular<sub>i</sub></b> := vector &lt;transparency<sub>i</sub>, transparency<sub>i</sub>, transparency<sub>i</sub>&gt;
      <li><b>transDiffuse<sub>i</sub></b> := diffuseColor<sub>i</sub> * transparency<sub>i</sub>
    </ul>

    <p>This way you don't have to use any of described here 6 fields.
    You can use only standard VRML fields (and maybe <tt>mirror</tt> field)
    and <i>path tracer</i> will use sensible values derived from
    other <tt>Material</tt> fields.
    If you will specify all 6 fields described here,
    then <i>path tracer</i> will completely ignore other
    <tt>Material</tt> fields.

    <p>You can use <?php echo a_href_page("kambi_mgf2inv", "kambi_mgf2inv"); ?>
    program to convert MGF files to VRML 1.0 with these six additional
    <tt>Material</tt> fields. So you can easily test my ray-tracer
    using your MGF files.

    <p>These fields are used only by <i>path tracer</i> in
    <?php echo a_href_page("rayhunter", "rayhunter") ?> and
    <?php echo a_href_page("view3dscene", "view3dscene") ?>.

<?php echo $toc->html_section(); ?>

    I'm adding new field:
    <?php echo node_begin("WWWInline");
      echo
      node_dots() .
      node_field('exposedField', "SFBool", "separate",  "TRUE") .
      node_end();
    ?>

    <p>To explain this field, let's create an example.
    Assume you have file <tt>1.wrl</tt> with following contents:

<pre class="vrml_code">
#VRML V1.0 ascii
Material { diffuseColor 1 0 0 }
</pre>

    And a file <tt>2.wrl</tt> with following contents:

<pre class="vrml_code">
#VRML V1.0 ascii
Group {
  WWWInline { name "1.wrl" }
  Cube { }
}
</pre>

    <p>Question: what material is used by the cube ? The red material
    (defined in <tt>1.wrl</tt>) or the default material ?
    In other words, do the state changes inside <tt>1.wrl</tt>
    "leak outside" of WWWInline node ?

    <p>The answer (stated by VRML specification, and followed by our
    programs when <tt>separate</tt> is TRUE (the default)) is that
    the cube uses the default material. <i>Not</i> the red material.
    In other words, state changes do not "leak" outside.
    This is definitely a sensible behavior. This is safer
    for the author of VRML files (you're not so "vulnerable" to changes
    done in included files). And it allows to delay
    loading of inlined file until it's really
    needed (e.g. is potentially visible). Effectively, this means
    that <tt>WWWInline</tt> behaves a little like a <tt>Separator</tt>
    node. File <tt>2.wrl</tt> is equivalent to

<pre class="vrml_code">
#VRML V1.0 ascii
Group {
  Separator {
    Material { diffuseColor 1 0 0 }
  }
  Cube { }
}
</pre>

    <p>On the other hand, when you set field <tt>separate</tt> to FALSE,
    the cube will be red. Every state change done inside inlined file
    will affect the things defined after <tt>WWWInline</tt> node.
    Effectively, this means that <tt>WWWInline</tt> behaves a little like a
    <tt>Group</tt> node. Two files below are equivalent:

<pre class="vrml_code">
#VRML V1.0 ascii
Group {
  WWWInline { name "1.wrl" separare FALSE }
  Cube { }
}
</pre>

<pre class="vrml_code">
#VRML V1.0 ascii
Group {
  Group {
    Material { diffuseColor 1 0 0 }
  }
  Cube { }
}
</pre>

    <p>Generally, setting field <tt>separate</tt> to FALSE
    is a little dangerous (because you have to be careful what
    you include), but it also allows you to do various tricks.

    <p>Test VRML file:
    see <?php echo a_href_page('VRML test suite',
    'kambi_vrml_test_suite'); ?>, file
    <tt>vrml_1/kambi_extensions/inline_not_separate.wrl</tt>.

<?php
  if (!IS_GEN_LOCAL) {
    php_counter("kambi_vrml_extensions", TRUE);
  };

  common_footer();
?>
