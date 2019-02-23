{ Build a simplest IndexedFaceSet node (mesh) to display a texture. }

uses SysUtils,
  CastleWindow, CastleScene, CastleSceneManager,
  CastleColors, CastleVectors, CastleFilesUtils, X3DNodes, CastleTransform;

function BuildRootNode(const ImageUrl: String): TX3DRootNode;
var
  Shape: TShapeNode;
  Geometry: TIndexedFaceSetNode;
  Coordinate: TCoordinateNode;
  TextureCoordinate: TTextureCoordinateNode;
  Texture: TImageTextureNode;
  Width, Height: Integer;
begin
  { Create ImageTexture node (represents the texture from file) }
  Texture := TImageTextureNode.Create;
  Texture.SetUrl([ImageUrl]);
  if Texture.TextureImage = nil then
    raise Exception.CreateFmt('Image "%s" could not be loaded', [ImageUrl]);

  { Read the texture size (it is necessary to determine geometry size) }
  Width  := Texture.TextureImage.Width;
  Height := Texture.TextureImage.Height;

  { Create Coordinate node (position of quad in 3D) }
  Coordinate := TCoordinateNode.Create;
  Coordinate.SetPoint([
    Vector3(-Width / 2, -Height / 2, 0),
    Vector3( Width / 2, -Height / 2, 0),
    Vector3( Width / 2,  Height / 2, 0),
    Vector3(-Width / 2,  Height / 2, 0)
  ]);

  { Create TextureCoordinate node (how the image is mapped onto a surface) }
  TextureCoordinate := TTextureCoordinateNode.Create;
  TextureCoordinate.SetPoint([
    Vector2(0, 0),
    Vector2(1, 0),
    Vector2(1, 1),
    Vector2(0, 1)
  ]);

  { Create Shape and IndexedFaceSet node (mesh with coordinates, texture coordinates) }
  Geometry := TIndexedFaceSetNode.CreateWithShape(Shape);
  Geometry.Coord := Coordinate;
  Geometry.TexCoord := TextureCoordinate;
  Geometry.Solid := false; // to see it from any side
  Geometry.SetCoordIndex([0, 1, 2, 3]);

  { Create Appearance (refers to a texture, connects the Texture to Shape) }
  Shape.Appearance := TAppearanceNode.Create;
  Shape.Appearance.Texture := Texture;

  Result := TX3DRootNode.Create;
  Result.AddChildren(Shape);
end;

var
  Window: TCastleWindow;
  Scene: TCastleScene;
begin
  Window := TCastleWindow.Create(Application);
  Window.Open;

  Scene := TCastleScene.Create(Application);
  Scene.Load(BuildRootNode(ApplicationData('face.png')), true);
  Scene.Spatial := [ssRendering, ssDynamicCollisions];
  Scene.ProcessEvents := true;
  Window.SceneManager.Items.Add(Scene);
  Window.SceneManager.MainScene := Scene;

  Application.Run;
end.