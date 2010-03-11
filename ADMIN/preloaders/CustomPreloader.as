package preloaders
{
    import flash.display.DisplayObject;
    import flash.display.GradientType;
    import flash.display.Sprite;
    import flash.events.Event;
    import flash.filters.DropShadowFilter;
    import flash.geom.Matrix;
    import flash.text.TextField;
    import flash.text.TextFormat;
    
    public class CustomPreloader extends PreloaderDisplayBase
    {
        /*[Embed("assets/preloader.png") ]
        [Bindable] public var Logo:Class;  */
		[Embed("assets/compromiso.svg") ]
        [Bindable] public var Logo:Class;  
        
        private var t:TextField;
        private var f:DropShadowFilter=new DropShadowFilter(2,45,0xFFFFFF,0.5)
        private var pathfLogo:Sprite= new Sprite();
        private var bar:Sprite=new Sprite();
        private var barFrame:Sprite;
        private var mainColor:uint=0xFFFFFF;
        private var verde:uint=0x008348;
        
        public function CustomPreloader()
        {
            super();
        }
        
        // This is called when the preloader has been created as a child on the stage.
        //  Put all real initialization here.
        override public function initialize():void
        {
            super.initialize();
            
            clear();  // clear here, rather than in draw(), to speed up the drawing
            
            var indent:int = 20;
            var height:int = 20;
            
            //creates all visual elements
            createAssets();
            this.addEventListener(Event.ENTER_FRAME,enterFrame);
        }
        //this is our "animation" bit
        override protected function draw():void
        {
            t.text = int(_fractionLoaded*100).toString()+"%";
            
             pathfLogo.y = (stageHeight-75)/2;
            pathfLogo.x= (stageWidth-75)/2+75;
            t.y = (stageHeight-t.height)/2+4;
            t.x= (stageWidth-t.width)/2+8;
        
        }
        
        
        
        protected function createAssets():void
        {
            //create the logo
            var log:DisplayObject= new Logo();
            pathfLogo.addChild(log);
            log.x=-75;
            log.y=-75;
            pathfLogo.y = (stageHeight-75)/2;
            pathfLogo.x= (stageWidth-75)/2+75;
            pathfLogo.filters = [f];
         	addChild(pathfLogo);
            
           
            //create text field to show percentage of loading
            t = new TextField()
           // t.y = barFrame.y-27;
            t.filters=[f];
            addChild(t);
            //we can format our text
            var s:TextFormat=new TextFormat("Verdana",null,0x000000,null,null,null,null,null,"right");
            t.defaultTextFormat=s;
        }
        
        protected function clear():void
        {    
            // Draw gradient background
            var b:Sprite = new Sprite;
             var matrix:Matrix =  new Matrix();
            matrix.createGradientBox(stageWidth, stageHeight, Math.PI/2);   
            b.graphics.beginGradientFill(GradientType.LINEAR,   
                                        [ mainColor,0xE7E7E7],             
                                        [1,1],                           
                                        [0,255],
                                        matrix
                                        );
            b.graphics.drawRect(0, 0, stageWidth, stageHeight);
            b.graphics.endFill(); 
            addChild(b);
        }
		
		private function enterFrame(e:Event):void
		{
			pathfLogo.rotation+=5;
		}
    }        
}