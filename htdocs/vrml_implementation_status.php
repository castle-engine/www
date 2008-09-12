<?php
  require_once 'vrmlengine_functions.php';

  common_header("VRML / X3D implementation status", LANG_EN,
    NULL, NULL,
    '<style type="text/css"><!--
    td.pass{background-color:rgb(50%,100%,50%)}
    td.fail{background-color:rgb(100%,50%,50%)}
    td.invalid{background-color:rgb(75%,75%,75%)}
    --></style>
    ');

  $toc = new TableOfContents(
    array(
      new TocItem('X3D status', 'x3d'),
      new TocItem('VRML 2.0 status', 'vrml_2'),
      new TocItem('VRML 1.0 status', 'vrml_1'),
      new TocItem('Tests passed', 'tests_passed'),
    ));
?>

<?php echo pretty_heading($page_title); ?>

<p>This page collects information about implementation status of
VRML constructs, with respect to VRML 1.0, 2.0 (aka VRML 97) and 3.0 (aka X3D)
specifications.
It also collects some details about handling of some nodes.
If you want to know what is supported <i>besides
the things required by VRML specifications</i>,
you should read the other page about
<?php echo a_href_page('VRML extensions implemented',
'kambi_vrml_extensions'); ?>.

<p><i>No limits</i>:
<a href="http://web3d.org/x3d/specifications/vrml/ISO-IEC-14772-VRML97/part1/conformance.html#7.3.3">
VRML 97 and X3D specifications define various limits</a>
that must be satisfied by VRML browsers to call themselves "conforming"
to VRML specification. For example, only 500 children per Group
have to be supported, only SFString with 30,000 characters have to be
supported etc. My units generally don't have these limits
(unless explicitly mentioned below). So any number of children in Group
node is supported, SFString may be of any length etc.
VRML authors are limited only by the amount of memory available
on user system, performance of OpenGL implementation etc.

<p>Contents:
<?php echo $toc->html_toc(); ?>

<?php echo $toc->html_section(); ?>

<p><i>All nodes from all components</i> of X3D edition 2 specification are
included in the engine. This doesn't mean that they are meaningfully
handled, but they <i>are at least parsed correctly</i> (and converting from
X3D XML to classic VRML preserves them correctly).

<p><i>All field types</i>, including new X3D double-precision and
matrixes, are supported, with the exception of MFImage. MFImage should
be implemented as soon as I see some usage of this, for now no X3D
specification nodes actually use this.

<p>We support fully both <i>XML and classic encodings</i>.

<p>Besides all VRML 97 features, X3D bits implemented now are:</p>

<ul>
  <li>
    <table align="right" class="table_with_movie_thumbnail table_with_thumbs_and_text">
      <tr><td>
        <?php echo medium_image_progs_demo_core("glsl_teapot_demo.png", 'Teapot VRML model rendered with toon shading in GLSL'); ?>
      </td></tr>
      <tr><td>
        This movie shows GLSL shaders by our engine. You can also
        <?php echo current_www_a_href_size('get AVI version with much better quality',
          'movies/2.avi'); ?>.
        <?php if (!HTML_VALIDATION) { ?>
        <object class="youtube_thumbnail_video"><param name="movie" value="http://www.youtube.com/v/ag-d-JGvHfQ&hl=en"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/ag-d-JGvHfQ&hl=en" type="application/x-shockwave-flash" wmode="transparent" width="200" height="167"></embed></object>
        <?php } ?>
      </td></tr>
    </table>

    <p><a name="shaders"></a><a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-X3DAbstractSpecification_Revision1_to_Part1/Part01/components/shaders.html"><b>Programmable
    shaders component</b></a>
    is basically implemented: <tt>ComposedShader</tt> and <tt>ShaderPart</tt> nodes
    allow you to write shaders in GLSL language.

    <p>For example add inside <tt>Appearance</tt> node VRML code like</p>

<pre class="vrml_code">
  shaders ComposedShader {
    language "GLSL"
    parts [
      ShaderPart { type "VERTEX" url "glsl_phong_shading.vs" }
      ShaderPart { type "FRAGMENT" url "glsl_phong_shading.fs" }
    ]
  }
</pre>

    <p>See <?php echo a_href_page("Kambi VRML test suite",
    "kambi_vrml_test_suite"); ?>, directory <tt>x3d/shaders/</tt>
    for working demos of this.</p>

    <p>You can also set uniform variables for your shaders from VRML,
    just add lines like

<pre class="vrml_code">
  inputOutput SFVec3f UniformVariableName 1 0 0
</pre>

    to your ComposedShader node. These uniforms may also be modified by
    VRML events (when they are <tt>inputOutput</tt> or <tt>inputOnly</tt>),
    for example here's a simple way to pass current VRML time (in seconds)
    to your shader:

<pre class="vrml_code">
# ......
# somewhere within Appearance:
  shaders DEF MyShader ComposedShader {
    language "GLSL"
    parts [
      ShaderPart { type "VERTEX" url "my_shader.vs" }
      ShaderPart { type "FRAGMENT" url "my_shader.fs" }
    ]
    inputOnly SFTime time
  }

# ......
# somewhere within grouping node (e.g. at top-level of VRML file)
DEF MyTimer TimeSensor { loop TRUE }
ROUTE MyTimer.time TO MyShader.time
</pre>

    <p>(TODO: unfortunately uniforms don't
    work yet with all required VRML field types. In particular,
    passing textures to shader this way (by SFNode fields) is not
    supported <i>yet</i>.)

    <p>TODO: attributes for shaders in VRML are not yet passed.
    They are implemented in the engine classes of course, it's only a matter
    of implementing link between VRML and them.
    <!-- Also <tt>Cg</tt> handling is quite possible in the future. -->
    If you have some interesting VRML / X3D models that use these programmable
    shaders features, feel free to contact me and I'll implement them
    in our engine.</p>

    <p>(I mean, I will implement them anyway some day, but it's always
    much more interesting to implement features when you actually have
    a real use for them... In other words, I'm just dying to see some
    beautiful VRML/X3D models that heavily use programmable shaders :).</p>

    <p>TODO: <tt>activate</tt> event doesn't work to relink the GLSL
    program now.
    (<tt>isSelected</tt> and <tt>isValid</tt> work perfectly for any
    X3DShaderNode.)
  </li>

  <li><p><tt>StaticGroup</tt>

    <p>(Although it doesn't make any extra optimization.
    This is not needed for our engine, as we <i>initially</i> treat pretty
    much everything as if it would be inside <tt>StaticGroup</tt>,
    that is we assume that model will be mostly static. Although it greatly
    depends on <i>renderer optimization</i> chosen.)</p>

  <li><p><tt>OrthoViewpoint</tt>

    <p>TODO: Although it's handled, some fields are ignored for now:
    jump, retainUserOffsets, centerOfRotation, fieldOfView.

  <li><p>New X3D rendering primitives implemented:

    <p><tt>IndexedTriangleSet</tt>, <tt>TriangleSet</tt>,
    <tt>IndexedQuadSet</tt>, <tt>QuadSet</tt>

    <p><tt>IndexedTriangleFanSet</tt>, <tt>TriangleFanSet</tt>,
    <tt>IndexedTriangleStripSet</tt>, <tt>TriangleStripSet</tt>

    <p><tt>LineSet</tt> (<tt>IndexedLineSet</tt> is also handled,
    this is part of VRML 2.0)

    <p><i>TODO</i>: missing is only the implementation of new X3D fields
    <tt>attrib</tt> and <tt>fogCoord</tt>.

    <p><i>TODO</i>: for <tt>TriangleFanSet</tt> and <tt>TriangleStripSet</tt>,
    a special constraint is present: if you will use colors
    (colors are always per-vertex on these primitives,
    according to X3D spec) and request generation of per-face normals
    at the same time, for the same lit (with material) node,
    then shading results will be slightly incorrect.
    Like this:

<pre>
#X3D V3.0 utf8
PROFILE Interchange

Shape {
  appearance Appearance { material Material { } }
  geometry TriangleFanSet {
    coord Coordinate { point [ 0 0 0, 1 0 0, 1 1 0, 0.5 1.5 0.5 ] }
    fanCount 4
    color Color { color [ 1 0 0, 0 1 0, 0 0 1, 1 1 1 ] }
    normalPerVertex FALSE
  }
}
</pre>

    <p>Unfortunately, this is quite unfixable without falling back to
    worse rendering methods. Shading has to be smooth to interpolate
    per-vertex colors, and at the same time the same vertex may require
    different normals on a different faces. So to render this correctly one has
    to decompose triangle fans and strips into separate triangles
    (like to <tt>IndexedTriangleSet</tt>) which means that rendering is
    non-optimal.

    <p>Ideas how to implement this without sacrificing rendering time
    are welcome. Eventually, a fallback to internally convert fans and strips
    to <tt>IndexedTriangleSet</tt> in such special case will be
    implemented some day.

    <p><i>Note</i>: As far as I see, X3D specification doesn't specify what to do
    for triangle/quad sets when appearance specify a texture but no
    <tt>texCoord</tt> is given.
    Our engine currently takes the <tt>IndexedFaceSet</tt> approach for
    automatic generation of texture coords in this case, let me know
    if this is wrong for whatever reason.

  <li><p><tt>solid</tt> field added to many simple nodes (like Box, Sphere)
    is handled, allows to you to turn on or off back-face culling for them.

  <li><p><tt>TextureProperties</tt>

    <p><i>TODO</i>: very basic support, only minificationFilter and
    magnificationFilter fields actually handled.

  <li><p><tt>KeySensor</tt>

    <p>The first sensor node actually implemented :)

    <p><i>TODO</i>: keyRelease is not generated (we do not have
    character data about key up). Everything else is Ok, but keyPress
    generates only 8-bit ASCII characters now.

  <li><p>Event utilities: <tt>BooleanFilter</tt>,
    <tt>BooleanToggle</tt>, <tt>BooleanTrigger</tt>,
    <tt>IntegerTrigger</tt>, <tt>TimeTrigger</tt></p>

  <li><p><tt>Rectangle2D</tt>

  <li><p>HAnim nodes:

    <p><tt>Humanoid</tt>, <tt>Joint</tt>, <tt>Segment</tt>,
    <tt>Site</tt>, <tt>Displacer</tt>

    <p><tt>HAnimHumanoid</tt>, <tt>HAnimJoint</tt>,
    <tt>HAnimSegment</tt>, <tt>HAnimSite</tt>, <tt>HAnimDisplacer</tt>
    (X3D version)

    <p>We have the basic HAnim support, which means that we can correctly
    render your human designed with HAnim nodes and efficiently animate it
    through VRML interpolators.

    <p>As you see, X3D version has exactly the same nodes, working the same way,
    but with <tt>HAnim</tt> prefix before node name. (I have no idea why
    this prefix was added in X3D specification, but it's supported.)
    Actually we allow both versions (with <tt>HAnim</tt> prefix and without)
    in all VRML and X3D versions (<?php echo a_href_page_hashlink("with our
    enfgine you can generally mix VRML versions",
    'kambi_vrml_extensions',
    'section_ext_mix_vrml_1_2'); ?>). But VRML authors/generators should
    not overuse this, and try to conform to appropriate spec where possible.

    <p>The implementation takes some care to handle all existing VRML versions:
    <a href="http://h-anim.org/Specifications/H-Anim1.0/">HAnim 1.0</a>,
    <a href="http://h-anim.org/Specifications/H-Anim1.1/">HAnim 1.1</a>,
    <a href="http://h-anim.org/Specifications/H-Anim200x/ISO_IEC_FCD_19774/">HAnim 200x</a>.

    <p>Animating transformations of <tt>Joint</tt> nodes and such
    is optimized, just like for <tt>Transform</tt> node,
    be sure to select <tt>roSeparateShapeStatesNoTransform</tt> method.
</ul>

<?php echo $toc->html_section(); ?>

<p><i>All nodes</i> from VRML 2.0 specification are correctly parsed.
The list below lists nodes that are actually handled, i.e. they do
things that they are supposed to do according to "Node reference"
chapter of VRML spec.

<p><i>TODO</i> for all nodes with url fields: for now all URLs
are interpreted as local file names (absolute or relative).
So if a VRML file is available on WWW, you should first download it
(any WWW browser can of couse download it and automatically open view3dscene
for you), remembering to download also any texture/background files
used.

<p>Nodes listed below are fully (except when noted with
<i>TODO</i>) supported :

<ul>
  <li><tt>DirectionalLight</tt>, <tt>PointLight</tt>, <tt>SpotLight</tt>

    <p><i>Note</i>: VRML 2.0 <tt>SpotLight.beamWidth</tt>
    idea cannot be translated to a standard
    OpenGL spotlight, so if you set beamWidth &lt; cutOffAngle then the light
    will not look exactly VRML 2.0-spec compliant.
    Honestly I don't see any sensible way to fix this
    (as long as we talk about real-time rendering using OpenGL).
    And other open-source VRML implementations rendering to OpenGL
    also don't seem to do anything better.

    <p>VRML 2.0 spec requires that at least 8 lights
    are supported. My units can support as many lights as are
    allowed by your OpenGL implementation, which is <i>at least</i> 8.

  <li><p><tt>Background</tt>, <tt>Fog</tt>, <tt>NavigationInfo</tt>,
    <tt>WorldInfo</tt>

    <p><i>Note</i>: <tt>WorldInfo.title</tt>, if set, is displayed by
    view3dscene on window's caption.

  <li><p><tt>Switch</tt>, <tt>Group</tt>, <tt>Transform</tt>

    <p>Including special optimizations for animating transformations,
    be sure to select <tt>roSeparateShapeStatesNoTransform</tt> method.

  <li><p><tt>Sphere</tt>, <tt>Box</tt>, <tt>Cone</tt>, <tt>Cylinder</tt>

  <li><p><tt>Shape</tt>, <tt>Appearance</tt>, <tt>Material</tt>

  <li><p><tt>TextureTransform</tt>, <tt>PixelTexture</tt>,
    <tt>ImageTexture</tt>

    <p><i>Note</i>: ImageTexture allows various texture formats,
    including JPEG, PNG, BMP, PPM, RGBE. GIF format is supported
    by running <tt>convert</tt> program from
    <a href="http://www.imagemagick.org/">ImageMagick</a>
    package "under the hood".
    See <?php echo a_href_page('glViewImage', 'glviewimage'); ?>
    documentation for more detailed list.

    <p><i>Note about alpha channel</i>: alpha channel of the textures
    is fully supported, both a simple yes-no transparency (done
    by alpha_test in OpenGL) and full range transparency
    (done by blending in OpenGL, just like partially transparent materials).
    Internally, we have a simple and very nice algorithm that detects whether texture's
    alpha channel qualifies as simple yes-no or full range, see
    <a href="TODO-reference">TImage.AlphaChannelType reference</a>
    (default tolerance values used by VRML renderer are 5 and 0.01).
    There is also a special program in <?php echo a_href_page('engine sources',
    'kambi_vrml_game_engine'); ?> (see <tt>images/tools/detect_alpha_simple_yes_no.pasprogram</tt>
    file) if you want to use this algorithm yourself.
    You can also see the results for your textures if you run view3dscene
    with <tt>--debug-log</tt> option.

    <p>The bottom line is: everything will magically work fast and look perfect.

  <li><p><tt>MovieTexture</tt>

    <p><i>TODO</i>: for now, the sound of the movie is not played.

    <p><i>Notes</i>:

    <ul>
      <li><p>Current implementation keeps the whole encoded video in memory
        (images may be discarded after loading (by TVRMLScene.FlatResources
        feature), but still the textures for all frames are kept in memory).
        The <i>disadvantage</i> is that this makes it impractical to load "real"
        movies, normal 2-hour movie will most usually eat all of your memory.
        The <i>advantage</i> is that once the movie is loaded, the playback is
        super-fast, just like you would display normal nodes with static
        textures. Since there's no streaming, decoding etc. in the background
        while you browse your models.

        <p>In other words, this is quite perfect for movie textures
        with game effects, like smoke or flame. But it's not a substitute
        for your "real" multimedia movie player.

      <li><p><a href="http://ffmpeg.mplayerhq.hu/">ffmpeg</a> must be
        installed and available on $PATH to actually open any movie format.
        See <?php echo a_href_page_hashlink('instructions for
        installing ffmpeg in view3dscene docs', 'view3dscene', 'section_depends'); ?>.
        Thanks to ffmpeg, we can handle probably any movie format you will
        ever need to open.

      <li><p>We can also open movies from images sequence.
        This doesn't require ffmpeg, and allows for some tricks
        (movie texture with alpha channel).
        See <?php echo a_href_page_hashlink('"Movies from images sequence"
        extension description', 'kambi_vrml_extensions',
        'section_ext_movie_from_image_sequence'); ?>.
    </ul>

  <li><p><tt>Inline</tt>, <tt>InlineLoadControl</tt>

    <p>Yes, this includes handling of <tt>InlineLoadControl</tt> features
    to react to <tt>load</tt>, <tt>url</tt> and generate <tt>children</tt>
    events. For X3D, basic <tt>Inline</tt> node already has
    <tt>load</tt>, <tt>url</tt> features and they also work perfectly.

  <li><p><tt>LOD</tt>

    <p><i>TODO</i>: But we always render first (best looking) node, ignoring
    distance of node to the viewer.

  <li><p><tt>Anchor</tt>

    <p><i>TODO</i>: <tt>parameter</tt> field is ignored, everything else
    is handled (according to X3D spec).

  <li><p><tt>Text</tt>, <tt>FontStyle</tt>

    <p>Most important properties
    (size, spacing, justify, family, style) are handled fully.

    <p><i>TODO</i>: But some properties are ignored for now:
    <ul>
      <li>FontStyle properties: From section
        <i>6.22.3 Direction and justification</i>
        horizontal, leftToRight, topToBottom fields are ignored
        (and things are always handled like they had default values
        TRUE, TRUE, TRUE). From section <i>6.22.4 Language</i>
        language field is ignored.
      <li><tt>Text</tt>: length, maxEntent are
        ignored (and handled like they had
        default values, which means that the text is not stretched).
    </ul>

    <p><tt>Text</tt> is "clickable" within
    <tt>Anchor</tt> and <tt>TouchSensor</tt> nodes.
    Although I didn't find any mention in the specifications that I should
    do this, many VRML models seem to assume this.
    We make an ultra-simple triangulation of the text
    (just taking 2 triangles to cover whole text, you don't want
    to produce real triangles for text node, as text node would have
    a lot of triangles!).<br/>
    <i>TODO</i>: unfortunately, for now these triangles also participate
    in collision detection, while spec says that text shouldn't collide.

  <li><p><tt>Viewpoint</tt></p>

    <p><i>Note</i>: view3dscene displays also nice menu allowing you to jump
    to any defined viewpoint, displaying viewpoints descriptions.
    Extensive tests of various viewpoint properties, includind fieldOfView,
    are inside <?php
      echo a_href_page('my VRML test suite', 'kambi_vrml_test_suite'); ?>
    in <tt>vrml_2/viewpoint_*.wrl</tt> files.</p>

  <li><p><tt>PointSet</tt>, <tt>IndexedLineSet</tt>, <tt>IndexedFaceSet</tt>,
    <tt>Coordinate</tt>, <tt>Color</tt>,
    <tt>Normal</tt>, <tt>TextureCoordinate</tt></p>

  <li><p><tt>Billboard</tt>, <tt>Collision</tt></p>

    <p><i>TODO</i>: These two nodes are not really handled:
    they just work like a <tt>Group</tt>. Sometimes that's enough
    for them to look sensible, but it's hardly a real support...</p>

    <p>Actually, the simplest case of <tt>Collision.collide = FALSE</tt>
    (called <tt>enabled</tt> in X3D) is supported Ok.</p>

  <li><p><tt>ElevationGrid</tt></p>

    <p><i>TODO</i>: when colors are present and <tt>colorPerVertex</tt>
    is different than <tt>normalPerVertex</tt> (from field or calculated
    based on creaseAngle) then shading results may be incorrect.
    Reasons for this &mdash; see comments about X3D <tt>[Indexed]TriangleFan/StripSet</tt>
    above on this page.</p>

    <p><i>TODO</i>: <tt>creaseAngle</tt> is not fully handled:
    we always generate all flat normals (if creaseAngle = 0) or
    all smooth normals (if creaseAngle &lt;&gt; 0).</p>

  <li><p><tt>Extrusion</tt>

    <p>Works fully.</p>

  <li><p><tt>ColorInterpolator</tt>, <tt>PositionInterpolator</tt>,
    <tt>PositionInterpolator2D</tt> (X3D), <tt>ScalarInterpolator</tt>,
    <tt>OrientationInterpolator</tt></p>

    <p><tt>CoordinateInterpolator</tt>,
    <tt>CoordinateInterpolator2D</tt> (X3D), <tt>NormalInterpolator</tt></p>

    <p><i>TODO</i>: Interpolation of ColorInterpolator simply interpolates
    3D vectors, so it interpolates in RGB space (while spec says to interpolate
    in nice HSV space).</p>

    <p>Interpolation of OrientationInterpolator simply
    interpolates 4D vectors, instead of a nice interpolation on the unit sphere.</p>

    <p>Interpolation of NormalInterpolator simply interpolates
    3D vectors (and normalizes afterwards), instead of
    a nice interpolation on the unit sphere.</p>

  <li><p><tt>TimeSensor</tt></p>

    <p>All common <tt>X3DTimeDependentNode</tt> things
    are implemented. <tt>enabled</tt> is honoured. <tt>time</tt> is generated.</p>

    <p><i>TODO:</i> <tt>fraction_changed</tt> simply generates
    elapsedTime / cycleInterval value. This is quite Ok for most uses.</p>

    <p>As for "time origin" in our engine, this follows VRML standard
    (time origin is "January 1, 1970"), but it can be changed
    by <?php echo a_href_page_hashlink(
    'our extension <tt>KambiNavigationInfo.timeOriginAtLoad</tt>',
    'kambi_vrml_extensions',
    'section_ext_time_origin_at_load'); ?>.</p>

  <li><p><tt>TouchSensor</tt>

    <p><i>TODO</i>: <tt>hitTexCoord_changed</tt> is not working,
    and <tt>hitNormal_changed</tt> generates only the flat (per-face) normal.
    Everything else works perfectly, which should be Ok for typical uses.</p>

  <li><p><tt>ProximitySensor</tt></p>

    <p><i>TODO</i>: <tt>orientation_changed</tt> and <tt>centerOfRotation_changed</tt>
    are not generated. Rest works Ok, according to spec. Timestamps
    for isActive, enter/exitTime are not interpolated (they are simply
    timestamps when this was detected), this shouldn't be a problem in
    typical uses.</p>
</ul>

<p>Prototypes (both external and not) are 100% done and working :)
External prototypes recognize URN of standard VRML 97 nodes, i.e.
<tt>urn:web3d:vrml97:node:Xxx</tt> and standard X3D nodes
(<tt>urn:web3d:x3d:node:Xxx</tt>), see also our extensions URN
on <?php echo a_href_page('Kambi VRML extensions', 'kambi_vrml_extensions'); ?>.

<p>Events, routes mechanism is implemented since 2008-08-11 :)

<p><i>TODO</i>: Some general features not implemented yet are listed below.
They all are parsed correctly and consciously (which means that the parser
doesn't simply "omit them to matching parenthesis" or some other dirty
trick like that). But they don't have any effect on the scene. These are:
<ul>
  <li><tt>Script</tt> nodes: no kind of scripting is implemented now.</li>
  <li>NURBS</li>
  <li>Sounds (<tt>AudioClip</tt> and <tt>Sound</tt>). Although our engine
    supports 3D sounds and music (using OpenAL, sound formats
    allowed now are WAV and OggVorbis), this is currently not integrated
    with VRML in any way &mdash; it's only available if you use our engine
    to write your own programs.
  <li>Geospatial component. As an exception, geospatial VRML 97 nodes
    may not even be correctly parsed by our engine. They are parsed
    according to X3D (there were some incompatible changes in X3D).</li>
</ul>

<?php echo $toc->html_section(); ?>

<p>I consider VRML 1.0 status as "almost complete".
All nodes and features are handled, with the exception of:

<ul>
  <li>Handling URLs in fields <tt>WWWInline.name</tt> and
    <tt>Texture2.filename</tt>. As for now, only local file names are
    allowed there.
    <!-- Relative paths are resolved with respect to the path of originating
         VRML file. -->

  <li><tt>AsciiText.width</tt> is ignored.

  <li>Value of <tt>height</tt> / <tt>heightAngle</tt> fields of camera
    nodes are ignored.

  <li>I'm always rendering the nearest (first) child of <tt>LOD</tt> node.
    Therefore I'm potentially losing some optimization if the scene
    has reasonably designed <tt>LOD</tt> nodes.
</ul>

<p><b>VRML 1.0 features that will probably never be implemented,
as they are replaced with much better mechanisms in newer VRML versions:</b>

<ul>
  <li><p><tt>AsciiText</tt> node's triangles and vertices are not counted
    when writing triangles and vertices counts of the scene.
    This is actually somewhat Ok, as later VRML specs say explicitly that
    Text nodes do not participate in collision detection
    (so they do not have triangles/vertices for collision detection,
    only for rendering).

  <li><p>Clicking on <tt>WWWAnchor</tt> doesn't work (use VRML &gt;= 2.0
    <tt>Anchor</tt> instead, implementing old VRML 1.0 anchor is not worth
    the trouble and would unnecessarily obfuscate the code).

  <li><p>Camera <tt>focalDistance</tt> is also ignored, but this
    is allowed by specification. And honestly VRML 1.0 specification
    is so ambiguous about this feature (<i>browser should adjust
    flying speed to reach that point in a reasonable amount of time</i>,
    <i>perhaps the browser can use this as a hint</i>...) that
    I see no reliable way to handle <tt>focalDistance</tt>.

    <p>Fortunately, VRML 2.0 replaced this with <tt>NavigationInfo.speed</tt>
    feature, with clear meaning (basically, it's just a distance per second),
    so please use this instead. (For my engine, you can use
    <tt>NavigationInfo</tt> node even in VRML 1.0 models.)

  <li><p>Extensibility features (<tt>isA</tt> and <tt>fields</tt>) are not handled
    fully, although you probably will not notice. For built-in nodes,
    <tt>isA</tt> and <tt>fields</tt> are correctly parsed but ignored.
    For unknown nodes, they are simply omitted up to the matching
    closing parenthesis.

    <p>This means that the only case when you will notice something doesn't
    work is when you use non-standard VRML node but point to a standard
    node with <tt>isA</tt> declaration. Then my engine will ignore
    <tt>isA</tt> declaration, while it should use it to interpret your node
    and (at least partially, when possible) handle it.</p>

    <p>Finishing of handling this VRML 1.0 feature has rather low priority,
    since this mechanism was completely dropped in later VRML versions.
    VRML 2.0 and X3D replaced this by fantastic prototypes mechanism,
    which is basically an extra-powerful and elegant way of doing what
    VRML 1.0 tried to do with <tt>isA</tt> and <tt>fields</tt> feature
    (and VRML prototypes are already handled 100% by our engine).

  <li><p>MFString field with strings not enclosed in double quotes will
    not be parsed corectly. Moreover, parsing SFStrings not enclosed
    in double quotes is implemented rather as a "quick &amp; dirty hack"
    than as a nice solution. Really, it's a weird "feature" of
    VRML 1.0 (fortunately eliminated in VRML 97) to allow strings not enclosed
    in double quotes.
    And I know about only <b>one</b> program that utilizes it (Blender)
    and this program uses it only in SFString field (Texture2.filename).
    So I doubt I will ever fix this to MFString &mdash;
    I would consider it a waste of time, since it's really
    a VRML-1.0-specific totally useless and uncommon syntax feature.
</ul>

<p>Note that some unclear parts of VRML 1.0 specification are handled according
to VRML 97 specification. Also, our ray-tracer uses lighting model
defined for VRML 97 (since VRML 1.0 didn't define any lighting model
precisely).

<?php echo $toc->html_section(); ?>

<!-- Besides the collections mentioned below I also tested
on numerous VRML models available on the WWW. -->

<ul>
  <li><p>Tested on <?php
    echo a_href_page('our Kambi VRML test suite', 'kambi_vrml_test_suite'); ?>.

  <li><p>Examples from VRML 2.0 specification and
    <a href="http://accad.osu.edu/~pgerstma/class/vnv/resources/info/AnnotatedVrmlRef/Book.html">
    The Annotated VRML 97 Reference</a> were tested.

  <li><p>Files generated by <a href="http://www.blender3d.org/">Blender</a>
    VRML 2.0 exporter
    are handled. I think that I support fully everything that can be
    generated by this exporter.

  <li><p>From <a href="http://www.itl.nist.gov/div897/ctg/vrml/chaco/chaco.html">
    Chaco's VRML Test Suite</a> passes every file
    (that exists on server &mdash; some are missing, although I fixed missing
    texture for tests) besides <tt>recurse.wrl</tt> &mdash; it's
    an incorrect file, we should produce better error message for it.

  <li><p><a href="http://xsun.sdct.itl.nist.gov/~mkass/vts/html/vrml.html">
    NIST VRML Test Suite</a> results are below.</p>

    <p>Each test was classified as "pass" only if it passed fully.
    Which is a good objective measure,
    but also means that many tests failed
    because unrelated features are not implemented. For example,
    don't be discouraged by many failures in <tt>PROTO</tt> category.
    Prototypes were 100% working in all tests, and I consider their
    implementation as practically finished.
    But unrelated things like missing <tt>Script</tt> support
    prevented the tests in <tt>PROTO</tt> category from passing completely.</p>

<?php
function pass($count, $comment = '')
{
  global $current_test_number;
  for ($i = 0; $i < $count; $i ++)
  {
    echo '
    <tr>
      <td>' . $current_test_number . '</td>
      <td class="pass">+</td>';
    if ($comment != '')
    {
      echo '<td rowspan="' . $count . '">' . $comment . '</td>';
      $comment = '';
    }
    echo '</tr>';
    $current_test_number++;
  }
}

function fail($count, $comment)
{
  global $current_test_number;
  for ($i = 0; $i < $count; $i ++)
  {
    echo '
    <tr>
      <td>' . $current_test_number . '</td>
      <td class="fail">-</td>';
    if ($comment != '')
    {
      echo '<td rowspan="' . $count . '">' . $comment . '</td>';
      $comment = '';
    }
    echo '</tr>';
    $current_test_number++;
  }
}
?>

    <table border="1">
      <tr>
        <th>Node Group</th>
        <th>Node</th>
        <th>Test Number</th>
        <th>Result</th>
        <th>Notes</th>
      </tr>
      <tr>
        <td rowspan="101">Appearance</td>
        <td rowspan="12">Appearance</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>10</td>
        <td class="fail">-</td>
        <td><tt>IndexedFaceSet</tt> and <tt>ElevationGrid</tt>
          errorneously modulate texture color by specified color.
      </tr>
      <tr>
        <td>11</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>12</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td rowspan="7">FontStyle</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
        <td>Note that the test looks strange because the X axis line
          starts at X = -200. This is an error in the test file.
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="fail">-</td>
        <td>For horizonal text test passes, but vertical text
          is not implemented yet.
      </tr>
      <tr>
        <td>7</td>
        <td class="fail">-</td>
        <td>Handling Script not implemented yet.
      </tr>
      <tr>
        <td rowspan="34">ImageTexture</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>10</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>11</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>12</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>13</td>
        <td class="fail">-</td>
        <td>The texture top is not aligned precisely with text top.
      </tr>
      <tr>
        <td>14</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>15</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>16</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>17</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>18</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>19</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>20</td>
        <td class="fail">-</td>
        <td>Like case 13: The texture top is not aligned precisely with text top.
      </tr>
      <tr>
        <td>21</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>22</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>23</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>24</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>25</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>26</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>27</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>28</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>29</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>30</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>31</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>32</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>33</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>34</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td rowspan="29">Material</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="fail">-</td>
        <td rowspan="3">For now we errorneously modulate texture color by specified color.
      </tr>
      <tr>
        <td>8</td>
        <td class="fail">-</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="fail">-</td>
      </tr>
<?php

$current_test_number = 10;
pass(20);

?>

      <tr>
        <td rowspan="19">MovieTexture</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>

<?php
$current_test_number = 2;
fail(2, 'Audio from MovieTexture is not played yet');
pass(12);
pass(1, 'The movie text.mpg is still (5 identical frames, according to ffmpeg,
  gstreamer and xine)');
pass(3);
?>

      <tr>
        <td colspan="5"><i>...here I skipped some tests, to be checked later...</i></td>
      </tr>

      <tr>
        <td rowspan="113">Geometry</td>
        <td rowspan="6">Box</td>
        <td>1</td>
        <td class="pass">+</td>
        <td>This links to <tt>Text</tt> test, that passes (but has nothing
          to do with <tt>Box</tt>)
      </tr>

<?php
$current_test_number = 2;
pass(5);
?>

      <tr>
        <td rowspan="8">Cone</td>
        <td>1</td>
        <td class="pass">+</td>
        <td>This links to <tt>Text</tt> test, that passes (but has nothing
          to do with <tt>Cone</tt>)
      </tr>

<?php
$current_test_number = 2;
pass(4, 'Again, tests linking to unrelated testcases for <tt>Box</tt> node (that pass)');
pass(2);
pass(1, 'Unrelated <tt>Box</tt> test... (that passes)');
?>

      <tr>
        <td rowspan="9">Cylinder</td>
        <td>1</td>
        <td class="pass">+</td>
        <td>Unrelated <tt>Text</tt> test again...
      </tr>

<?php
$current_test_number = 2;
pass(4, 'Unrelated tests for <tt>Box</tt> again...');
pass(2);
pass(1, 'Unrelated <tt>Cone</tt> test...');
pass(1, 'Unrelated <tt>Box</tt> test...');
?>

      <tr>
        <td rowspan="14">ElevationGrid</td>
        <td>1</td>
        <td class="pass">+</td>
        <td>Note that by default ElevationGrid is not smoothed (creaseAngle = 0),
          this is following the spec.
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="pass">+</td>
        <td>The reference image of the test is bad. The result should
          be more obvious (whole rows of quads have the same normal),
          and it is &mdash; with our engine.
      </tr>
      <tr>
        <td>10</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>11</td>
        <td class="pass">+</td>
        <td>Although we use two-sided lighting.
      </tr>
      <tr>
        <td>12</td>
        <td class="fail">-</td>
        <td>Although we do generate smooth normals, they are
          not used since colorPerVertex forces us to use smooth shading.
      </tr>
      <tr>
        <td>13</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>14</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td rowspan="17">Extrusion</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
        <td>Reference images show the incorrect non-uniform scaling
          of the caps. We handle it right.
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>10</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>11</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>12</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>13</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>14</td>
        <td class="fail">-</td>
        <td>While generally looks Ok, it seems that our triangulating
          algorithm can't handle this particular shape perfectly.
      </tr>
      <tr>
        <td>15</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>16</td>
        <td class="fail">-</td>
        <td>This links to ElevationGrid creaseAngle test, that fails...
          Has nothing to do with Extrusion actually. (And we do
          handle creaseAngle on Extrusion correctly!)
      </tr>
      <tr>
        <td>17</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td rowspan="21">IndexedFaceSet</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>

<?php
$current_test_number = 2;
pass(10);
fail(3, 'Texture is applied Ok, but tests fail because texture is currently always modulated by color (while VRML specifies default mode as decal for RGB textures)');
pass(7);
?>

      <tr>
        <td rowspan="10">IndexedLineSet</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
        <td rowspan="2">(These tests have nothing to do with IndexedLineSet,
          they are for IndexedFaceSet.)</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
        <td rowspan="2">(These tests have nothing to do with IndexedLineSet,
          they are for IndexedFaceSet.)</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>10</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td rowspan="5">PointSet</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>

<?php
$current_test_number = 2;
pass(4);
?>

      <tr>
        <td rowspan="5">Shape</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>

<?php
$current_test_number = 2;
pass(4);
?>

      <tr>
        <td rowspan="6">Sphere</td>
        <td>1</td>
        <td class="pass">+</td>
        <td>Unrelated <tt>Text</tt> tests...
      </tr>

<?php
$current_test_number = 2;
pass(5, 'Unrelated <tt>Box</tt> tests...');
?>

      <tr>
        <td rowspan="12">Text</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>

<?php
$current_test_number = 2;
pass(3);
fail(3, 'Text.length is not supported yet');
fail(2, 'Text.maxExtent is not supported yet');
pass(2);
fail(1, 'Texture mapping is a little incorrect, text is too small');
?>

      <tr>
        <td colspan="5"><i>...here I skipped some tests, to be checked later...</i></td>
      </tr>

      <tr>
        <td rowspan="48">Misc</td>
        <td rowspan="18">EXTERNPROTO</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>8</td>
        <td class="fail">-</td>
        <td>Currently, base URL for EXTERNPROTO is from the file where EXTERNPROTO
          is written, not from the file where it's instantiated.
      </tr>
      <tr>
        <td>9</td>
        <td class="fail">-</td>
        <td rowspan="2">
          Scipts are not supported yet. Also, the DEF declaration inside
          a script causes known problem with cycles in VRML graph.</td>
      </tr>
      <tr>
        <td>10</td>
        <td class="fail">-</td>
      </tr>
      <tr>
        <td>11</td>
        <td class="fail">-</td>
        <td rowspan="2">Scipts are not supported yet.</td>
      </tr>
      <tr>
        <td>12</td>
        <td class="fail">-</td>
      </tr>
      <tr>
        <td>13</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>14</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>15</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>16</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>17</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>18</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td rowspan="30">PROTO</td>
        <td>1</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>2</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>3</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>4</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>5</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>6</td>
        <td class="pass">+</td>
        <td>(It links to unrelated Text test that works?)</td>
      </tr>
      <tr>
        <td>7A</td>
        <td class="pass">+</td>
        <td>Result is Ok, but we do not handle SphereSensor,
          Sound, AudioClip nodes (yet).</td>
      </tr>
      <tr>
        <td>7B</td>
        <td class="fail">-</td>
        <td>Static result seems Ok, but we do not handle VisibilitySensor (yet).
          Also Billboard is crude, although this is not noticeable here.
        </td>
      </tr>
      <tr>
        <td>7C</td>
        <td class="fail">-</td>
        <td>Static result seems Ok, but we do not handle VisibilitySensor
          and Collision.collideTime is not generated (yet).</td>
      </tr>
      <tr>
        <td>7D</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7E</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7F</td>
        <td class="pass">+</td>
        <td>Result is Ok, although actually we do not handle PlaneSensor (yet).</td>
      </tr>
      <tr>
        <td>7G</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7H</td>
        <td class="pass">+</td>
        <td>Result is Ok, although actually we do not handle Script (yet).</td>
      </tr>
      <tr>
        <td>7I</td>
        <td class="pass">-</td>
        <td>But we do not handle LOD (yet), rendering always the first child.
          This is valid (although non-optimal) with respect to spec, AFAIK?
          (But will be improved anyway in the future.)
      </tr>
      <tr>
        <td>7J</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>7K</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td>8</td>
        <td class="fail">-</td>
        <td>Although the tested features work Ok, there is a problem
          (unrelated to protos) with
          activating TouchSensor when SphereSensor is also enabled.
          We should activate them both simultaneously, currently
          only one (SphereSensor in this case, since it's first)
          is activated.</td>
      </tr>
      <tr>
        <td>9</td>
        <td class="fail">-</td>
        <td>Tested features work perfectly. But VisibilitySensor
        is not handled (yet), so animation doesn't start (you can replace
        it by e.g. ProximitySensor with large sizes, and animation will work).
      </tr>
      <tr>
        <td>10</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>11</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>12</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>13</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>14</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>15</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>16</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>17</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>18</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>19</td>
        <td class="pass">+</td>
      </tr>
      <tr>
        <td>20</td>
        <td class="pass">+</td>
      </tr>

      <tr>
        <td colspan="5"><i>That's enough for now...
          I don't have time to check all the tests.
          If someone wants to do the work and do the remaining
          tests (and document results just like above),
          please contact us by
          <?php echo MAILING_LIST_LINK; ?>.</i>
    </table>

    <p>Cases are marked above as "success" (+) only if they succeed
    completely.
    The table above was modelled after similar page
    <a href="http://www.openvrml.org/doc/conformance.html">
    OpenVRML Conformance Test Results</a>. <!-- See there also
    for some  remarks about invalid tests included in
    NIST test suite. -->

</ul>

<?php
  if (!IS_GEN_LOCAL) {
    php_counter("vrml_implementation_status", TRUE);
  };

  common_footer();
?>
