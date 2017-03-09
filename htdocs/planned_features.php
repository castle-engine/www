<?php
require_once 'castle_engine_functions.php';
castle_header('Planned features (roadmap)', array(
  'path' => array('documentation')
));
echo pretty_heading($page_title);
?>

<p>Don't see the feature you need? <a href="<?php echo FORUM_URL; ?>">Tell us on the forum</a>:)

<p>If you would like to see some feature implemented sooner,
please <a href="<?php echo PATREON_URL; ?>">support the engine development on Patreon!</a>

<h2>Incoming in the next release</h2>

<ul>
  <li>
    <p><b>Easy iOS recompilation</b>

    <p>The goal is to easily recompile any project for
    iOS using our <a href="https://github.com/castle-engine/castle-engine/wiki/Build-Tool">build tool "castle-engine"</a>.
    Just like it's trivial right now to compile for Android and desktop platforms.

    <p>Also, as much as possible of existing "services", like
    <?php api_link('TInAppPurchases', 'CastleInAppPurchases.TInAppPurchases.html'); ?> API,
    should work out-of-the-box on the iOS.
  </li>
</ul>

<h2>Future plans</h2>

<ul>
  <li><p><b>Delphi compatibility</b>

    <p>As for the Delphi version:
    <ul>
      <li><p>This will be <i>Delphi 10 Berlin</i>,
        as Borland made that version available for free some time ago.

      <li><p>If someone will sponsor another Delphi version,
        then I will gladly port to it too.

        <p>I'm afraid that Delphi costs a <i>lot</i>,
        so I cannot afford to buy it on my own. Especially since, personally,
        I work on Linux with FPC + Lazarus.

      <li><p>Of course, patches to add/fix support for other Delphi version
        are always accepted.
    </ul>

    <p>Michalis is still an open-source fanatic, and most of the engine development
    happens on Linux, so don't worry &mdash; the FPC is still, and forever, first:)
    But Delphi compatibility will be quite easy to add,
    and I may improve some APIs BTW (like a vector3 API),
    and it will open us to more developers.

  <li><p><b>Physics</b></p>

    <p>Integrate our engine with a physics engine.
    Most preferably <a href="http://bulletphysics.org/">Bullet</a>, although this will require proper translation of Bullet API to C and then to FPC (as Buller is in C++, it's not readily usable from anything other than C++). Eventually <a href="http://www.ode.org/">ODE</a> (much simpler C API). Allow to easily use it in new games for programmers. Allow to use it in VRML/X3D models by following the X3D "Rigid body physics" component.</p>

    <p>Implementing this feature will most likely be split into a couple of small releases:</p>

    <ol>
      <li><p>Basic integration with a physics engine, to add collisions and rigid body physics at the TCastleScene level (whole scene as a single rigid body). So e.g. player could collide with a whole scene using a physics engine, without our custom octrees. Gravity could work using physics engine.
      <li><p>Allow using rigid body physics X3D component. This means that a single rigid body may be a shape, or group of shapes, in X3D. So a scene becomes a (possibly interconnected) set of many rigid bodies.
      <li><p>Add to Blender exporter ability to mark rigid body stuff in X3D.
      <li><p>Soft body, liquids, other special features.
    </ol>
  </li>

  <li><p><b>Visual designing of the castle components</b>

    <p>Inside a Lazarus form. Like <i>GLScene</i> and <i>FireMonkey 3d</i>.

    <p>Implementing this feature will most likely be split into a couple of small releases:</p>

    <ol>
      <li><p>Visually design 2D controls within <code>TCastleControl</code>.
      <li><p>Visually design T3D hierarchy in <code>SceneManager.Items</code>.
        So you could look and move whole <code>TCastleScene</code> instances.
      <li><p>Make published, and editable through Lazarus, various properties
        currently only public. E.g. we need to figure out some way to edit
        vectors and matrices in Lazarus (they are records/old-style objects
        internally).
      <li><p>Design X3D nodes hierarchy (inside the TCastleScene) visually,
        which means you can edit shapes, materials, textures...
    </ol>

  <li><p><b>view3dscene as an Android application</b>

  <li><p><b>Terrain designer</b>

    <p>Easily design a height map (X3D <?php api_link('ElevationGrid', 'X3DNodes.TElevationGridNode.html'); ?> node, with trees, rocks, grass). Saved and loaded as an X3D file.

    <p>Implementing this feature will most likely be split into a couple of small releases:</p>

    <ol>
      <li><p>Edit the heights.</li>
      <li><p>Edit the grass and trees and rocks.</li>
      <li><p>Efficiently render huge amounts of grass and trees and rocks.</li>
    </ol>

  <li><p><b>Blender X3D exporter improvements</b>

    <p>Current Blender X3D exporter doesn't support animations,
    configuring collisions (X3D <?php api_link('Collision', 'X3DNodes.TCollisionNode.html'); ?> node),
    multitexturing,
    natural normalmap export,
    3D sound sources and more.
    We would like to fix it!:) This will be useful for everyone using Blender and X3D,
    not only with our engine.

    <p>See also our page about <a href="creating_data_blender.php">creating data in Blender</a>
    and <a href="https://github.com/castle-engine/castle-engine/wiki/Blender">hints
    about exporting from Blender to X3D</a>.

    <p>While doing this, we may also implement <a href="https://doc.x3dom.org/author/Shaders/CommonSurfaceShader.html">CommonSurfaceShader</a> node,
    which is an InstantReality / X3DOM extension for specifying normalmaps.
    This could replace <?php echo a_href_page('our current bump mapping X3D extension',
    'x3d_implementation_texturing_extensions'); ?>,
    and be used by the Blender exporter.</p>

  <li><p><b>Android Cardboard (VR)</b>

    <p>Maybe also other VR devices &mdash; depending on demand, and our access to test devices.

  <li><p><b>Ready components to replicate data over the Internet</b>

    <p>Allowing to trivially get multi-playter functionality in your games.

  <li><p><b>More renderers</b>

    <p>Vulkan renderer. Maybe Metal renderer. Maybe Direct3D renderer.

  <li><p><b>Larger scene processing and rendering improvements:</b>

    <ol>
      <li><p><b>Animations blending</b>

        <p>To smoothly fade in/out animation,
        with cross-fade between animations,
        for things played by
        <?php api_link('TCastleScene.PlayAnimation', 'CastleSceneCore.TCastleSceneCore.html#PlayAnimation'); ?>.

      <li><p><b>Batching</b>

        <p>Batching of shapes that have equal appearance for start, to optimize the rendering.

      <li><p><b>Make TCastleScene, T3DTranform and friends to be special X3D nodes</b>

        <p>This would make the whole scene manager a single graph of X3D nodes,
        allowing for more sharing in code.
        The T3DTranform would be just like TTransforNode, but a little diferently
        optimized (but it would become toggable).

      <li><p><b>Make TCastleScene descend from T3DTranform?</b>

        <p>Also, allow <code>SceneManager.MainScene</code> to have some transformation
        (right now, it's not 100% correct).

      <li><p><b>Unify OpenGL and OpenGLES shaders</b>

        <p>Currently, our desktop OpenGL shaders do always <i>Phong</i> shading.
        To get <i>Gouraud</i> shading on desktop OpenGL,
        you need to use the (old) fixed-function pipeline.
        This is somewhat dirty, it would be more natural to be able to switch
        our shader pipeline between <i>Phong</i> and <i>Gouraud</i> shading.

        <p>Moreover, our desktop OpenGL shaders right now use old
        GLSL stuff <code>gl_Xxx</code>. This allowed them to work even on old
        GPUs, but makes the implementation quite complicated,
        as the mobile OpenGLES shaders cannot use the <code>gl_Xxx</code> variables.
        So the code of the shader pipeline is complicated,
        with a lot of conditions to do things differently on (desktop) OpenGL
        and (mobile) OpenGLES.
        Right now, all sensible GPUs handle shaders, and for truly ancient GPUs
        &mdash; we can just fallback on the fixed-function pipeline.
        So the code can be simplified.

        <p>Moreover, our mobile OpenGLES shaders right now always do <i>Gouraud</i>
        shading. It would be sensible to allow using <i>Phong</i> shading on
        selected shapes, even on mobile, as in some cases the performance is
        acceptable.

        <p>Summing it up:</p>

        <ul>
          <li><p>Remove the shader pipeline using GLSL <code>gl_Xxx</code> variables.
            Bring the OpenGL and OpenGLES code paths closer together.
          <li><p>Offer to switch between <i>Gouraud</i> and <i>Phong</i> shaders,
            on both OpenGL and OpenGLES, in the shader pipeline.
        </ul>
    </ol>

  <li><p><b>WebGL (HTML5) support</b>

    <p>But this waits for the possibility from FPC to recompile to web (that is, JS or WebAsembly, probably through LLVM). Then our engine will jump on to the web platform. (Unlike the <a href="https://github.com/castle-engine/castle-engine/wiki/Web-Plugin">current web plugin</a>, which is soon deprecated by all browsers, unfortunately.)

  <li>
    <p><b>Scripting in JavaScript</b></p>
    <p>Allow to use JavaScript (ECMAScript) directly inside VRML/X3D files (in Script nodes). This will follow VRML/X3D specification. Implementation will be through <a href="http://besen.sourceforge.net/">besen</a> (preferably, if it will work well enough), SpiderMonkey, or maybe some other JS library.</p>
  </li>

  <li>
    <p><b>Particle systems</b>
    <p>With a designer, probably. Probably following the X3D "particle system" component, so it will be saved and loaded as an X3D file.
  </li>

<?php /*
  <li>
    <p><b>Use Cocoa under Mac OS X</b></p>

    <p>We already have a native look and feel, and easy installation,
    under Mac OS X, see
    <a href="<?php echo CURRENT_URL; ?>old_news.php?id=devel-2013-04-19">relevant news</a>
    and <a href="<?php echo CURRENT_URL; ?>macosx_requirements.php">docs for Mac OS X</a>.
    Our programs no longer have to use X11 and GTK under Mac OS X.
    Still, current solution is not optimal:
    we use LCL with Carbon under the hood. Carbon is deprecated and only
    32-bit (Cocoa should be used instead), and depending on LCL has it's
    own problems (mouse look is not smooth with LCL message loop).

    <p>The proposed task is to implement nice Cocoa backend
    in <code>CastleWindow</code> unit. Contributions are welcome.
    This is an easy and rewarding task for a developer interested in Mac OS X.
  </li>

*/ ?>

  <li>
    <p><b>Support Material.mirror field for OpenGL rendering</b></p>
    <p>An easy way to make planar (on flat surfaces) mirrors. Just set Material.mirror field to something > 0 (setting it to 1.0 means it's a perfect mirror, setting it to 0.5 means that half of the visible color is coming from the mirrored image, and half from normal material color).</p>
    <p>Disadvantages: This will require an additional rendering pass for such shape (so expect some slowdown for really large scenes). Also your shape will have to be mostly planar (we will derive a single plane equation by looking at your vertexes).</p>
    <p>Advantages: The resulting mirror image looks perfect (there's no texture pixelation or anything), as the mirror is actually just a specially rendered view of a scene. The mirror always shows the current scene (there are no problems with dynamic scenes, as mirror is rendered each time).</p>
    <p>This will be some counterpart to current way of making mirrors by RenderedTexture (on flat surfaces) or <a href="x3d_implementation_cubemaptexturing.php">GeneratedCubeMap</a> (on curvy surfaces).</p>
  </li>

  <li>
    <p><b>Advanced networking support</b></p>
    <p>Basic networiking support is done already, we use <a href="http://wiki.freepascal.org/fphttpclient">FpHttpClient unit distributed with FPC</a>, see <a href="https://castle-engine.sourceforge.io/old_news.php?id=devel-2013-04-19">relevant news entry</a>. Things working: almost everything handles URLs, we support <code>file</code> and <code>data</code> and <code>http</code> URLs.

    <p>Things missing are listed below (some of them may done by adding
    integration with <a href="http://lnet.wordpress.com/">LNet</a> or
    <a href="http://www.ararat.cz/synapse/">Synapse</a>, see also nice
    intro to Synapse on <a href="http://wiki.freepascal.org/Synapse">FPC wiki</a>).

    <ol>
      <li><p><b>Support for <code>https</code></b>. By sending patches to add it to
        FpHttpClient. Or by using LNet or Synapse (they both include https
        support).

      <li><p><b>Support for <code>ftp</code></b>. By using LNet or Synapse, unless
        something ready in FPC appears in the meantime.
        Both LNet (through LFtp unit) and Synapse (FtpGetFile) support ftp.

      <li><p><b>Support for HTTP basic authentication</b>. This can be done in our
        CastleDownload unit. Although it would be cleaner to implement it
        at FpHttpClient level, see
        <a href="http://bugs.freepascal.org/view.php?id=24335">this
        proposal</a>.
        Or maybe just use LNet or Synapse, I'm sure they have some support
        for it.

      <li><p><b>Ability to cancel the ongoing download</b>.
        Add a "cancel" button to CastleWindowProgress for this.
        See the task below (background downloading) for ideas how to do it.

      <li><p><b>Ability to download resources in the background</b>,
        while the game is running. Technically this is connected to the previous
        point: being able to reliably cancel the download.

        <p>There is a question how to do it.
        We can use <code>TThread</code> for downloads,
        maybe even a couple of threads each for a separate download.
        We can use API that doesn't block (like LNet or Sockets,
        with Timeout > 0).
        We can do both.

        <p>Using separate thread(s) for download seems like a good idea,
        the synchronization is not difficult as the thread needs only
        to report when it finished work.

        <p>The difficult part is reliably breaking the download.
        Using something like <code>TThread.Terminate</code> will not do anything
        good while the thread is hanging waiting for socket data
        (<code>TThread.Terminate</code> is a graceful way to close the thread,
        it only works as often as the thread explicitly checks
        <code>TThread.Terminated</code>). Hacks like <code>Windows.TerminateThread</code>
        are 1. OS-specific 2. very dirty,
        as <code>TThread.Execute</code> has no change to release allocated memory
        and such.
        The bottom line: <i>merely using TThread does <b>not</b> give
        you a reliable and clean way to break the thread execution at any time</i>.

        <p>This suggests that you <i>have</i> to use non-blocking
        API (so LNet or Sockets is the way to go,
        FpHttpClient and Synapse are useless for this)
        if you want to reliably break the download.
        Using it inside a separate thread may still be a good idea,
        to not hang the main event loop to process downloaded data.
        So the correct answer seems <i>use LNet/Sockets (not
        FpHttpClient/Synapse), with non-blocking API, within a TThread;
        thanks to non-blocking API you can guarantee checking
        <code>TThread.Terminated</code> at regular intervals</i>.

        <p>I'm no expert in threads and networking, so if anyone has
        any comments about this (including just comfirming my analysis)
        please let me (Michalis) know :)

        <!--
        http://wiki.freepascal.org/Example_of_multi-threaded_application:_array_of_threads
        http://www.freepascal.org/docs-html/rtl/classes/tthread.html
        http://stackoverflow.com/questions/4044855/how-to-kill-a-thread-in-delphi
        http://stackoverflow.com/questions/1089482/a-proper-way-of-destroying-a-tthread-object
        http://stackoverflow.com/questions/3788743/correct-thread-destroy
        -->

      <li><p><b>Support X3D <code>LoadSensor</code> node</b>.

      <li><p><b>Caching on disk of downloaded data</b>.
        Just like WWW browsers, we should be able to cache
        resources downloaded from the Internet.
        <ul>
          <li>Store each resource under a filename in cache directory.
          <li>Add a function like ApplicationCache, similar existing ApplicationData
            and ApplicationConfig, to detect cache directory.
            For starters, it can be ApplicationConfig (it cannot be
            ApplicationData, as ApplicationData may be read-only).
            Long-term, it should be something else (using the same
            directory as for config files may not be adviced,
            e.g. to allow users to backup config without backuping cache).
            See standards suitable for each OS (for Linux, and generally Unix
            (but not Mac OS X) see <a href="http://standards.freedesktop.org/basedir-spec/basedir-spec-latest.html">basedir-spec</a>;
            specific Microsoft, Apple specs may be available
            for Windows and Mac OS X).
          <li>Probably it's best to store a resource under a filename
            calculated by MD5 hash on the URL.
          <li>For starters, you can just make the max cache life
            a configurable variable for user.
            Long-term: Like WWW browsers, we should honor various HTTP headers that
            say when/how long cache can be considered valid,
            when we should at least check with server if something changed,
            when we should just reload the file every time.
          <li>Regardless of above, a way to reload file forcibly disregarding
            the cache should be available (like Ctrl+Shift+R in WWW browsers).
          <li>A setting to control max cache size on disk, with some reasonable
            default (look at WWW browsers default settings) should be available.
        </ul>

        <p>Note: don't worry about caching in memory, we have this already,
        for all URLs (local files, data URIs, network resources).
  </ol>
</ul>

<?php castle_footer(); ?>
