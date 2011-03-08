<?php

array_push($news,
    array('title' => 'Development news: Beautiful shader rendering, compositing shaders extensions, shadow maps, Blender X3D exporter mods, and more',
          'year' => 2011,
          'month' => 3,
          'day' => 8,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'metallic_shiny.png', 'titlealt' => 'Shiny dark metallic material under multiple lights, with per-pixel lighting.'),
  array('filename' => 'castle_light_2.png', 'titlealt' => 'Castle fountain level with shaders, fun with lighting 2'),
  array('filename' => 'castle_light_1.png', 'titlealt' => 'Castle fountain level with shaders, fun with lighting 1'),
  array('filename' => 'castle_light_3.png', 'titlealt' => 'Castle fountain level with shaders, fun with lighting 3'),
  array('filename' => 'volumetric_animated_fog_all.png', 'titlealt' => 'Volumetric fog'),
  array('filename' => 'volumetric_animated_fog_no_fog.png', 'titlealt' => 'Scene for the volumetric fog, here visible with fog turned off'),
  array('filename' => 'volumetric_animated_fog_no_light.png', 'titlealt' => 'Volumetric fog, with normal lighting turned off'),
  array('filename' => 'shadow_map_spot.png', 'titlealt' => 'Spot light casting shadows'),
  array('filename' => 'fancy_light_spot_shape.png', 'titlealt' => 'Textured spot light with shadow'),
  array('filename' => 'flowers.png', 'titlealt' => 'Flowers bending under the wind, transformed on GPU in object space'),
  array('filename' => 'fresnel_and_toon.png', 'titlealt' => 'Toon and Fresnel effects combined'),
  array('filename' => 'noise.png', 'titlealt' => '3D and 2D smooth noise on GPU, wrapped in ShaderTexture'),
), 2) .
'<p>A lot of great news about engine development below :) As always, remember you can try them all immediately by downloading a binary from our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>!</p>

<ol>
  <li><p>We have a new shiny method of rendering everything through shaders (OpenGL Shading Language, aka GLSL). All of the standard X3D effects, and our extensions, are implemented in this mode. This results in much better look of many scenes.</p>

    <p>By default, the shader rendering is used only for shapes that require it &mdash; are shadow map receivers, use bump mapping, or have an explicit shader source code assigned (by <a href="http://vrmlengine.sourceforge.net/vrml_implementation_shaders.php">the ComposedShader node</a>, or by the new <a href="http://vrmlengine.sourceforge.net/compositing_shaders.php">Compositing Shaders extensions</a>.). For testing purposes, you can switch to <i>"View -&gt; Shaders -&gt; Enable For Everything"</i> in <a href="http://vrmlengine.sourceforge.net/view3dscene.php">view3dscene</a> menu. The results should be the same (or better) than current renderer.

    <ul>
      <li><p>All the <i>lighting is calculated per-pixel</i> in shader rendering (we have the <i>Phong shading</i>, as opposed to the <i>Gouraud shading</i>). This is targeted at high-end GPUs anyway, so for now I decided that there\'s no point in making alternative per-vertex lighting. This means you should expect much nicer specular and spot light highlights. Try to make some smooth and curvy metallic surfaces to appreciate it :)</p>

        <p>Note that shapes that don\'t need any shader effects are still by default rendered through the fixed-function pipeline. So the performance of simple scenes should not suffer on low-end GPUs.</p></li>

      <li><p>Our <i>bump mapping</i> effect is very nicely unified within the new rendering process. Previously we used special bump mapping shaders, limited to common situations. Now bump mapping really works, under all lighting and texturing conditions, and uses all normal VRML/X3D lights into account.</p>

        <p>We also enable now bump mapping by default.</p>

        <p>Our bump mapping works also with two-sides lighting now.</p>
      </li>

      <li><p>The <i>shadow maps</i> implementation is also nicely unified with new rendering. This gives a huge improvement, as now we take into account the shadows in the correct place of the lighting equation, scaling down only the contribution of the obscured light. So the shadows maps work fully correctly with multiple lights and multiple shadow maps over the same shape.</p>

        <p>Shadow maps also work now with all the multi-textured shapes. And, in general, they work with every VRML/X3D lights/materials/textures settings.</p>
      </li>
    </ul>

    <p>Another huge improvement is our <i>compositing shaders</i> approach. I wrote a paper about this that will be submitted for the Web3D 2011 conference, and will be available here publicly later (I don\'t think I should make it public before being accepted). The <a href="http://vrmlengine.sourceforge.net/compositing_shaders.php">short introduction to our &quot;compositing shaders&quot; idea is here</a>, including the screenshots and information where to find the demo models. Drop me a mail if you\'d like to get a peek at my paper PDF.</p>

    <p>New <i>"View -&gt; Shaders"</i> submenu allows to choose when shaders are used.</p>

    <ul>
      <li><i>Disable</i> means <i>never</i>. Our effects will not work, standard ComposedShader will not work, shadow maps will not work. Also, screen effects (that always require shaders) are off.</li>
      <li><i>Enable When Required</i> means that we used fixed-function pipeline for the shapes that don\'t use any shader effects, and shader pipeline for shapes that do.</li>
      <li><i>Enable For Everything</i> means that we use shader pipeline for all the shapes. You will see beautiful per-pixel lighting on everything. On the other hand, rendering may be slower, unless you have really brand new GPU.</li>
    </ul>

    <p>Note that this all in development right now. There are some rough edges for now, in particular:</p>

    <ul>
      <li>Shaders caching is not implemented, so scenes with many shapes may take a while to load.</li>
      <li><i>Parallax bump mapping</i> is not ported to new renderer for now. Only standard bump mapping is available. Similarly, <i>Variance Shadow Maps</i> are not ported &mdash; only standard shadow maps work.</li>
      <li>Some less common multi-texturing options (add/subtract modes and such), and some volumetric fog options, are not ported yet to the new renderer.</li>
    </ul>
  </li>

  <li><p><a href="http://vrmlengine.sourceforge.net/demo_models.php">"Kambi VRML test suite" will be renamed to "VRML / X3D demo models"</a> upon the next release. The SVN already has many improvements:</p>

    <ul>
      <li>New directory layout, to emphasize foremost what features are tested (shadow_maps, shaders etc.)</li>
      <li>New <tt>compositing_shaders</tt> demos, testing our extensions for compositing shaders.</li>
      <li>Some new shadow_maps demos (sunny_street and others) are added. Previously they were in the <tt>papers/shadow_maps_x3d/</tt> subdir in SVN, which wasn\'t well known.</li>
      <li>Some basic demos from our vrml_engine_doc are added</li>
      <li>Many other fixes. Thanks in particular go to "Circular" for a lot of reports on the <a href="http://www.lazarus.freepascal.org/index.php/topic,12059.0.html">Lazarus forum thread</a>.</li>
    </ul>
  </li>

  <li><p><a href="http://vrmlengine.sourceforge.net/blender_stuff.php">Blender VRML/X3D exporters page</a> updated, I added there a modified version of Blender 2.56 X3D exporter, fixing small things, and adding exporting of normalMap (for our bump mapping extension).</p></li>

  <li><p>For developers using FPC 2.2.4 (or older): a bug slipped into the last engine sources, preventing compilation with FPC 2.2.4 or older. A fixed version of the sources is released, see <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_fpc_ver">FPC version notes</a> and <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_engine_src">engine sources</a>. Thanks to Stephen H. France for reporting this!</p></li>

  <li><p>view3dscene has new <i>File -&gt; Preferences</i> persistent settings for line width (controls all line visualization, like wireframe, bounding box, <tt>LineSet</tt> etc.), point size and default background color.</p></li>

  <li><p>VRML 1.0 <tt>PerspectiveCamera.heightAngle</tt> and <tt>OrthographicCamera.height</tt> support.</p></li>

  <li><p>Shadow maps <i>PCF 4 bilinear</i> fixes &mdash; it was too dark.</p></li>
</ol>
'),

    array('title' => 'view3dscene 3.9.0: new renderer, GLSL attributes, multiple viewports. Also: &quot;fundry&quot;, a way to donate to particular feature',
          'year' => 2011,
          'month' => 2,
          'day' => 6,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'glsl_flutter.png', 'titlealt' => 'GLSL demo &quot;flutter&quot; (from FreeWRL examples)'),
  array('filename' => 'upwind_turbine.png', 'titlealt' => 'Wind turbine simulations, from SSB Wind Systems, with 4 viewports'),
  array('filename' => 'atcs_viewports_frustum.png', 'titlealt' => 'Tremulous ATCS in VRML, with 2 viewports and frustum visualized in right viewport'),
), 1) .
'<p>We\'re proud to release a new version of <a href="http://vrmlengine.sourceforge.net/view3dscene.php">view3dscene 3.9.0</a>, our VRML/X3D (and other 3D models) browser. As usual, the new release is accompanied by new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">Kambi VRML game engine 2.4.0</a> (where all the magic actually happens) and new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_test_suite.php">Kambi VRML test suite 2.11.0</a> releases.</p>

<ol>
  <li><p>The main new feature of this release is a <b>new modern renderer</b>. It opens the door for pure shader rendering in the next release, which hopefully will blow your mind :) Features already implemented while improving the renderer:</p>

    <ul>
      <li>GLSL attributes from VRML/X3D nodes: support for <a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/shaders.html"><tt>FloatVertexAttribute</tt>, <tt>Matrix3VertexAttribute</tt>, <tt>Matrix4VertexAttribute</tt> nodes</a>.</li>
      <li><a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/enveffects.html#LocalFog">LocalFog</a> support. This allows you to limit (or turn off) fog for particular shapes. Our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_fog_volumetric">volumetric fog</a> extensions are available for this type of fog, as well as normal <tt>Fog</tt>.</li>
      <li><a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/enveffects.html#FogCoordinate">FogCoordinate</a> node support (explicit per-vertex fog intensities). <a href="http://vrmlengine.sourceforge.net/vrml_implementation_environmentaleffects.php">Support details are here</a>.</li>
      <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_bump_mapping">Bump mapping extensions</a> support for every shape (including X3D triangle/quad sets/strips/fans).</li>
      <li><tt>ElevationGrid.creaseAngle</tt> is now working correctly (previously only all smooth or all flat normals were possible for ElevationGrid).</li>
      <li>Loading GLSL shader source from data URI. For example, you can prefix inline shader source with line "<tt>data:text/plain,</tt>", which is a spec-conforming method of putting shader source inline (even though you can still omit it for our engine). See <a href="http://vrmlengine.sourceforge.net/vrml_implementation_shaders.php">examples in our docs here</a>, also the "GLSL Vertex Shader" example in <a href="http://freewrl.sourceforge.net/examples.html">FreeWRL examples</a>.</li>
    </ul>

    <p>With the new renderer, you should enjoy better speed on many scenes &mdash; in some cases the improvement is large (although, admittedly, in some cases it\'s not really noticeable). If you\'re curious, some (not impressive, but also not bad) <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/branches/view3dscene-old-renderer-for-comparison/STATS.txt">results are here</a>.</p>

    <p>For programmers, a description of how the new renderer works is available in our <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/section.vrml_arrays.html">documentation (section "Geometry Arrays")</a>. You can grab it in PDF or other formats from <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc.php">here</a>.</p></li>

  <li><p>Another new feature are <b>multiple viewports</b>. This was <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/section.custom_viewports.html">already implemented in our engine</a>, now it\'s used in view3dscene. Just open any scene, and try the new <i>Display -&gt; 1/2/4 viewports</i> menu items, and you will see what I mean. Hope you like this :) Remember that the main (upper-left) viewport is still the central one, for example it controls the headlight.</p>

    <p>Thanks to Jens van Schelve for suggesting this. A cool fact: the guys at <a href="http://www.ssbwindsystems.de/">SSB Wind Systems</a> are using our <a href="http://vrmlengine.sourceforge.net/view3dscene.php">view3dscene</a> to visualize wind turbine simulations :) You can see a screenshot of their simulation output on the right.</p></li>

  <li><p>Other important new features / fixes:</p>
    <ul>
      <li>The <b>screenshot options work now more reliably</b> on modern GPUs (that have <i>Framebuffer Object</i>). This allows to hide the window during screenshot process on all the platforms, and capture larger image sizes reliably. See <a href="http://vrmlengine.sourceforge.net/view3dscene.php#section_screenshot"><tt>--screenshot</tt> and <tt>--screenshot-range</tt> options documentation</a>.</li>
      <li><tt>TouchSensor.hitTexCoord_changed</tt> implemented, <tt>hitNormal_changed</tt> improved to generate smooth normals. See <a href="http://vrmlengine.sourceforge.net/vrml_implementation_pointingdevicesensor.php">support details here</a>.</li>
      <li>For programmers, an improved TVRMLShape.LocalTriangulate callback is available. See <a href="https://sourceforge.net/apps/phpbb/vrmlengine/viewtopic.php?f=3&amp;t=25">this forum thread for more information</a>.</li>
    </ul>
  </li>

  <li><p>A new website feature is the possibility to <b>donate money specifically for implementing a particular feature</b>: <a href="https://fundry.com/project/91-kambi-vrml-game-engine">go to fundry page for our engine</a>. The fundry widget is available also on the <a href="http://vrmlengine.sourceforge.net/support.php">Forum page (that I overuse for other support and donation links)</a>.</p></li>

  <li><p>At the end: I decided to <b>deprecate some of our old extensions</b>. As far as I know noone used them, and they are rather useless in the light of new features:</p>

    <ul>
      <li>Fog.alternative &mdash; useless, because all decent (even old) GPUs support EXT_fog_coord. And for ancient GPUs automatic fallback to the non-volumetric fog works well enough.
      <li>Material.fogImmune &mdash; useless, as newly implemented LocalFog node allows you to locally disable fog too. LocalFog also allows much more (like locally <i>enable</i> fog), and it\'s part of the X3D specification. So our poor Material.fogImmune extension has no place anymore.
      <li>Also, menu item to switch "Smooth Shading" is removed from view3dscene menu. Forcing flat shading on the whole scene seemed rather useless debug feature. You can always set IndexedFaceSet.creaseAngle = 0 in your files (in fact it\'s the default) to achieve the same effect.
    </ul>
  </li>
</ol>'),

    array('title' => 'Development news: first milestone of new renderer reached, GLSL attributes and other new features',
          'year' => 2011,
          'month' => 1,
          'day' => 18,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'glsl_flutter.png', 'titlealt' => 'GLSL demo &quot;flutter&quot; (from FreeWRL examples)'),
  array('filename' => 'venus_spheremap.png', 'titlealt' => 'Venus model with environment sphere mapping (model referenced from FreeWRL examples)'),
), 1) .
'<p>I have committed to SVN a large rework of our renderer. Everything is now rendered through <i>locked interleaved vertex arrays</i>. And I mean <i>everything</i>, really every feature of VRML/X3D shapes &mdash; all colors, normals, texture coords etc. are loaded through vertex arrays. This opens wide the door for much more optimized, modern renderer using exclusively VBOs for nearest release. It will also eventually allow OpenGL ES version for modern mobile phones. (But shhhhh, this is all not ready yet.)</p>

<p>Improvements already done while improving our renderer:</p>

<ul>
  <li>GLSL attributes from VRML/X3D nodes: support for <tt>FloatVertexAttribute</tt>, <tt>Matrix3VertexAttribute</tt>, <tt>Matrix4VertexAttribute</tt> nodes.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_bump_mapping">Bump mapping extensions</a> support for every shape (including X3D triangle/quad sets/strips/fans).</li>
  <li><tt>ElevationGrid.creaseAngle</tt> is now working correctly (previously only all smooth or all flat normals were possible for ElevationGrid).</li>
  <li><tt>FogCoordinate</tt> node support (explicit per-vertex fog intensities).</li>
  <li>Loading GLSL shader source from data URI. For example, you can prefix inline shader source with line "<tt>data:text/plain,</tt>", which is a spec-conforming method of putting shader source inline (even though you can still omit it for our engine). For a demo, see the "GLSL Vertex Shader" example in <a href="http://freewrl.sourceforge.net/examples.html">FreeWRL examples</a>.</li>
</ul>

<p>As always, you can test the latest development version by downloading binary from our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.</p>

<p>(Note that the <tt>Text</tt> nodes are an exception, they don\'t benefit from new renderer features. Parts of <tt>Text</tt> geometry are rendered through a different method, that is not integrated with vertex arrays. This will not be touched for next release, text nodes are not that much important.)</p>

<p>(Note that the fglrx (ATI Radeon proprietary drivers under Linux) sucks, as always. GLSL vertex attributes, and bump mapping, currently require that you change <i>Preferences -&gt; Rendering Optimizaton</i> to None. That\'s because glEnableVertexArrayARB seemingly doesn\'t work inside display lists. This problem doesn\'t occur with any other drivers (even with Radeon drivers for the same graphic card but on Mac OS X), so it\'s another clear fglrx fault. This will be fixed nicer before release, as VBO renderer without display lists will probably avoid these problems entirely.)</p>'),

    array('title' => 'view3dscene 3.8.0: 3D sound, skinned H-Anim, more',
          'year' => 2011,
          'month' => 1,
          'day' => 6,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'sound.png', 'titlealt' => 'Sound demo (from Kambi VRML test suite)'),
  array('filename' => 'lucy_test.png', 'titlealt' => 'Lucy (from Seamless3d test page)'),
  array('filename' => 'lucy_joints_visualization.png', 'titlealt' => 'Lucy with our joints visualization'),
), 1) .
'<p><b>3D sound in VRML/X3D</b> worlds is implemented. Grab the new ' . news_a_href_page('view3dscene 3.8.0', 'view3dscene') . ', and for some demo open files <tt>x3d/sound_final.x3dv</tt> and <tt>x3d/sound_location_animate.x3dv</tt> from the ' . news_a_href_page('kambi_vrml_test_suite', 'kambi_vrml_test_suite') . '. ' . news_a_href_page('Detailed documentation for Sound support is here', 'vrml_implementation_sound') . '.</p>

<p>Note that you have to install some additional libraries to hear sounds (OpenAL to hear anything, and VorbisFile to load OggVorbis format). For Windows, these are already included in the zip file, and you actually don\'t have to do anything. For Linux, you should install them using your package managar. For Mac OS X, ' . news_a_href_page('OpenAL is already preinstalled and you can get VorbisFile from fink', 'macosx_requirements') . '.</p>

<p>If you want to mute / unmute sound, you can use <i>File -&gt; Preferences -&gt; Sound</i> menu item of view3dscene. There\'s also <i>File -&gt; Preferences -&gt; Sound Device</i> choice. ' . news_a_href_page('Command-line options for controlling sound are documented here', 'openal') . '.</p>

<p>For developers, as usual we release a <b>new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">Kambi VRML game engine 2.3.0</a></b>. Besides sound in VRML/X3D, you will notice a new shiny SoundEngine (instance of TALSoundEngine, in ALSoundEngine unit) that makes using OpenAL a breeze from ObjectPascal code. Sample usage:</p>

<pre class="sourcecode">
var Buffer: TALBuffer;
...
Buffer := SoundEngine.LoadBuffer(\'sample.wav\');
SoundEngine.PlaySound(Buffer, ...); // see TALSoundEngine.PlaySound parameters
</pre>

<p>See the <a href="http://vrmlengine.sourceforge.net/reference.php">engine reference</a>, in particular <a href="http://vrmlengine.sourceforge.net/apidoc/html/ALSoundEngine.TALSoundEngine.html">TALSoundEngine class reference</a>, for details. You can try adding this code spinnet to any example in engine sources, e.g. to the <tt>examples/vrml/scene_manager_demos.lpr</tt>.</p>

<p><b>Animating skinned H-Anim humanoids</b> is also implemented. You can use view3dscene to open e.g. <a href="http://www.seamless3d.com/browser_test/index.html">"Lucy" examples</a> from Seamless3D, also "The famous boxman" linked from the bottom of <a href="http://doc.instantreality.org/tutorial/humanoid-animation/">InstantReality H-Anim overview</a>. The <a href="http://vrmlengine.sourceforge.net/vrml_implementation_hanim.php">details about H-Anim support are here</a>. The new view3dscene menu item <i>"Edit -&gt; Add Humanoids Joints Visualization"</i> may be useful too.</p>

<p>See also the video below. At first you see InstantReality results and then the view3dscene. Thanks to Peter "griff" Griffith for testing and creating this video!</p>

' . (!HTML_VALIDATION ? '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/v20CFbKWAYU?fs=1&amp;hl=pl_PL"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/v20CFbKWAYU?fs=1&amp;hl=pl_PL" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>' : '') . '

<p>Some <b>other notable features</b> implemented:</p>

<ul>
  <li><tt>MultiGeneratedTextureCoordinate</tt> node introduced, to better define the <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord">Box/Cone/Cylinder/Sphere.texCoord</a>.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord_bounds">Texture coord generation dependent on bounding box (TextureCoordinateGenerator.mode = BOUNDS*)</a>. This allowed fixing shadow maps implementation for the case when shape has a texture but no explicit texture coordinate node.</li>
  <li><a href="http://vrmlengine.sourceforge.net/reference.php">Engine reference for developers</a> improved a lot.</li>
</ul>

<p>Also <b>' . news_a_href_page('castle 0.9.0', 'castle') . ' is released</b>. This doesn\'t bring any new user-visible features, however internally a lot of stuff was simplified and ported to our engine 2.x line.</p>')

);
